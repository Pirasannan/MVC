<?php
class Message {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all conversations for a user
    public function getConversations($userId, $userType) {
        $this->db->query('
            SELECT 
                c.conversation_id,
                c.user1_id,
                c.user2_id,
                c.last_message_time,
                m.message as last_message,
                CASE 
                    WHEN c.user1_id = :user_id THEN u2.name
                    ELSE u1.name
                END as user_name,
                CASE
                    WHEN c.user1_id = :user_id THEN LOWER(u2.role)
                    ELSE LOWER(u1.role)
                END as user_role,
                CASE 
                    WHEN c.user1_id = :user_id THEN c.user2_id
                    ELSE c.user1_id
                END as user_id,
                (SELECT COUNT(*) FROM messages 
                 WHERE conversation_id = c.conversation_id 
                 AND sender_id != :user_id 
                 AND is_read = 0) as unread_count,
                (m.sender_id = :user_id) as is_sent
            FROM conversations c
            LEFT JOIN Users u1 ON c.user1_id = u1.id
            LEFT JOIN Users u2 ON c.user2_id = u2.id
            LEFT JOIN messages m ON m.message_id = (
                SELECT mm.message_id
                FROM messages mm
                WHERE mm.conversation_id = c.conversation_id
                ORDER BY mm.created_at DESC, mm.message_id DESC
                LIMIT 1
            )
            WHERE c.user1_id = :user_id OR c.user2_id = :user_id
            ORDER BY c.last_message_time DESC
        ');

        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get messages for a conversation
    public function getMessages($conversationId) {
        $this->db->query('
            SELECT 
                message_id,
                conversation_id,
                sender_id,
                message,
                is_read,
                created_at
            FROM messages
            WHERE conversation_id = :conversation_id
            ORDER BY created_at ASC
        ');

        $this->db->bind(':conversation_id', $conversationId);
        return $this->db->resultSet();
    }

    // Get new messages since last check
    public function getNewMessages($conversationId, $lastMessageId) {
        $this->db->query('
            SELECT 
                message_id,
                conversation_id,
                sender_id,
                message,
                is_read,
                created_at
            FROM messages
            WHERE conversation_id = :conversation_id
            AND message_id > :last_message_id
            ORDER BY created_at ASC
        ');

        $this->db->bind(':conversation_id', $conversationId);
        $this->db->bind(':last_message_id', $lastMessageId);
        return $this->db->resultSet();
    }

    // Send a message
    public function sendMessage($conversationId, $senderId, $messageText) {
        $this->db->query('
            INSERT INTO messages (conversation_id, sender_id, message, created_at)
            VALUES (:conversation_id, :sender_id, :message, NOW())
        ');

        $this->db->bind(':conversation_id', $conversationId);
        $this->db->bind(':sender_id', $senderId);
        $this->db->bind(':message', $messageText);

        if ($this->db->execute()) {
            // Do not fail message delivery if the conversation timestamp update fails.
            try {
                $this->updateConversationTime($conversationId);
            } catch (Exception $e) {
                // ignore non-critical update failure
            }
            return $this->db->getConnection()->lastInsertId();
        }

        return false;
    }

    // Get a single message by ID
    public function getMessageById($messageId) {
        $this->db->query('
            SELECT
                message_id,
                conversation_id,
                sender_id,
                message,
                created_at
            FROM messages
            WHERE message_id = :message_id
            LIMIT 1
        ');

        $this->db->bind(':message_id', $messageId);
        return $this->db->single();
    }

    // Update conversation last message time
    private function updateConversationTime($conversationId) {
        $this->db->query('
            UPDATE conversations 
            SET last_message_time = COALESCE(
                (SELECT MAX(created_at) FROM messages WHERE conversation_id = :conversation_id_latest),
                NOW()
            )
            WHERE conversation_id = :conversation_id
        ');

        $this->db->bind(':conversation_id_latest', $conversationId);
        $this->db->bind(':conversation_id', $conversationId);
        $this->db->execute();
    }

    // Mark conversation as read
    public function markConversationAsRead($conversationId, $userId) {
        $this->db->query('
            UPDATE messages 
            SET is_read = 1
            WHERE conversation_id = :conversation_id
            AND sender_id != :user_id
            AND is_read = 0
        ');

        $this->db->bind(':conversation_id', $conversationId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Check if user has access to conversation
    public function hasAccessToConversation($conversationId, $userId) {
        $this->db->query('
            SELECT conversation_id 
            FROM conversations
            WHERE conversation_id = :conversation_id
            AND (user1_id = :user_id OR user2_id = :user_id)
        ');

        $this->db->bind(':conversation_id', $conversationId);
        $this->db->bind(':user_id', $userId);
        return $this->db->single() ? true : false;
    }

    // Find conversation between two users
    public function findConversation($user1Id, $user2Id) {
        $this->db->query('
            SELECT conversation_id
            FROM conversations
            WHERE (user1_id = :user1_id AND user2_id = :user2_id)
            OR (user1_id = :user2_id AND user2_id = :user1_id)
        ');

        $this->db->bind(':user1_id', $user1Id);
        $this->db->bind(':user2_id', $user2Id);
        return $this->db->single();
    }

    // Create new conversation
    public function createConversation($user1Id, $user2Id) {
        $this->db->query('
            INSERT INTO conversations (user1_id, user2_id, created_at, last_message_time)
            VALUES (:user1_id, :user2_id, NOW(), NOW())
        ');

        $this->db->bind(':user1_id', $user1Id);
        $this->db->bind(':user2_id', $user2Id);

        if ($this->db->execute()) {
            return $this->db->getConnection()->lastInsertId();
        }

        return false;
    }

    // Get unread message count
    public function getUnreadCount($userId) {
        $this->db->query('
            SELECT COUNT(*) as unread_count
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.conversation_id
            WHERE (c.user1_id = :user_id OR c.user2_id = :user_id)
            AND m.sender_id != :user_id
            AND m.is_read = 0
        ');

        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result ? $result->unread_count : 0;
    }

    // Delete a message (only sender can delete)
    public function deleteMessage($messageId, $userId) {
        $this->db->query('
            DELETE FROM messages 
            WHERE message_id = :message_id 
            AND sender_id = :user_id
        ');

        $this->db->bind(':message_id', $messageId);
        $this->db->bind(':user_id', $userId);
        if (!$this->db->execute()) {
            return false;
        }

        return $this->db->rowCount() > 0;
    }

    // Update a message (only sender can update)
    public function updateMessage($messageId, $userId, $messageText) {
        $this->db->query('
            UPDATE messages
            SET message = :message
            WHERE message_id = :message_id
            AND sender_id = :user_id
        ');

        $this->db->bind(':message_id', $messageId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':message', $messageText);
        if (!$this->db->execute()) {
            return false;
        }

        return $this->db->rowCount() > 0;
    }

    // Recalculate the latest message timestamp after a delete
    public function refreshConversationLastMessageTime($conversationId) {
        $this->db->query('
            UPDATE conversations
            SET last_message_time = COALESCE(
                (SELECT MAX(created_at) FROM messages WHERE conversation_id = :conversation_id_max),
                (SELECT created_at FROM conversations WHERE conversation_id = :conversation_id_created)
            )
            WHERE conversation_id = :conversation_id_where
        ');

        $this->db->bind(':conversation_id_max', $conversationId);
        $this->db->bind(':conversation_id_created', $conversationId);
        $this->db->bind(':conversation_id_where', $conversationId);
        return $this->db->execute();
    }

    // Get conversation by ID
    public function getConversationById($conversationId) {
        $this->db->query('
            SELECT * FROM conversations 
            WHERE conversation_id = :conversation_id
        ');

        $this->db->bind(':conversation_id', $conversationId);
        return $this->db->single();
    }

    // Mark specific message as read
    public function markMessageAsRead($messageId) {
        $this->db->query('
            UPDATE messages 
            SET is_read = 1
            WHERE message_id = :message_id
        ');

        $this->db->bind(':message_id', $messageId);
        return $this->db->execute();
    }

    // Ensure doctor/patient pairs can chat only if they had at least one approved/completed appointment
    public function hasAppointmentHistoryBetweenUsers($user1Id, $user2Id) {
        $this->db->query('
            SELECT COUNT(*) as total
            FROM appointments
            WHERE status IN ("approved", "completed")
              AND (
                    (patient_id = :user1_id AND doctor_id = :user2_id)
                 OR (patient_id = :user2_id AND doctor_id = :user1_id)
              )
        ');

        $this->db->bind(':user1_id', $user1Id);
        $this->db->bind(':user2_id', $user2Id);

        $row = $this->db->single();
        return $row && (int)$row->total > 0;
    }

    // Get chat-eligible contacts based on past appointment history
    public function getEligibleContacts($userId, $userRole, $searchTerm = '') {
        $normalizedRole = strtolower((string)$userRole);

        if ($normalizedRole === 'patient') {
            $sql = '
                SELECT DISTINCT
                    u.id AS user_id,
                    u.name AS user_name,
                    LOWER(u.role) AS user_role,
                    u.email
                FROM appointments a
                INNER JOIN Users u ON u.id = a.doctor_id
                WHERE a.patient_id = :user_id
                  AND a.status IN ("approved", "completed")
                  AND u.status = "active"
            ';

            if ($searchTerm !== '') {
                $sql .= ' AND (u.name LIKE :search OR u.email LIKE :search)';
            }

            $sql .= ' ORDER BY u.name ASC LIMIT 50';
        } elseif ($normalizedRole === 'doctor') {
            $sql = '
                SELECT DISTINCT
                    u.id AS user_id,
                    u.name AS user_name,
                    LOWER(u.role) AS user_role,
                    u.email
                FROM appointments a
                INNER JOIN Users u ON u.id = a.patient_id
                WHERE a.doctor_id = :user_id
                  AND a.status IN ("approved", "completed")
                  AND u.status = "active"
            ';

            if ($searchTerm !== '') {
                $sql .= ' AND (u.name LIKE :search OR u.email LIKE :search)';
            }

            $sql .= ' ORDER BY u.name ASC LIMIT 50';
        } else {
            $sql = '
                SELECT
                    id AS user_id,
                    name AS user_name,
                    LOWER(role) AS user_role,
                    email
                FROM Users
                WHERE id != :user_id
                  AND LOWER(role) IN ("doctor", "patient")
                  AND status = "active"
            ';

            if ($searchTerm !== '') {
                $sql .= ' AND (name LIKE :search OR email LIKE :search)';
            }

            $sql .= ' ORDER BY name ASC LIMIT 50';
        }

        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);

        if ($searchTerm !== '') {
            $this->db->bind(':search', '%' . $searchTerm . '%');
        }

        return $this->db->resultSet();
    }

    // Get all active doctors and patients except the current admin user
    public function getAdminBroadcastRecipients($adminUserId, $excludeUserId = null) {
        $sql = '
            SELECT id
            FROM Users
            WHERE id != :admin_user_id
              AND LOWER(TRIM(role)) IN ("doctor", "patient")
              AND LOWER(TRIM(status)) = "active"
        ';

        if ($excludeUserId !== null) {
            $sql .= ' AND id != :exclude_user_id';
        }

        $sql .= ' ORDER BY id ASC';

        $this->db->query($sql);
        $this->db->bind(':admin_user_id', $adminUserId);
        if ($excludeUserId !== null) {
            $this->db->bind(':exclude_user_id', (int)$excludeUserId);
        }
        return $this->db->resultSet();
    }

    // Send one admin message to all active doctors and patients
    public function broadcastAdminMessage($adminUserId, $messageText, $excludeUserId = null) {
        $recipients = $this->getAdminBroadcastRecipients($adminUserId, $excludeUserId);
        $sentMessageIds = [];

        foreach ($recipients as $recipient) {
            $recipientId = (int)($recipient->id ?? 0);
            if ($recipientId <= 0) {
                continue;
            }

            $conversation = $this->findConversation($adminUserId, $recipientId);
            $conversationId = $conversation ? (int)$conversation->conversation_id : 0;

            if ($conversationId <= 0) {
                $conversationId = (int)$this->createConversation($adminUserId, $recipientId);
            }

            if ($conversationId <= 0) {
                continue;
            }

            $messageId = $this->sendMessage($conversationId, $adminUserId, $messageText);
            if ($messageId) {
                $sentMessageIds[] = (int)$messageId;
            }
        }

        return $sentMessageIds;
    }
}

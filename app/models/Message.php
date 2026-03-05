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
                    WHEN c.user1_id = :user_id THEN u2.user_name
                    ELSE u1.user_name
                END as user_name,
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
            LEFT JOIN users u1 ON c.user1_id = u1.user_id
            LEFT JOIN users u2 ON c.user2_id = u2.user_id
            LEFT JOIN messages m ON c.conversation_id = m.conversation_id 
                AND m.created_at = c.last_message_time
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
            // Update conversation last_message_time
            $this->updateConversationTime($conversationId);
            // Get last insert ID from PDO
            $lastId = $this->db->getConnection()->lastInsertId();
            return $lastId;
        }
        
        return false;
    }

    // Update conversation last message time
    private function updateConversationTime($conversationId) {
        $this->db->query('
            UPDATE conversations 
            SET last_message_time = NOW()
            WHERE conversation_id = :conversation_id
        ');
        
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
            // Get last insert ID from PDO
            $lastId = $this->db->getConnection()->lastInsertId();
            return $lastId;
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
}

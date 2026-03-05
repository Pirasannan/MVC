<?php
class Messages extends Controller {
    private $messageModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('M_Users');
    }

    // Get conversations list
    public function getConversations() {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        $userType = $_SESSION['user_type'];
        
        try {
            $conversations = $this->messageModel->getConversations($userId, $userType);
            
            echo json_encode([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error loading conversations'
            ]);
        }
    }

    // Get user information
    public function getUserInfo($userId) {
        header('Content-Type: application/json');
        
        try {
            $user = $this->userModel->getUserById($userId);
            
            if ($user) {
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user->user_id,
                        'name' => $user->user_name,
                        'type' => $user->user_type,
                        'is_online' => $this->userModel->isUserOnline($userId)
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error loading user info'
            ]);
        }
    }

    // Get messages for a conversation
    public function getMessages($conversationId) {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        
        try {
            // Verify user has access to this conversation
            if (!$this->messageModel->hasAccessToConversation($conversationId, $userId)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Access denied'
                ]);
                return;
            }
            
            $messages = $this->messageModel->getMessages($conversationId);
            
            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error loading messages'
            ]);
        }
    }

    // Send a message
    public function sendMessage() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $conversationId = $input['conversation_id'] ?? null;
        $messageText = $input['message'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$conversationId || !$messageText) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        try {
            // Verify user has access to this conversation
            if (!$this->messageModel->hasAccessToConversation($conversationId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }
            
            $messageId = $this->messageModel->sendMessage($conversationId, $userId, $messageText);
            
            if ($messageId) {
                echo json_encode([
                    'success' => true,
                    'message_id' => $messageId
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to send message'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error sending message'
            ]);
        }
    }

    // Get new messages since last check
    public function getNewMessages($conversationId) {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        $lastMessageId = $_GET['last_message_id'] ?? 0;
        
        try {
            // Verify user has access to this conversation
            if (!$this->messageModel->hasAccessToConversation($conversationId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }
            
            $messages = $this->messageModel->getNewMessages($conversationId, $lastMessageId);
            
            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error loading new messages'
            ]);
        }
    }

    // Mark conversation as read
    public function markAsRead($conversationId) {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        try {
            $result = $this->messageModel->markConversationAsRead($conversationId, $userId);
            
            echo json_encode([
                'success' => $result
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error marking as read'
            ]);
        }
    }

    // Create new conversation
    public function createConversation() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $recipientId = $input['recipient_id'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$recipientId) {
            echo json_encode(['success' => false, 'message' => 'Missing recipient ID']);
            return;
        }
        
        try {
            // Check if conversation already exists
            $existingConversation = $this->messageModel->findConversation($userId, $recipientId);
            
            if ($existingConversation) {
                echo json_encode([
                    'success' => true,
                    'conversation_id' => $existingConversation->conversation_id
                ]);
            } else {
                // Create new conversation
                $conversationId = $this->messageModel->createConversation($userId, $recipientId);
                
                if ($conversationId) {
                    echo json_encode([
                        'success' => true,
                        'conversation_id' => $conversationId
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to create conversation'
                    ]);
                }
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error creating conversation'
            ]);
        }
    }

    // Search users to start new conversation
    public function searchUsers() {
        header('Content-Type: application/json');
        
        $searchTerm = $_GET['search'] ?? '';
        $userId = $_SESSION['user_id'];
        
        if (strlen($searchTerm) < 2) {
            echo json_encode([
                'success' => false,
                'message' => 'Search term too short'
            ]);
            return;
        }
        
        try {
            $users = $this->userModel->searchUsersForMessaging($searchTerm, $userId);
            
            echo json_encode([
                'success' => true,
                'users' => $users
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error searching users'
            ]);
        }
    }

    // Delete a message
    public function deleteMessage() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $messageId = $input['message_id'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Missing message ID']);
            return;
        }
        
        try {
            $result = $this->messageModel->deleteMessage($messageId, $userId);
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Message deleted' : 'Failed to delete message'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error deleting message'
            ]);
        }
    }

    // Get unread message count
    public function getUnreadCount() {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        
        try {
            $count = $this->messageModel->getUnreadCount($userId);
            
            echo json_encode([
                'success' => true,
                'unread_count' => $count
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error getting unread count'
            ]);
        }
    }

    // Update user activity (called periodically)
    public function updateActivity() {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        
        try {
            $this->userModel->updateLastActivity($userId);
            
            echo json_encode([
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false
            ]);
        }
    }
}

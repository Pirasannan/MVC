<?php
class Messages extends Controller {
    private $messageModel;
    private $userModel;
    private $appointmentModel;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('M_Users');
        $this->appointmentModel = $this->model('Appointment');
    }

    private function getCurrentMessagingStatus() {
        $role = strtolower((string)($_SESSION['user_role'] ?? ''));
        if (!in_array($role, ['patient', 'doctor'], true)) {
            return null;
        }

        return strtolower((string)$this->userModel->getUserStatusById((int)$_SESSION['user_id']));
    }

    // Get conversations list
    public function getConversations() {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'];
        $userType = $_SESSION['user_role'] ?? '';

        if ($this->getCurrentMessagingStatus() === 'inactive' && strtolower((string)($_SESSION['user_role'] ?? '')) === 'patient') {
            echo json_encode([
                'success' => true,
                'conversations' => []
            ]);
            return;
        }
        
        try {
            $conversations = $this->messageModel->getConversations($userId, $userType);
            $this->attachProfileImagesToRows($conversations, 'user_id');
            
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
                $profileImagePath = $this->userModel->getUserProfileImage((int)$user->user_id);
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user->user_id,
                        'name' => $user->user_name,
                        'type' => $user->user_role,
                        'profile_image' => $profileImagePath,
                        'profile_image_url' => $this->buildProfileImageUrl($profileImagePath),
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
        if (!is_array($input)) {
            $input = [];
        }
        $conversationId = $_POST['conversation_id'] ?? ($input['conversation_id'] ?? null);
        $messageText = $_POST['message'] ?? ($input['message'] ?? '');
        $userId = $_SESSION['user_id'];
        $attachmentFile = $_FILES['attachment'] ?? null;
        $attachmentData = null;

        $accountStatus = $this->getCurrentMessagingStatus();
        if ($accountStatus === 'inactive') {
            echo json_encode(['success' => false, 'message' => 'Your account is deactivated. Please contact admin.']);
            return;
        }

        if ($accountStatus === 'suspended') {
            echo json_encode(['success' => false, 'message' => 'Your account is suspended. You cannot send messages.']);
            return;
        }

        $messageText = trim((string)$messageText);
        $hasAttachment = $attachmentFile && isset($attachmentFile['tmp_name']) && !empty($attachmentFile['tmp_name']) && ($attachmentFile['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;
        
        // Validation: Check required fields
        if (!$conversationId || ($messageText === '' && !$hasAttachment)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        // Validation: Check message length (max 1000 characters)
        if (strlen($messageText) > 1000) {
            echo json_encode(['success' => false, 'message' => 'Message too long (max 1000 characters)']);
            return;
        }
        
        try {
            // Security: Verify user has access to this conversation
            if (!$this->messageModel->hasAccessToConversation($conversationId, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            // Optional attachment upload (images and PDFs)
            if ($hasAttachment) {
                $uploadResult = $this->uploadChatAttachment($attachmentFile, $userId);
                if (!$uploadResult['success']) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Attachment upload failed: ' . implode(', ', $uploadResult['errors'])
                    ]);
                    return;
                }
                $attachmentData = $uploadResult['attachment'];
            }

            $storedMessage = $messageText;
            if ($attachmentData) {
                $storedMessage = json_encode([
                    'text' => $messageText,
                    'attachment' => $attachmentData
                ], JSON_UNESCAPED_SLASHES);
            } else {
                // Store raw text; the frontend escapes it on render.
                $storedMessage = $messageText;
            }
            
            $isAdminSender = strtolower((string)($_SESSION['user_role'] ?? '')) === 'admin';

            if ($isAdminSender) {
                $conversation = $this->messageModel->getConversationById($conversationId);
                $targetUserId = null;
                if ($conversation) {
                    $targetUserId = ((int)$conversation->user1_id === (int)$userId)
                        ? (int)$conversation->user2_id
                        : (int)$conversation->user1_id;
                }

                // Always deliver to the selected chat first.
                $primaryMessageId = $this->messageModel->sendMessage($conversationId, $userId, $storedMessage);
                if (!$primaryMessageId) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to send admin message'
                    ]);
                    return;
                }

                // Then fan out to all other active doctors/patients.
                $broadcastMessageIds = $this->messageModel->broadcastAdminMessage($userId, $storedMessage, $targetUserId);

                echo json_encode([
                    'success' => true,
                    'message_id' => $primaryMessageId,
                    'broadcast_count' => count($broadcastMessageIds) + 1,
                    'message' => $storedMessage,
                    'attachment' => $attachmentData
                ]);
                return;
            }

            $messageId = $this->messageModel->sendMessage($conversationId, $userId, $storedMessage);

            if ($messageId) {
                echo json_encode([
                    'success' => true,
                    'message_id' => $messageId,
                    'message' => $storedMessage,
                    'attachment' => $attachmentData
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

    // Update a message
    public function updateMessage() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $input = [];
        }
        $messageId = $_POST['message_id'] ?? ($input['message_id'] ?? null);
        $messageText = $_POST['message'] ?? ($input['message'] ?? '');
        $userId = $_SESSION['user_id'];

        $accountStatus = $this->getCurrentMessagingStatus();
        if ($accountStatus === 'inactive') {
            echo json_encode(['success' => false, 'message' => 'Your account is deactivated. Please contact admin.']);
            return;
        }

        if ($accountStatus === 'suspended') {
            echo json_encode(['success' => false, 'message' => 'Your account is suspended. You cannot edit messages.']);
            return;
        }

        $messageText = trim((string)$messageText);

        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Missing message ID']);
            return;
        }

        try {
            $existingMessage = $this->messageModel->getMessageById($messageId);
            if (!$existingMessage) {
                echo json_encode(['success' => false, 'message' => 'Message not found']);
                return;
            }

            if ((int)$existingMessage->sender_id !== (int)$userId) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            if (!$this->messageModel->hasAccessToConversation($existingMessage->conversation_id, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            $parsedMessage = $this->decodeMessagePayload($existingMessage->message);
            $hasAttachment = !empty($parsedMessage['attachment']);

            if ($messageText === '' && !$hasAttachment) {
                echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
                return;
            }

            if (strlen($messageText) > 1000) {
                echo json_encode(['success' => false, 'message' => 'Message too long (max 1000 characters)']);
                return;
            }

            $storedMessage = $this->buildMessagePayload($messageText, $parsedMessage['attachment'] ?? null);
            $result = $this->messageModel->updateMessage($messageId, $userId, $storedMessage);

            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Message updated' : 'Failed to update message',
                'message_id' => (int)$messageId,
                'conversation_id' => (int)$existingMessage->conversation_id,
                'message_text' => $messageText,
                'attachment' => $parsedMessage['attachment'] ?? null
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error updating message'
            ]);
        }
    }

    // Upload chat attachment (images and PDFs)
    private function uploadChatAttachment($file, $userId) {
        $result = [
            'success' => false,
            'attachment' => null,
            'errors' => []
        ];

        try {
            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                $result['errors'][] = 'No attachment uploaded';
                return $result;
            }

            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $result['errors'][] = 'Upload error';
                return $result;
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
            $maxFileSize = 10485760; // 10MB

            $originalName = $file['name'] ?? 'attachment';
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $result['errors'][] = 'Only JPG, PNG, WEBP, and PDF files are allowed';
                return $result;
            }

            if (($file['size'] ?? 0) > $maxFileSize) {
                $result['errors'][] = 'File is too large. Maximum allowed size is 10MB';
                return $result;
            }

            $mimeType = $file['type'] ?? '';
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($finfo) {
                    $detected = finfo_file($finfo, $file['tmp_name']);
                    if ($detected) {
                        $mimeType = $detected;
                    }
                    finfo_close($finfo);
                }
            }

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                $result['errors'][] = 'Invalid file type';
                return $result;
            }

            $baseDir = dirname(APPROOT) . '/public/uploads/chat_attachments/' . $userId . '/';
            if (!is_dir($baseDir) && !mkdir($baseDir, 0755, true)) {
                $result['errors'][] = 'Failed to create attachment directory';
                return $result;
            }

            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $safeName = trim($safeName, '._-');
            if ($safeName === '') {
                $safeName = 'attachment';
            }

            $storedName = 'chat_' . time() . '_' . bin2hex(random_bytes(6)) . '_' . $safeName . '.' . $extension;
            $targetPath = $baseDir . $storedName;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                $result['errors'][] = 'Failed to save attachment';
                return $result;
            }

            chmod($targetPath, 0644);

            $relativePath = 'uploads/chat_attachments/' . $userId . '/' . $storedName;
            $result['success'] = true;
            $result['attachment'] = [
                'url' => URLROOT . '/' . $relativePath,
                'path' => $relativePath,
                'name' => $originalName,
                'type' => $mimeType,
                'size' => (int)($file['size'] ?? 0)
            ];
            return $result;
        } catch (Exception $e) {
            $result['errors'][] = 'Upload error';
            return $result;
        }
    }

    private function decodeMessagePayload($message) {
        if (is_array($message)) {
            return [
                'text' => (string)($message['text'] ?? ''),
                'attachment' => $message['attachment'] ?? null
            ];
        }

        if (!is_string($message) || $message === '') {
            return ['text' => '', 'attachment' => null];
        }

        $decoded = json_decode($message, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return [
                'text' => (string)($decoded['text'] ?? ''),
                'attachment' => $decoded['attachment'] ?? null
            ];
        }

        return ['text' => $message, 'attachment' => null];
    }

    private function buildMessagePayload($messageText, $attachment = null) {
        $messageText = trim((string)$messageText);

        if (!empty($attachment)) {
            return json_encode([
                'text' => $messageText,
                'attachment' => $attachment
            ], JSON_UNESCAPED_SLASHES);
        }

        return $messageText;
    }

    private function deleteAttachmentFile($attachment = null) {
        if (!is_array($attachment)) {
            return;
        }

        $relativePath = $attachment['path'] ?? '';
        if ($relativePath === '' && !empty($attachment['url'])) {
            $urlPath = parse_url($attachment['url'], PHP_URL_PATH);
            if (is_string($urlPath)) {
                $relativePath = ltrim($urlPath, '/');
            }
        }

        if ($relativePath === '' || strpos($relativePath, 'uploads/chat_attachments/') !== 0) {
            return;
        }

        $absolutePath = dirname(APPROOT) . '/public/' . $relativePath;
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    public function submitUserReport() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $role = strtolower((string)($_SESSION['user_role'] ?? ''));
        if (!in_array($role, ['doctor', 'patient'], true)) {
            echo json_encode(['success' => false, 'message' => 'Only doctors and patients can submit reports.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $input = [];
        }

        $conversationId = (int)($_POST['conversation_id'] ?? ($input['conversation_id'] ?? 0));
        $reportedUserId = (int)($_POST['reported_user_id'] ?? ($input['reported_user_id'] ?? 0));
        $reason = trim((string)($_POST['reason'] ?? ($input['reason'] ?? '')));
        $description = trim((string)($_POST['description'] ?? ($input['description'] ?? '')));
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);

        if ($conversationId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid conversation.']);
            return;
        }

        if ($reason === '') {
            echo json_encode(['success' => false, 'message' => 'Reason is required.']);
            return;
        }

        if (!$this->messageModel->hasAccessToConversation($conversationId, $currentUserId)) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $conversation = $this->messageModel->getConversationById($conversationId);
        if (!$conversation) {
            echo json_encode(['success' => false, 'message' => 'Conversation not found.']);
            return;
        }

        $counterpartId = 0;
        if ((int)$conversation->user1_id === $currentUserId) {
            $counterpartId = (int)$conversation->user2_id;
        } elseif ((int)$conversation->user2_id === $currentUserId) {
            $counterpartId = (int)$conversation->user1_id;
        }

        if ($counterpartId <= 0 || $counterpartId === $currentUserId) {
            echo json_encode(['success' => false, 'message' => 'Invalid participant selected for report.']);
            return;
        }

        if ($reportedUserId > 0 && $reportedUserId !== $counterpartId) {
            echo json_encode(['success' => false, 'message' => 'Invalid participant selected for report.']);
            return;
        }

        $counterpartUser = $this->userModel->getUserById($counterpartId);
        if (!$counterpartUser) {
            echo json_encode(['success' => false, 'message' => 'Reported user not found.']);
            return;
        }

        $counterpartRole = strtolower((string)($counterpartUser->user_role ?? ''));
        if (!in_array($counterpartRole, ['doctor', 'patient'], true)) {
            echo json_encode(['success' => false, 'message' => 'Only doctor or patient users can be reported from messages.']);
            return;
        }

        $allMessages = $this->messageModel->getMessages($conversationId);
        $latestMessage = !empty($allMessages) ? end($allMessages) : null;
        $latestPayload = $latestMessage ? $this->decodeMessagePayload($latestMessage->message ?? '') : ['text' => '', 'attachment' => null];

        $latestSnippet = trim((string)($latestPayload['text'] ?? ''));
        if ($latestSnippet === '') {
            $latestSnippet = !empty($latestPayload['attachment']) ? '(Attachment only)' : '(No message text)';
        }
        if (strlen($latestSnippet) > 180) {
            $latestSnippet = substr($latestSnippet, 0, 180) . '...';
        }

        $details = 'Context: Conversation #' . $conversationId
            . '. Reported user ID: ' . $counterpartId
            . '. Latest message: ' . $latestSnippet;

        if ($description !== '') {
            $details .= ' | Reporter note: ' . $description;
        }

        $saved = $this->appointmentModel->createReport([
            'reporter_type' => ucfirst($role),
            'reporter_id' => $currentUserId,
            'reported_type' => ucfirst($counterpartRole),
            'reported_id' => $counterpartId,
            'report_type' => 'User Report',
            'reason' => $reason,
            'description' => $details,
            'status' => 'pending',
        ]);

        if (!$saved) {
            echo json_encode(['success' => false, 'message' => 'Could not submit report right now.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Report submitted successfully.']);
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
        if (!is_array($input)) {
            $input = [];
        }
        $recipientId = $input['recipient_id'] ?? null;
        $userId = $_SESSION['user_id'];

        $accountStatus = $this->getCurrentMessagingStatus();
        if ($accountStatus === 'inactive') {
            echo json_encode(['success' => false, 'message' => 'Your account is deactivated. Please contact admin.']);
            return;
        }

        if ($accountStatus === 'suspended') {
            echo json_encode(['success' => false, 'message' => 'Your account is suspended. You cannot start new chats.']);
            return;
        }
        
        if (!$recipientId) {
            echo json_encode(['success' => false, 'message' => 'Missing recipient ID']);
            return;
        }
        
        // Validation: Cannot message yourself
        if ($userId == $recipientId) {
            echo json_encode(['success' => false, 'message' => 'Cannot message yourself']);
            return;
        }
        
        try {
            // Security: Verify role-based authorization before creating conversation
            if (!$this->canUsersMessage($userId, $recipientId)) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized - users cannot message each other']);
                return;
            }
            
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
        $userRole = $_SESSION['user_role'] ?? '';
        
        try {
            // Restrict search to users this person can message.
            $users = $this->messageModel->getEligibleContacts($userId, $userRole, trim($searchTerm));
            $this->attachProfileImagesToRows($users, 'user_id');
            
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

    // Get contacts the current user is allowed to start chatting with
    public function getEligibleContacts() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'] ?? '';
        $searchTerm = trim($_GET['search'] ?? '');

        $accountStatus = $this->getCurrentMessagingStatus();
        if (in_array($accountStatus, ['inactive', 'suspended'], true)) {
            echo json_encode([
                'success' => true,
                'contacts' => []
            ]);
            return;
        }

        try {
            $contacts = $this->messageModel->getEligibleContacts($userId, $userRole, $searchTerm);
            $this->attachProfileImagesToRows($contacts, 'user_id');

            echo json_encode([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error loading eligible contacts'
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
        if (!is_array($input)) {
            $input = [];
        }
        $messageId = $input['message_id'] ?? null;
        $userId = $_SESSION['user_id'];

        $accountStatus = $this->getCurrentMessagingStatus();
        if ($accountStatus === 'inactive') {
            echo json_encode(['success' => false, 'message' => 'Your account is deactivated. Please contact admin.']);
            return;
        }

        if ($accountStatus === 'suspended') {
            echo json_encode(['success' => false, 'message' => 'Your account is suspended. You cannot delete messages.']);
            return;
        }
        
        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Missing message ID']);
            return;
        }
        
        try {
            $existingMessage = $this->messageModel->getMessageById($messageId);
            if (!$existingMessage) {
                echo json_encode(['success' => false, 'message' => 'Message not found']);
                return;
            }

            if ((int)$existingMessage->sender_id !== (int)$userId) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            if (!$this->messageModel->hasAccessToConversation($existingMessage->conversation_id, $userId)) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                return;
            }

            $parsedMessage = $this->decodeMessagePayload($existingMessage->message);
            $this->deleteAttachmentFile($parsedMessage['attachment'] ?? null);

            $result = $this->messageModel->deleteMessage($messageId, $userId);
            if ($result) {
                $this->messageModel->refreshConversationLastMessageTime($existingMessage->conversation_id);
            }
            
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
    
    // Verify messaging authorization based on user roles
    // Doctor ↔ Patient, Doctor ↔ Admin, Admin ↔ Patient allowed
    private function verifyMessagingAuthorization($conversationId, $currentUserId) {
        // Get conversation info
        $conversation = $this->messageModel->getConversationById($conversationId);
        
        if (!$conversation) {
            return false;
        }
        
        // Get both users' roles
        $user1 = $this->userModel->getUserById($conversation->user1_id);
        $user2 = $this->userModel->getUserById($conversation->user2_id);
        
        if (!$user1 || !$user2) {
            return false;
        }
        
        return $this->canUsersMessage((int)$conversation->user1_id, (int)$conversation->user2_id);
    }
    
    // Check if two users can message each other based on their roles
    private function canUsersMessage($userId1, $userId2) {
        $user1 = $this->userModel->getUserById($userId1);
        $user2 = $this->userModel->getUserById($userId2);
        
        if (!$user1 || !$user2) {
            return false;
        }

        $role1 = strtolower($user1->user_role ?? '');
        $role2 = strtolower($user2->user_role ?? '');

        // Doctor/patient chat is allowed only if they already have approved/completed appointment history.
        $isDoctorPatientPair =
            ($role1 === 'doctor' && $role2 === 'patient') ||
            ($role1 === 'patient' && $role2 === 'doctor');

        if ($isDoctorPatientPair) {
            return $this->messageModel->hasAppointmentHistoryBetweenUsers($userId1, $userId2);
        }

        // Keep admin flows available as before.
        $isAdminDoctorPair =
            ($role1 === 'admin' && $role2 === 'doctor') ||
            ($role1 === 'doctor' && $role2 === 'admin');

        if ($isAdminDoctorPair) {
            return true;
        }

        $isAdminPatientPair =
            ($role1 === 'admin' && $role2 === 'patient') ||
            ($role1 === 'patient' && $role2 === 'admin');

        if ($isAdminPatientPair) {
            return true;
        }
        
        return false;
    }

    private function buildProfileImageUrl($profileImagePath) {
        $path = trim((string)$profileImagePath);
        if ($path === '') {
            return '';
        }

        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path);
        $path = ltrim($path, '/');

        return $path !== '' ? URLROOT . '/' . $path : '';
    }

    private function attachProfileImagesToRows(&$rows, $idField = 'user_id') {
        if (!is_array($rows)) {
            return;
        }

        foreach ($rows as &$row) {
            if (!is_object($row) && !is_array($row)) {
                continue;
            }

            $userId = null;
            if (is_object($row)) {
                $userId = $row->{$idField} ?? null;
            } elseif (is_array($row)) {
                $userId = $row[$idField] ?? null;
            }

            $userId = (int)$userId;
            if ($userId <= 0) {
                continue;
            }

            $profileImagePath = $this->userModel->getUserProfileImage($userId);
            $profileImageUrl = $this->buildProfileImageUrl($profileImagePath);

            if (is_object($row)) {
                $row->profile_image = $profileImagePath;
                $row->profile_image_url = $profileImageUrl;
            } else {
                $row['profile_image'] = $profileImagePath;
                $row['profile_image_url'] = $profileImageUrl;
            }
        }
        unset($row);
    }
    
    // Index method - View messages page
    public function index() {
        $userRole = $_SESSION['user_role'] ?? 'patient';
        
        // Route to appropriate view based on role
        switch (strtolower($userRole)) {
            case 'admin':
                $this->view('pages/messages/v_admin_messages');
                break;
            case 'doctor':
                $this->view('pages/messages/v_doctor_messages');
                break;
            case 'patient':
            default:
                $this->view('pages/messages/v_patient_messages');
                break;
        }
    }
}

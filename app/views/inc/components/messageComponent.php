<!-- Reusable WhatsApp-Style Message Component -->
<div class="message-container">
    <!-- Left Sidebar - Conversations List -->
    <div class="chat-conversations-sidebar">
        <!-- Search Bar -->
        <div class="conversations-header">
            <h2 class="conversations-title">Chats</h2>
            <div class="search-container">
                <input type="text" id="searchConversations" class="search-input" placeholder="Search or start new chat">
                <span class="search-icon"></span>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="conversations-list" id="conversationsList">
            <!-- Dynamic conversations will be loaded here -->
        </div>
    </div>

    <!-- Right Panel - Chat Area -->
    <div class="chat-area">
        <!-- Empty State -->
        <div class="chat-empty-state" id="chatEmptyState">
            <div class="empty-state-content">
                <h3>MEDILINK Messages</h3>
                <p>Send and receive messages securely</p>
                <small>Select a conversation to start messaging</small>
            </div>
        </div>

        <!-- Active Chat Interface -->
        <div class="chat-interface" id="chatInterface" style="display: none;">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-header-left">
                    <div class="back-button" onclick="closeChatMobile()">
                        <span>←</span>
                    </div>
                    <div class="chat-user-avatar" id="chatUserAvatar">
                        <span id="avatarInitials">U</span>
                    </div>
                    <div class="chat-user-info">
                        <h3 class="chat-user-name" id="chatUserName">User Name</h3>
                    </div>
                </div>
            </div>

            <!-- Messages Display Area -->
            <div class="messages-list messages-display" id="messagesDisplay">
                <!-- Messages will be dynamically loaded here -->
            </div>

            <!-- Message Input Area -->
            <div class="message-input-area">
                <input type="file" id="chatAttachmentInput" class="chat-attachment-input" accept="image/jpeg,image/png,application/pdf,.jpg,.jpeg,.png,.pdf" hidden>
                <button class="attach-button" id="attachButton" type="button" title="Attach file">Attach</button>
                <div class="input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="message-input" 
                        placeholder="Type a message"
                        rows="1"></textarea>
                </div>
                <button class="send-button" id="sendButton" type="button">
                    <span class="send-icon">Send</span>
                </button>
            </div>
            <div class="attachment-preview" id="attachmentPreview" style="display:none;">
                <div class="attachment-preview-info">
                    <span class="attachment-preview-icon">📎</span>
                    <span class="attachment-preview-name" id="attachmentPreviewName"></span>
                </div>
                <button type="button" class="attachment-preview-remove" id="removeAttachmentBtn">×</button>
            </div>
        </div>
    </div>
</div>

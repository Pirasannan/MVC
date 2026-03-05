<!-- Reusable WhatsApp-Style Message Component -->
<div class="message-container">
    <!-- Left Sidebar - Conversations List -->
    <div class="conversations-sidebar">
        <!-- Search Bar -->
        <div class="conversations-header">
            <h2 class="conversations-title">Chats</h2>
            <div class="search-container">
                <input type="text" id="searchConversations" class="search-input" placeholder="Search or start new chat">
                <span class="search-icon">🔍</span>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="conversations-list" id="conversationsList">
            <!-- Dynamic conversations will be loaded here -->
            <div class="no-conversations">
                <div class="no-conversations-icon">💬</div>
                <p>No conversations yet</p>
                <small>Start a new conversation</small>
            </div>
        </div>
    </div>

    <!-- Right Panel - Chat Area -->
    <div class="chat-area">
        <!-- Empty State -->
        <div class="chat-empty-state" id="chatEmptyState">
            <div class="empty-state-content">
                <div class="empty-state-icon">💬</div>
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
                        <span class="chat-user-status" id="chatUserStatus">
                            <span class="status-dot"></span>
                            <span id="statusText">Online</span>
                        </span>
                    </div>
                </div>
                <div class="chat-header-right">
                    <button class="icon-button" title="Search">🔍</button>
                    <button class="icon-button" title="More options">⋮</button>
                </div>
            </div>

            <!-- Messages Display Area -->
            <div class="messages-display" id="messagesDisplay">
                <!-- Messages will be dynamically loaded here -->
            </div>

            <!-- Message Input Area -->
            <div class="message-input-area">
                <button class="attach-button" title="Attach file">📎</button>
                <div class="input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="message-input" 
                        placeholder="Type a message"
                        rows="1"></textarea>
                    <button class="emoji-button" title="Emoji">😊</button>
                </div>
                <button class="send-button" id="sendButton" onclick="sendMessage()">
                    <span class="send-icon">➤</span>
                </button>
            </div>
        </div>
    </div>
</div>

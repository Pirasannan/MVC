// WhatsApp-Style Messaging JavaScript

// Global variables
let currentUserId = null;
let currentUserType = null;
let selectedConversationId = null;
let messagePollingInterval = null;

// Initialize messaging when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMessaging();
});

// Initialize messaging system
function initializeMessaging() {
    // Get user info from page data
    const userIdElement = document.querySelector('[data-user-id]');
    const userTypeElement = document.querySelector('[data-user-type]');
    
    if (userIdElement) currentUserId = userIdElement.dataset.userId;
    if (userTypeElement) currentUserType = userTypeElement.dataset.userType;

    // Setup event listeners
    setupEventListeners();
    
    // Load conversations
    loadConversations();
    
    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', autoResizeTextarea);
        messageInput.addEventListener('keypress', handleEnterKey);
    }
}

// Setup event listeners
function setupEventListeners() {
    // Search conversations
    const searchInput = document.getElementById('searchConversations');
    if (searchInput) {
        searchInput.addEventListener('input', searchConversations);
    }

    // Send button click
    const sendButton = document.getElementById('sendButton');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }
}

// Load conversations list
async function loadConversations() {
    try {
        const response = await fetch(`${URLROOT}/Messages/getConversations`);
        const data = await response.json();
        
        if (data.success) {
            displayConversations(data.conversations);
        } else {
            showNoConversations();
        }
    } catch (error) {
        console.error('Error loading conversations:', error);
        showNoConversations();
    }
}

// Display conversations in sidebar
function displayConversations(conversations) {
    const conversationsList = document.getElementById('conversationsList');
    
    if (!conversations || conversations.length === 0) {
        showNoConversations();
        return;
    }

    conversationsList.innerHTML = '';
    
    conversations.forEach(conv => {
        const conversationItem = createConversationItem(conv);
        conversationsList.appendChild(conversationItem);
    });
}

// Create conversation item element
function createConversationItem(conv) {
    const div = document.createElement('div');
    div.className = `conversation-item ${conv.unread_count > 0 ? 'unread' : ''}`;
    div.dataset.conversationId = conv.conversation_id;
    div.dataset.userId = conv.user_id;
    div.onclick = () => selectConversation(conv.conversation_id, conv.user_id);
    
    const initials = conv.user_name ? conv.user_name.substring(0, 2).toUpperCase() : 'U';
    const lastMessagePreview = conv.last_message ? truncateText(conv.last_message, 40) : 'No messages yet';
    const timeAgo = conv.last_message_time ? formatTimeAgo(conv.last_message_time) : '';
    
    div.innerHTML = `
        <div class="conversation-avatar">
            <span>${initials}</span>
        </div>
        <div class="conversation-info">
            <div class="conversation-header">
                <h3 class="conversation-name">${escapeHtml(conv.user_name || 'Unknown User')}</h3>
                <span class="conversation-time">${timeAgo}</span>
            </div>
            <div class="conversation-preview">
                ${conv.is_sent ? '<span class="message-status">✓</span>' : ''}
                <span class="conversation-message">${escapeHtml(lastMessagePreview)}</span>
                ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}
            </div>
        </div>
    `;
    
    return div;
}

// Show no conversations state
function showNoConversations() {
    const conversationsList = document.getElementById('conversationsList');
    conversationsList.innerHTML = `
        <div class="no-conversations">
            <div class="no-conversations-icon">💬</div>
            <p>No conversations yet</p>
            <small>Start a new conversation</small>
        </div>
    `;
}

// Select a conversation
async function selectConversation(conversationId, userId) {
    selectedConversationId = conversationId;
    
    // Update active state
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const selectedItem = document.querySelector(`[data-conversation-id="${conversationId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('active');
    }
    
    // Show chat interface
    document.getElementById('chatEmptyState').style.display = 'none';
    document.getElementById('chatInterface').style.display = 'flex';
    
    // Load user info and messages
    await loadChatUser(userId);
    await loadMessages(conversationId);
    
    // Start polling for new messages
    startMessagePolling();
    
    // Mark as read
    markConversationAsRead(conversationId);
    
    // Mobile: show chat area
    document.querySelector('.chat-area').classList.add('active');
}

// Load chat user information
async function loadChatUser(userId) {
    try {
        const response = await fetch(`${URLROOT}/Messages/getUserInfo/${userId}`);
        const data = await response.json();
        
        if (data.success) {
            const user = data.user;
            const initials = user.name ? user.name.substring(0, 2).toUpperCase() : 'U';
            
            document.getElementById('avatarInitials').textContent = initials;
            document.getElementById('chatUserName').textContent = user.name || 'Unknown User';
            
            // Update online status
            const statusElement = document.getElementById('chatUserStatus');
            if (user.is_online) {
                statusElement.classList.remove('offline');
                document.getElementById('statusText').textContent = 'Online';
            } else {
                statusElement.classList.add('offline');
                document.getElementById('statusText').textContent = 'Offline';
            }
        }
    } catch (error) {
        console.error('Error loading user info:', error);
    }
}

// Load messages for conversation
async function loadMessages(conversationId) {
    try {
        const response = await fetch(`${URLROOT}/Messages/getMessages/${conversationId}`);
        const data = await response.json();
        
        if (data.success) {
            displayMessages(data.messages);
        } else {
            showEmptyMessages();
        }
    } catch (error) {
        console.error('Error loading messages:', error);
        showEmptyMessages();
    }
}

// Display messages
function displayMessages(messages) {
    const messagesDisplay = document.getElementById('messagesDisplay');
    messagesDisplay.innerHTML = '';
    
    if (!messages || messages.length === 0) {
        showEmptyMessages();
        return;
    }
    
    let lastDate = null;
    
    messages.forEach(message => {
        // Add date separator if date changed
        const messageDate = new Date(message.created_at).toDateString();
        if (messageDate !== lastDate) {
            const dateSeparator = createDateSeparator(message.created_at);
            messagesDisplay.appendChild(dateSeparator);
            lastDate = messageDate;
        }
        
        // Add message bubble
        const messageBubble = createMessageBubble(message);
        messagesDisplay.appendChild(messageBubble);
    });
    
    // Scroll to bottom
    scrollToBottom();
}

// Create date separator
function createDateSeparator(dateString) {
    const div = document.createElement('div');
    div.className = 'date-separator';
    div.innerHTML = `<span class="date-label">${formatDate(dateString)}</span>`;
    return div;
}

// Create message bubble
function createMessageBubble(message) {
    const div = document.createElement('div');
    const isSent = message.sender_id == currentUserId;
    div.className = `message-bubble ${isSent ? 'sent' : 'received'}`;
    
    const time = formatTime(message.created_at);
    const statusIcon = isSent ? (message.is_read ? '✓✓' : '✓') : '';
    
    div.innerHTML = `
        <div class="message-content">
            <p class="message-text">${escapeHtml(message.message)}</p>
            <div class="message-meta">
                <span class="message-time">${time}</span>
                ${statusIcon ? `<span class="message-status-icon">${statusIcon}</span>` : ''}
            </div>
        </div>
    `;
    
    return div;
}

// Show empty messages state
function showEmptyMessages() {
    const messagesDisplay = document.getElementById('messagesDisplay');
    messagesDisplay.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #8696a0; text-align: center;">
            <div>
                <div style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;">📭</div>
                <p style="margin: 0; font-size: 14px;">No messages yet</p>
                <small style="font-size: 12px;">Send a message to start the conversation</small>
            </div>
        </div>
    `;
}

// Send message
async function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const messageText = messageInput.value.trim();
    
    if (!messageText || !selectedConversationId) {
        return;
    }
    
    try {
        const response = await fetch(`${URLROOT}/Messages/sendMessage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                conversation_id: selectedConversationId,
                message: messageText
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Clear input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // Add message to display
            addMessageToDisplay({
                sender_id: currentUserId,
                message: messageText,
                created_at: new Date().toISOString(),
                is_read: false
            });
            
            // Refresh conversations list
            loadConversations();
        } else {
            alert('Failed to send message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Error sending message');
    }
}

// Add message to display
function addMessageToDisplay(message) {
    const messagesDisplay = document.getElementById('messagesDisplay');
    
    // Remove empty state if exists
    const emptyState = messagesDisplay.querySelector('[style*="No messages yet"]');
    if (emptyState) {
        messagesDisplay.innerHTML = '';
    }
    
    const messageBubble = createMessageBubble(message);
    messagesDisplay.appendChild(messageBubble);
    scrollToBottom();
}

// Start polling for new messages
function startMessagePolling() {
    // Clear existing interval
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
    }
    
    // Poll every 3 seconds
    messagePollingInterval = setInterval(() => {
        if (selectedConversationId) {
            checkForNewMessages();
        }
    }, 3000);
}

// Check for new messages
async function checkForNewMessages() {
    if (!selectedConversationId) return;
    
    try {
        const response = await fetch(`${URLROOT}/Messages/getNewMessages/${selectedConversationId}`);
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addMessageToDisplay(message);
            });
            
            // Mark as read
            markConversationAsRead(selectedConversationId);
        }
    } catch (error) {
        console.error('Error checking for new messages:', error);
    }
}

// Mark conversation as read
async function markConversationAsRead(conversationId) {
    try {
        await fetch(`${URLROOT}/Messages/markAsRead/${conversationId}`, {
            method: 'POST'
        });
        
        // Update UI to remove unread badge
        const conversationItem = document.querySelector(`[data-conversation-id="${conversationId}"]`);
        if (conversationItem) {
            conversationItem.classList.remove('unread');
            const badge = conversationItem.querySelector('.unread-badge');
            if (badge) badge.remove();
        }
    } catch (error) {
        console.error('Error marking as read:', error);
    }
}

// Search conversations
function searchConversations(event) {
    const searchTerm = event.target.value.toLowerCase();
    const conversationItems = document.querySelectorAll('.conversation-item');
    
    conversationItems.forEach(item => {
        const name = item.querySelector('.conversation-name').textContent.toLowerCase();
        const message = item.querySelector('.conversation-message').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || message.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Auto resize textarea
function autoResizeTextarea(event) {
    const textarea = event.target;
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
}

// Handle Enter key
function handleEnterKey(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

// Close chat on mobile
function closeChatMobile() {
    document.querySelector('.chat-area').classList.remove('active');
}

// Scroll to bottom of messages
function scrollToBottom() {
    const messagesDisplay = document.getElementById('messagesDisplay');
    messagesDisplay.scrollTop = messagesDisplay.scrollHeight;
}

// Utility functions
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays}d ago`;
    
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    if (date.toDateString() === today.toDateString()) {
        return 'Today';
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
    } else {
        return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    }
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
    }
});

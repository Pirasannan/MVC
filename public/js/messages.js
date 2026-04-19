// WhatsApp-Style Messaging JavaScript

// Global variables
let currentUserId = null;
let currentUserType = null;
let selectedConversationId = null;
let messagePollingInterval = null;
let conversationPollingInterval = null;
let lastMessageId = 0; // Track the last message ID for polling
let eligibleContacts = [];
let conversationsCache = [];
let sidebarSearchTerm = '';
let selectedAttachmentFile = null;
let messagingDisabled = false;
let selectedConversationUserId = null;
let selectedConversationUserName = '';
let selectedConversationUserRole = '';

const MESSAGE_USER_REPORT_REASONS = [
    'Abuse or harassment',
    'Fraud or scam',
    'No-show / missed appointment',
    'Inappropriate behavior',
    'Fake profile'
];

// Suppress the noisy failed-send popup while keeping other alerts unchanged.
if (typeof window !== 'undefined' && !window.__messagesAlertPatched) {
    window.__messagesAlertPatched = true;
    const originalAlert = window.alert;
    window.alert = function(message) {
        const text = String(message || '').toLowerCase().trim();
        if (text === 'failed to send message' || text.includes('failed to send message')) {
            console.warn('Suppressed alert:', message);
            return;
        }
        return originalAlert.call(window, message);
    };
}

// Initialize messaging when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMessaging();
});

window.addEventListener('focus', refreshActiveConversation);
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        refreshActiveConversation();
    }
});

// Initialize messaging system
function initializeMessaging() {
    // Get user info from page data
    const userIdElement = document.querySelector('[data-user-id]');
    const userTypeElement = document.querySelector('[data-user-type]');
    const messageContainer = document.querySelector('.message-container');
    const appConfig = window.APP_CONFIG || {};
    
    if (userIdElement && userIdElement.dataset.userId) {
        currentUserId = String(userIdElement.dataset.userId);
    } else if (appConfig.userId !== undefined && appConfig.userId !== null) {
        currentUserId = String(appConfig.userId);
    }

    if (userTypeElement && userTypeElement.dataset.userType) {
        currentUserType = String(userTypeElement.dataset.userType);
    } else if (appConfig.userRole) {
        currentUserType = String(appConfig.userRole);
    }

    messagingDisabled = !!(messageContainer && messageContainer.dataset && messageContainer.dataset.messagingDisabled === '1');

    // Setup event listeners
    setupEventListeners();
    
    // Load conversations
    loadConversations();
    loadEligibleContacts();
    startConversationPolling();
    
    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', autoResizeTextarea);
        messageInput.addEventListener('keypress', handleEnterKey);
    }

    setupAttachmentHandlers();
    setupReportHandlers();
    updateReportButtonState();
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

    const messagesDisplay = document.getElementById('messagesDisplay');
    if (messagesDisplay) {
        messagesDisplay.addEventListener('click', (event) => {
            // Keep action buttons interactive without toggling the bubble state.
            if (event.target.closest('.message-action-btn')) {
                return;
            }

            const clickedBubble = event.target.closest('.message-bubble.sent');
            if (!clickedBubble || !messagesDisplay.contains(clickedBubble)) {
                closeAllMessageActionMenus();
                return;
            }

            const hasActions = !!clickedBubble.querySelector('.message-actions');
            const wasOpen = clickedBubble.classList.contains('show-actions');

            closeAllMessageActionMenus();

            if (hasActions && !wasOpen) {
                clickedBubble.classList.add('show-actions');
            }
        });
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('#messagesDisplay')) {
            return;
        }
        closeAllMessageActionMenus();
    });
}

function closeAllMessageActionMenus() {
    document.querySelectorAll('.message-bubble.show-actions').forEach((bubble) => {
        bubble.classList.remove('show-actions');
    });
}

// Setup attachment chooser and preview handlers
function setupAttachmentHandlers() {
    const attachButton = document.getElementById('attachButton');
    const attachmentInput = document.getElementById('chatAttachmentInput');
    const removeAttachmentBtn = document.getElementById('removeAttachmentBtn');

    if (attachButton && attachmentInput) {
        attachButton.addEventListener('click', () => attachmentInput.click());
        attachmentInput.addEventListener('change', handleAttachmentSelection);
    }

    if (removeAttachmentBtn) {
        removeAttachmentBtn.addEventListener('click', clearSelectedAttachment);
    }
}

function handleAttachmentSelection(event) {
    const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
    selectedAttachmentFile = file;
    renderAttachmentPreview(file);
}

function renderAttachmentPreview(file) {
    const preview = document.getElementById('attachmentPreview');
    const previewName = document.getElementById('attachmentPreviewName');

    if (!preview || !previewName) return;

    if (!file) {
        preview.style.display = 'none';
        previewName.textContent = '';
        return;
    }

    preview.style.display = 'flex';
    previewName.textContent = file.name;
}

function clearSelectedAttachment() {
    selectedAttachmentFile = null;
    const attachmentInput = document.getElementById('chatAttachmentInput');
    if (attachmentInput) attachmentInput.value = '';
    renderAttachmentPreview(null);
}

// Load conversations list
async function loadConversations() {
    try {
        const response = await fetch(`${URLROOT}/Messages/getConversations`);
        const data = await response.json();
        
        if (data.success) {
            conversationsCache = data.conversations || [];
            renderSidebarList();
        } else {
            conversationsCache = [];
            showNoConversations();
        }
    } catch (error) {
        console.error('Error loading conversations:', error);
        conversationsCache = [];
        showNoConversations();
    }
}

// Load contacts user is allowed to start chatting with
async function loadEligibleContacts(searchTerm = '') {
    try {
        const query = searchTerm ? `?search=${encodeURIComponent(searchTerm)}` : '';
        const response = await fetch(`${URLROOT}/Messages/getEligibleContacts${query}`);
        const data = await response.json();

        if (data.success) {
            eligibleContacts = data.contacts || [];
            renderSidebarList();
        } else {
            eligibleContacts = [];
            renderSidebarList();
        }
    } catch (error) {
        console.error('Error loading eligible contacts:', error);
        eligibleContacts = [];
        renderSidebarList();
    }
}

function renderSidebarList(searchTerm = sidebarSearchTerm) {
    const conversationsList = document.getElementById('conversationsList');
    const conversationsSidebar = document.querySelector('.chat-conversations-sidebar');

    if (!conversationsList) {
        return;
    }

    sidebarSearchTerm = (searchTerm || '').toLowerCase().trim();

    const conversationUserIds = new Set(
        (conversationsCache || []).map(conv => String(conv.user_id))
    );

    const filteredContacts = (eligibleContacts || []).filter(contact => {
        if (conversationUserIds.has(String(contact.user_id))) {
            return false;
        }

        if (!sidebarSearchTerm) {
            return true;
        }

        const name = (contact.user_name || '').toLowerCase();
        const email = (contact.email || '').toLowerCase();
        const role = (contact.user_role || '').toLowerCase();
        return name.includes(sidebarSearchTerm) || email.includes(sidebarSearchTerm) || role.includes(sidebarSearchTerm);
    });

    const filteredConversations = (conversationsCache || []).filter(conv => {
        if (!sidebarSearchTerm) {
            return true;
        }

        const name = (conv.user_name || '').toLowerCase();
        const message = (formatMessagePreview(conv.last_message) || '').toLowerCase();
        return name.includes(sidebarSearchTerm) || message.includes(sidebarSearchTerm);
    });

    conversationsList.innerHTML = '';

    filteredContacts.forEach(contact => {
        const item = createEligibleContactItem(contact);
        conversationsList.appendChild(item);
    });

    filteredConversations.forEach(conv => {
        const conversationItem = createConversationItem(conv);
        conversationsList.appendChild(conversationItem);

        if (String(selectedConversationId) === String(conv.conversation_id)) {
            conversationItem.classList.add('active');
        }
    });

    if (conversationsSidebar) {
        if (filteredContacts.length === 0 && filteredConversations.length === 0) {
            conversationsSidebar.classList.add('empty-conversations');
        } else {
            conversationsSidebar.classList.remove('empty-conversations');
        }
    }
}

function createEligibleContactItem(contact) {
    const div = document.createElement('div');
    div.className = 'eligible-contact-item';

    const initials = contact.user_name ? contact.user_name.substring(0, 2).toUpperCase() : 'U';
    const role = (contact.user_role || '').toString();

    div.innerHTML = `
        <div class="eligible-contact-main">
            <div class="eligible-contact-avatar"><span>${initials}</span></div>
            <div class="eligible-contact-info">
                <div class="eligible-contact-name">${escapeHtml(contact.user_name || 'Unknown User')}</div>
                <div class="eligible-contact-role">${escapeHtml(role)}</div>
            </div>
        </div>
        <button class="start-chat-btn" type="button">Chat</button>
    `;

    const button = div.querySelector('.start-chat-btn');
    button.addEventListener('click', (event) => {
        event.stopPropagation();
        if (messagingDisabled) {
            alert('Your account is suspended or deactivated. Messaging is disabled.');
            return;
        }
        startChatWithUser(contact.user_id);
    });

    div.addEventListener('click', () => {
        if (messagingDisabled) {
            alert('Your account is suspended or deactivated. Messaging is disabled.');
            return;
        }
        startChatWithUser(contact.user_id);
    });
    return div;
}

// Display conversations in sidebar
function displayConversations(conversations) {
    conversationsCache = conversations || [];
    renderSidebarList();
}

// Create conversation item element
function createConversationItem(conv) {
    const div = document.createElement('div');
    div.className = `conversation-item ${conv.unread_count > 0 ? 'unread' : ''}`;
    div.dataset.conversationId = conv.conversation_id;
    div.dataset.userId = conv.user_id;
    div.dataset.userName = conv.user_name || '';
    div.onclick = () => selectConversation(conv.conversation_id, conv.user_id, {
        name: conv.user_name || '',
        role: conv.user_role || ''
    });
    
    const initials = conv.user_name ? conv.user_name.substring(0, 2).toUpperCase() : 'U';
    const lastMessagePreview = formatMessagePreview(conv.last_message);
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
                <span class="conversation-message">${escapeHtml(lastMessagePreview)}</span>
                ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}
            </div>
        </div>
    `;
    
    return div;
}

function formatMessagePreview(rawMessage) {
    if (!rawMessage) return 'No messages yet';

    const parsed = parseMessagePayload(rawMessage);
    if (parsed.attachment && !parsed.text) {
        return `[Attachment] ${parsed.attachment.name || 'file'}`;
    }

    if (parsed.attachment && parsed.text) {
        return truncateText(parsed.text, 40);
    }

    return truncateText(parsed.text || rawMessage, 40);
}

// Show no conversations state
function showNoConversations() {
    const conversationsList = document.getElementById('conversationsList');
    const conversationsSidebar = document.querySelector('.chat-conversations-sidebar');

    if (conversationsSidebar) {
        conversationsSidebar.classList.add('empty-conversations');
    }

    conversationsCache = [];
    conversationsList.innerHTML = '';
}

// Select a conversation
async function selectConversation(conversationId, userId, previewUser = null) {
    selectedConversationId = conversationId;
    lastMessageId = 0;
    selectedConversationUserId = userId ? String(userId) : null;
    selectedConversationUserName = (previewUser && previewUser.name) ? previewUser.name : '';
    selectedConversationUserRole = (previewUser && previewUser.role) ? String(previewUser.role) : '';
    updateReportButtonState();
    
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

    // Show known chat user details immediately while API data loads.
    if (previewUser && previewUser.name) {
        applyChatHeaderUser(previewUser);
    }
    
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
            applyChatHeaderUser(data.user || null);
        }
    } catch (error) {
        console.error('Error loading user info:', error);
    }
}

function applyChatHeaderUser(user) {
    const safeUser = user || {};
    const userName = safeUser.name || safeUser.user_name || 'Unknown User';
    const initials = userName.substring(0, 2).toUpperCase();

    const avatarEl = document.getElementById('avatarInitials');
    const nameEl = document.getElementById('chatUserName');

    if (avatarEl) avatarEl.textContent = initials;
    if (nameEl) nameEl.textContent = userName;

    selectedConversationUserId = safeUser.id !== undefined && safeUser.id !== null
        ? String(safeUser.id)
        : selectedConversationUserId;
    selectedConversationUserName = userName;
    selectedConversationUserRole = String(safeUser.type || safeUser.user_role || selectedConversationUserRole || '');
    updateReportButtonState();
}

function setupReportHandlers() {
    const reportBtn = document.getElementById('reportUserBtn');
    const modal = document.getElementById('messageReportModal');
    const closeBtn = document.getElementById('messageReportCloseBtn');
    const cancelBtn = document.getElementById('messageReportCancelBtn');
    const form = document.getElementById('messageReportForm');

    if (reportBtn) {
        reportBtn.addEventListener('click', openMessageReportModal);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMessageReportModal);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeMessageReportModal);
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeMessageReportModal();
            }
        });
    }

    if (form) {
        form.addEventListener('submit', submitMessageReport);
    }
}

function canCurrentUserReportInMessages() {
    const role = String(currentUserType || '').toLowerCase();
    return role === 'doctor' || role === 'patient';
}

function canReportSelectedCounterpart() {
    const role = String(selectedConversationUserRole || '').toLowerCase();
    return role === 'doctor' || role === 'patient';
}

function updateReportButtonState() {
    const reportBtn = document.getElementById('reportUserBtn');
    if (!reportBtn) return;

    const canUse = !!selectedConversationId
        && !!selectedConversationUserId
        && canCurrentUserReportInMessages()
        && canReportSelectedCounterpart();

    reportBtn.disabled = !canUse;
}

function setMessageReportReasonOptions() {
    const reasonSelect = document.getElementById('messageReportReason');
    if (!reasonSelect) return;

    reasonSelect.innerHTML = '<option value="">Select a reason</option>';
    MESSAGE_USER_REPORT_REASONS.forEach((label) => {
        const option = document.createElement('option');
        option.value = label;
        option.textContent = label;
        reasonSelect.appendChild(option);
    });
}

function openMessageReportModal() {
    if (!canCurrentUserReportInMessages()) {
        alert('Only doctors and patients can submit reports.');
        return;
    }

    if (!selectedConversationId || !selectedConversationUserId) {
        alert('Select a conversation first.');
        return;
    }

    if (!canReportSelectedCounterpart()) {
        alert('Only doctor or patient users can be reported from messages.');
        return;
    }

    const modal = document.getElementById('messageReportModal');
    const conversationInput = document.getElementById('messageReportConversationId');
    const userInput = document.getElementById('messageReportUserId');
    const descriptionInput = document.getElementById('messageReportDescription');

    if (!modal || !conversationInput || !userInput) {
        return;
    }

    setMessageReportReasonOptions();
    conversationInput.value = String(selectedConversationId);
    userInput.value = String(selectedConversationUserId);
    if (descriptionInput) {
        descriptionInput.value = '';
        descriptionInput.placeholder = selectedConversationUserName
            ? `Add details about ${selectedConversationUserName}`
            : 'Add details';
    }

    modal.style.display = 'flex';
}

function closeMessageReportModal() {
    const modal = document.getElementById('messageReportModal');
    const form = document.getElementById('messageReportForm');

    if (form) {
        form.reset();
    }
    if (modal) {
        modal.style.display = 'none';
    }
}

async function submitMessageReport(event) {
    event.preventDefault();

    const form = document.getElementById('messageReportForm');
    if (!form) return;

    const formData = new FormData(form);
    const payload = new URLSearchParams(formData);

    try {
        const response = await fetch(`${URLROOT}/Messages/submitUserReport`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: payload.toString()
        });

        const result = await response.json();
        if (!response.ok || !result.success) {
            alert(result.message || 'Could not submit report.');
            return;
        }

        alert(result.message || 'Report submitted successfully.');
        closeMessageReportModal();
    } catch (error) {
        console.error('Report submission failed:', error);
        alert('Could not submit report. Please try again.');
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
    lastMessageId = 0;
    
    if (!messages || messages.length === 0) {
        showEmptyMessages();
        lastMessageId = 0;
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
        
        // Track the highest message ID
        if (message.message_id > lastMessageId) {
            lastMessageId = message.message_id;
        }
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
    const isPending = !!message.pending;
    div.className = `message-bubble ${isSent ? 'sent' : 'received'}${isPending ? ' pending' : ''}`;
    if (message.message_id !== undefined && message.message_id !== null) {
        div.dataset.messageId = String(message.message_id);
    }
    const fingerprint = buildMessageFingerprint(message);
    if (fingerprint) {
        div.dataset.messageFingerprint = fingerprint;
    }
    
    const time = formatTime(message.created_at);
    const parsed = parseMessagePayload(message.message);
    const attachment = message.attachment || parsed.attachment || null;
    const text = parsed.text || '';
    const attachmentHtml = attachment ? renderAttachmentMarkup(attachment) : '';
    const messageId = message.message_id !== undefined && message.message_id !== null ? String(message.message_id) : '';
    const canManageMessage = isSent && !isPending && messageId && !messageId.startsWith('temp-') && !messagingDisabled;
    const actionButtonsHtml = canManageMessage ? `
        <div class="message-actions">
            <button type="button" class="message-action-btn message-edit-btn">Edit</button>
            <button type="button" class="message-action-btn message-delete-btn">Delete</button>
        </div>
    ` : '';
    
    div.innerHTML = `
        <div class="message-content">
            ${actionButtonsHtml}
            ${text ? `<p class="message-text">${escapeHtml(text)}</p>` : ''}
            ${attachmentHtml}
            <div class="message-meta">
                <span class="message-time">${time}</span>
            </div>
        </div>
    `;

    if (isSent) {
        const editButton = div.querySelector('.message-edit-btn');
        const deleteButton = div.querySelector('.message-delete-btn');

        if (editButton) {
            editButton.addEventListener('click', (event) => {
                event.stopPropagation();
                handleEditMessage(message);
            });
        }

        if (deleteButton) {
            deleteButton.addEventListener('click', (event) => {
                event.stopPropagation();
                handleDeleteMessage(message);
            });
        }
    }
    
    return div;
}

function parseMessagePayload(rawMessage) {
    if (!rawMessage) {
        return { text: '', attachment: null };
    }

    if (typeof rawMessage === 'object') {
        return {
            text: rawMessage.text || '',
            attachment: rawMessage.attachment || null
        };
    }

    if (typeof rawMessage === 'string') {
        try {
            const parsed = JSON.parse(rawMessage);
            if (parsed && typeof parsed === 'object' && (parsed.text !== undefined || parsed.attachment !== undefined)) {
                return {
                    text: parsed.text || '',
                    attachment: parsed.attachment || null
                };
            }
        } catch (error) {
            // Plain text message
        }

        return { text: rawMessage, attachment: null };
    }

    return { text: '', attachment: null };
}

function getFileTypeLabel(fileName, mimeType) {
    const mimeTypeLower = (mimeType || '').toLowerCase();
    const fileNameLower = (fileName || '').toLowerCase();
    
    if (mimeTypeLower.includes('pdf') || fileNameLower.endsWith('.pdf')) {
        return 'PDF document';
    }
    if (mimeTypeLower.includes('image/jpeg') || fileNameLower.endsWith('.jpg') || fileNameLower.endsWith('.jpeg')) {
        return 'JPEG image';
    }
    if (mimeTypeLower.includes('image/png') || fileNameLower.endsWith('.png')) {
        return 'PNG image';
    }
    
    const extension = fileNameLower.split('.').pop();
    return extension ? `${extension.toUpperCase()} file` : 'File attachment';
}

function renderAttachmentMarkup(attachment) {
    if (!attachment) return '';

    const fileUrl = attachment.url || attachment.file_url || attachment.path || '';
    const fileName = attachment.name || attachment.file_name || 'attachment';
    const mimeType = (attachment.type || attachment.mime_type || '').toLowerCase();
    const isImage = mimeType.startsWith('image/');
    const safeUrl = escapeHtml(fileUrl);
    const safeName = escapeHtml(fileName);
    const fileTypeLabel = getFileTypeLabel(fileName, mimeType);

    if (isImage) {
        return `
            <a class="message-attachment-image-link" href="${safeUrl}" target="_blank" rel="noopener">
                <img class="message-attachment-image" src="${safeUrl}" alt="${safeName}">
            </a>
        `;
    }

    return `
        <a class="message-attachment-file" href="${safeUrl}" target="_blank" rel="noopener" download>
            <span class="message-attachment-file-icon">FILE</span>
            <span class="message-attachment-file-info">
                <span class="message-attachment-file-name">${safeName}</span>
                <span class="message-attachment-file-type">${fileTypeLabel}</span>
            </span>
        </a>
    `;
}

function handleEditMessage(message) {
    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }

    const currentPayload = parseMessagePayload(message.message);
    const currentText = currentPayload.text || '';
    const newText = window.prompt('Edit message', currentText);

    if (newText === null) {
        return;
    }

    const trimmedText = newText.trim();
    if (!trimmedText && !currentPayload.attachment) {
        alert('Message cannot be empty.');
        return;
    }

    updateMessage(message.message_id, trimmedText);
}

async function updateMessage(messageId, messageText) {
    if (!messageId || !selectedConversationId) {
        return;
    }

    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('message', messageText);

        const response = await fetch(`${URLROOT}/Messages/updateMessage`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            await loadMessages(selectedConversationId);
            loadConversations();
        } else {
            alert(data.message || 'Failed to update message');
        }
    } catch (error) {
        console.error('Error updating message:', error);
        alert('Error updating message');
    }
}

function handleDeleteMessage(message) {
    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }

    const confirmed = window.confirm('Delete this message?');
    if (!confirmed) {
        return;
    }

    deleteMessage(message.message_id);
}

async function deleteMessage(messageId) {
    if (!messageId || !selectedConversationId) {
        return;
    }

    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/Messages/deleteMessage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message_id: messageId })
        });

        const data = await response.json();
        if (data.success) {
            await loadMessages(selectedConversationId);
            loadConversations();
        } else {
            alert(data.message || 'Failed to delete message');
        }
    } catch (error) {
        console.error('Error deleting message:', error);
        alert('Error deleting message');
    }
}

function setMessageActionsDisabled(messageBubble, disabled) {
    if (!messageBubble) return;

    const actionButtons = messageBubble.querySelectorAll('.message-action-btn');
    actionButtons.forEach(button => {
        button.disabled = disabled;
    });
}

// Show empty messages state
function showEmptyMessages() {
    const messagesDisplay = document.getElementById('messagesDisplay');
    messagesDisplay.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #8696a0; text-align: center;">
            <div>
                <p style="margin: 0; font-size: 14px;">No messages yet</p>
                <small style="font-size: 12px;">Send a message to start the conversation</small>
            </div>
        </div>
    `;
}

// Send message
async function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const rawMessageText = messageInput.value;
    const messageText = rawMessageText.trim();

    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }
    
    // Validation: Check if conversation is selected
    if (!selectedConversationId) {
        return;
    }
    
    // Validation: Check if message text or attachment exists
    if (!messageText && !selectedAttachmentFile) {
        return;
    }
    
    // Validation: Check message length (max 1000 characters)
    if (messageText.length > 1000) {
        alert('Message is too long. Maximum 1000 characters allowed.');
        return;
    }

    const optimisticMessage = {
        message_id: `temp-${Date.now()}`,
        sender_id: currentUserId,
        message: selectedAttachmentFile
            ? JSON.stringify({
                text: messageText,
                attachment: {
                    name: selectedAttachmentFile.name,
                    type: selectedAttachmentFile.type || ''
                }
            })
            : messageText,
        attachment: selectedAttachmentFile ? {
            name: selectedAttachmentFile.name,
            type: selectedAttachmentFile.type || ''
        } : null,
        created_at: new Date().toISOString(),
        is_read: false,
        pending: true
    };

    // Clear the input immediately so the sent text does not remain in the textarea.
    messageInput.value = '';
    messageInput.style.height = 'auto';
    const pendingBubble = addMessageToDisplay(optimisticMessage, true);

    const handleSendFailure = (messageTextToShow) => {
        if (pendingBubble && pendingBubble.parentNode) {
            pendingBubble.parentNode.removeChild(pendingBubble);
        }
        if (messageTextToShow) {
            console.warn('Send message failed:', messageTextToShow);
        }
        if (selectedConversationId) {
            loadMessages(selectedConversationId);
        }
    };
    
    try {
        const formData = new FormData();
        formData.append('conversation_id', selectedConversationId);
        formData.append('message', messageText);

        if (selectedAttachmentFile) {
            formData.append('attachment', selectedAttachmentFile);
        }

        const response = await fetch(`${URLROOT}/Messages/sendMessage`, {
            method: 'POST',
            body: formData
        });

        const responseText = await response.text();
        let data = null;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Non-JSON response from sendMessage:', responseText);
            handleSendFailure('Could not send message. Please try again.');
            return;
        }
        
        if (data.success) {
            clearSelectedAttachment();

            if (pendingBubble) {
                pendingBubble.classList.remove('pending');
                optimisticMessage.message_id = data.message_id || optimisticMessage.message_id;
                pendingBubble.dataset.messageId = String(data.message_id || pendingBubble.dataset.messageId || '');
                pendingBubble.dataset.messageFingerprint = buildMessageFingerprint({
                    sender_id: currentUserId,
                    message: data.message || messageText,
                    attachment: data.attachment || optimisticMessage.attachment,
                    created_at: optimisticMessage.created_at
                });
                setMessageActionsDisabled(pendingBubble, false);
            }
            
            // Update lastMessageId
            if (data.message_id > lastMessageId) {
                lastMessageId = data.message_id;
            }
            
            // Refresh the active chat so the server state stays in sync immediately.
            refreshActiveConversation();

            // Refresh conversations list
            loadConversations();
        } else {
            console.error('Failed to send message:', data.message || 'Unknown error');
            handleSendFailure(data.message || 'Failed to send message');
            loadConversations();
        }
    } catch (error) {
        console.error('Error sending message:', error);
        handleSendFailure('Network error while sending message.');
        loadConversations();
    }
}

// Add message to display
function addMessageToDisplay(message, isOptimistic = false) {
    const messagesDisplay = document.getElementById('messagesDisplay');
    const messageId = message && message.message_id !== undefined && message.message_id !== null
        ? String(message.message_id)
        : '';
    const fingerprint = buildMessageFingerprint(message);

    if (messageId) {
        const existingBubble = messagesDisplay.querySelector(`.message-bubble[data-message-id="${CSS.escape(messageId)}"]`);
        if (existingBubble) {
            return existingBubble;
        }
    }

    if (fingerprint) {
        const existingFingerprintBubble = messagesDisplay.querySelector(`.message-bubble[data-message-fingerprint="${CSS.escape(fingerprint)}"]`);
        if (existingFingerprintBubble) {
            if (messageId) {
                existingFingerprintBubble.dataset.messageId = messageId;
            }

            if (message.sender_id == currentUserId) {
                existingFingerprintBubble.classList.remove('pending');
            }

            return existingFingerprintBubble;
        }
    }
    
    // Remove empty state if exists
    const emptyState = messagesDisplay.querySelector('[style*="No messages yet"]');
    if (emptyState) {
        messagesDisplay.innerHTML = '';
    }
    
    const messageBubble = createMessageBubble(message);
    messagesDisplay.appendChild(messageBubble);
    scrollToBottom();

    return isOptimistic ? messageBubble : undefined;
}

function buildMessageFingerprint(message) {
    if (!message) return '';

    const parsed = parseMessagePayload(message.message);
    const text = (parsed.text || '').trim();
    const attachment = message.attachment || parsed.attachment || null;
    const attachmentName = attachment ? (attachment.name || attachment.file_name || attachment.path || '') : '';
    const attachmentType = attachment ? (attachment.type || attachment.mime_type || '') : '';
    const senderId = message.sender_id !== undefined && message.sender_id !== null ? String(message.sender_id) : '';

    if (!senderId && !text && !attachmentName) {
        return '';
    }

    return [senderId, text, attachmentName, attachmentType].join('|');
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

// Refresh the currently selected conversation from the server.
function refreshActiveConversation() {
    if (selectedConversationId) {
        checkForNewMessages();
        markConversationAsRead(selectedConversationId);
    }
    loadConversations();
}

// Keep conversation list updated so receiver sees new chats/messages without page refresh.
function startConversationPolling() {
    if (conversationPollingInterval) {
        clearInterval(conversationPollingInterval);
    }

    conversationPollingInterval = setInterval(() => {
        loadConversations();
    }, 3000);
}

// Check for new messages
async function checkForNewMessages() {
    if (!selectedConversationId) return;
    
    try {
        const response = await fetch(`${URLROOT}/Messages/getNewMessages/${selectedConversationId}?last_message_id=${lastMessageId}`);
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addMessageToDisplay(message);
                
                // Update lastMessageId
                if (message.message_id > lastMessageId) {
                    lastMessageId = message.message_id;
                }
            });
            
            // Mark as read
            markConversationAsRead(selectedConversationId);
            
            // Refresh conversations list to update preview
            loadConversations();
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
    renderSidebarList(event.target.value || '');
}

// Start chat with selected eligible user (creates conversation if needed)
async function startChatWithUser(recipientId) {
    if (!recipientId) {
        return;
    }

    if (messagingDisabled) {
        alert('Your account is suspended or deactivated. Messaging is disabled.');
        return;
    }

    try {
        const response = await fetch(`${URLROOT}/Messages/createConversation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ recipient_id: recipientId })
        });

        const data = await response.json();
        if (!data.success || !data.conversation_id) {
            alert(data.message || 'Unable to start conversation');
            return;
        }

        const contact = eligibleContacts.find(c => String(c.user_id) === String(recipientId));

        await loadConversations();
        await selectConversation(data.conversation_id, recipientId, {
            name: contact ? (contact.user_name || '') : '',
            role: contact ? (contact.user_role || '') : ''
        });
    } catch (error) {
        console.error('Error starting chat:', error);
        alert('Error starting chat');
    }
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

    if (conversationPollingInterval) {
        clearInterval(conversationPollingInterval);
    }
});

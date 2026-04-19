<!-- Reusable WhatsApp-Style Message Component -->
<?php
    $currentUserStatus = strtolower((string)($data['user_status'] ?? $_SESSION['user_status'] ?? 'active'));
    $currentUserRole = strtolower((string)($_SESSION['user_role'] ?? ''));
    $isRestrictedMessagingRole = in_array($currentUserRole, ['patient', 'doctor'], true);
    $isSuspendedAccount = ($currentUserStatus === 'suspended' && $isRestrictedMessagingRole);
    $isDeactivatedAccount = ($currentUserStatus === 'inactive' && $isRestrictedMessagingRole);
?>
<div
    class="message-container"
    data-user-id="<?php echo (int)($_SESSION['user_id'] ?? 0); ?>"
    data-user-type="<?php echo htmlspecialchars(strtolower((string)($_SESSION['user_role'] ?? ''))); ?>"
    data-messaging-disabled="<?php echo ($isSuspendedAccount || $isDeactivatedAccount) ? '1' : '0'; ?>"
>
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
                <p><?php echo $isSuspendedAccount ? 'Your account is suspended. Messaging is disabled.' : ($isDeactivatedAccount ? 'Your account is deactivated. Messaging is disabled.' : 'Send and receive messages securely'); ?></p>
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
                <div class="chat-header-right">
                    <button type="button" class="report-flag-btn" id="reportUserBtn" disabled>Report User</button>
                </div>
            </div>

            <!-- Messages Display Area -->
            <div class="messages-list messages-display" id="messagesDisplay">
                <!-- Messages will be dynamically loaded here -->
            </div>

            <!-- Message Input Area -->
            <div class="message-input-area">
                <input type="file" id="chatAttachmentInput" class="chat-attachment-input" accept="image/jpeg,image/png,application/pdf,.jpg,.jpeg,.png,.pdf" hidden>
                <button class="attach-button" id="attachButton" type="button" title="Attach file" <?php echo ($isSuspendedAccount || $isDeactivatedAccount) ? 'disabled' : ''; ?>>Attach</button>
                <div class="input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="message-input" 
                        placeholder="<?php echo $isSuspendedAccount || $isDeactivatedAccount ? 'Messaging disabled for your account' : 'Type a message'; ?>"
                        rows="1"
                        <?php echo ($isSuspendedAccount || $isDeactivatedAccount) ? 'disabled' : ''; ?>></textarea>
                </div>
                <button class="send-button" id="sendButton" type="button" <?php echo ($isSuspendedAccount || $isDeactivatedAccount) ? 'disabled' : ''; ?>>
                    <span class="send-icon">Send</span>
                </button>
            </div>
            <?php if ($isSuspendedAccount || $isDeactivatedAccount): ?>
                <div class="message-access-warning">
                    <?php echo $isSuspendedAccount ? 'Your account is suspended. You cannot send messages.' : 'Your account is deactivated. Please contact admin.'; ?>
                </div>
            <?php endif; ?>
            <div class="attachment-preview" id="attachmentPreview" style="display:none;">
                <div class="attachment-preview-info">
                    <span class="attachment-preview-icon">📎</span>
                    <span class="attachment-preview-name" id="attachmentPreviewName"></span>
                </div>
                <button type="button" class="attachment-preview-remove" id="removeAttachmentBtn">×</button>
            </div>
        </div>

        <div class="message-report-modal" id="messageReportModal" style="display:none;">
            <div class="message-report-dialog">
                <div class="message-report-header">
                    <h3>Report User</h3>
                    <button type="button" class="message-report-close" id="messageReportCloseBtn">×</button>
                </div>
                <form id="messageReportForm" class="message-report-form">
                    <input type="hidden" name="conversation_id" id="messageReportConversationId" value="">
                    <input type="hidden" name="reported_user_id" id="messageReportUserId" value="">

                    <label for="messageReportReason">Reason</label>
                    <select id="messageReportReason" name="reason" required>
                        <option value="">Select a reason</option>
                    </select>

                    <label for="messageReportDescription">Additional Details (optional)</label>
                    <textarea id="messageReportDescription" name="description" rows="3" placeholder="Add details"></textarea>

                    <div class="message-report-actions">
                        <button type="button" class="message-report-cancel" id="messageReportCancelBtn">Cancel</button>
                        <button type="submit" class="message-report-submit">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

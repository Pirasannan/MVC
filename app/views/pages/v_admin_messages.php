<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminMessages';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/messages/admin_messages.css'>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

        <!-- Messages Content -->
        <div class="dashboard-content">
            
            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="success-message" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    ✅ <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-card primary">
                    <div class="stat-content">
                        <h3 class="stat-title">Total Messages</h3>
                        <div class="stat-number"><?php echo $data['total_messages'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Active Conversations</h3>
                        <div class="stat-number"><?php echo $data['active_conversations'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Messages Today</h3>
                        <div class="stat-number"><?php echo $data['messages_today'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Response Rate</h3>
                        <div class="stat-number">92%</div>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                
                <!-- Recent Messages Overview Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Messages Overview</h2>
                        <p class="section-subtitle">Monitor and manage all system messages</p>
                    </div>
                    <div class="section-content">
                        <?php if (empty($data['recent_messages'] ?? [])): ?>
                            <div class="empty-state" style="text-align: center; padding: 40px; color: #6c757d;">
                                <div class="empty-icon" style="font-size: 48px; margin-bottom: 16px;">📊</div>
                                <h3 style="margin-bottom: 8px; color: #495057;">No recent messages</h3>
                                <p>System message activity will appear here.</p>
                            </div>
                        <?php else: ?>
                            <div class="messages-grid">
                                <?php foreach ($data['recent_messages'] as $message): ?>
                                    <div class="message-card">
                                        <div class="card-header">
                                            <div class="message-info">
                                                <div class="participants">
                                                    <span class="sender">Dr. <?php echo htmlspecialchars($message->doctor_name); ?></span>
                                                    <span class="arrow">→</span>
                                                    <span class="recipient"><?php echo htmlspecialchars($message->patient_name); ?></span>
                                                </div>
                                                <span class="message-time"><?php echo date('M j, Y • g:i A', strtotime($message->sent_at)); ?></span>
                                            </div>
                                            <div class="message-status">
                                                <span class="status-badge <?php echo $message->status; ?>"><?php echo ucfirst($message->status); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                            <div class="message-preview">
                                                <h4 class="message-subject"><?php echo htmlspecialchars($message->subject); ?></h4>
                                                <p class="message-text"><?php echo htmlspecialchars(substr($message->message_text, 0, 100)) . (strlen($message->message_text) > 100 ? '...' : ''); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="card-actions">
                                            <button class="action-button primary small" onclick="viewMessage(<?php echo $message->id; ?>)">
                                                <span class="button-icon"></span>
                                                View
                                            </button>
                                            <button class="action-button secondary small" onclick="moderateMessage(<?php echo $message->id; ?>)">
                                                <span class="button-icon"></span>
                                                Moderate
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Message View Modal -->
<div id="messageModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); padding: 30px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div class="modal-header" style="border-bottom: 2px solid #3498db; margin-bottom: 20px; padding-bottom: 15px;">
            <h2 style="margin: 0; color: #2c3e50;">Message Details</h2>
            <button onclick="closeMessageModal()" style="position: absolute; right: 15px; top: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="message-info" style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <strong>From:</strong> <span id="modalSender">Dr. Sarah Johnson</span>
                    </div>
                    <div>
                        <strong>To:</strong> <span id="modalRecipient">Michael Chen</span>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Subject:</strong> <span id="modalSubject">Prescription Follow-up</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Time:</strong> <span id="modalMessageTime">Oct 23, 2024 • 10:30 AM</span>
                </div>
            </div>
            
            <div class="message-content" style="border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; background: #f8f9fa; min-height: 150px;">
                <h4 style="margin-bottom: 15px; color: #2c3e50;">Message Content:</h4>
                <p id="modalMessageText" style="line-height: 1.6; color: #333;">This is the full message content that would appear here.</p>
            </div>
            
            <div class="message-actions" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef; display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="moderateMessageFromModal()" class="action-button secondary">Moderate Message</button>
                <button onclick="closeMessageModal()" class="action-button primary">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for interactive functionality -->
<script>

function clearAnnouncementForm() {
    document.getElementById('recipient_type').value = '';
    document.getElementById('priority').value = 'normal';
    document.getElementById('subject').value = '';
    document.getElementById('message_text').value = '';
}

function viewMessage(messageId) {
    // Open the message modal
    const modal = document.getElementById('messageModal');
    if (modal) {
        // Set the message ID in the modal for reference
        modal.setAttribute('data-message-id', messageId);
        
        // Update modal content with message-specific information
        const messageCard = document.querySelector(`[onclick="viewMessage(${messageId})"]`).closest('.message-card');
        const sender = messageCard.querySelector('.sender').textContent;
        const recipient = messageCard.querySelector('.recipient').textContent;
        const subject = messageCard.querySelector('.message-subject').textContent;
        const messageText = messageCard.querySelector('.message-text').textContent;
        const messageTime = messageCard.querySelector('.message-time').textContent;
        
        document.getElementById('modalSender').textContent = sender;
        document.getElementById('modalRecipient').textContent = recipient;
        document.getElementById('modalSubject').textContent = subject;
        document.getElementById('modalMessageText').textContent = messageText;
        document.getElementById('modalMessageTime').textContent = messageTime;
        
        // Show the modal
        modal.style.display = 'flex';
    }
}

function moderateMessage(messageId) {
    // This could be expanded to show moderation options
    alert('Message moderation feature coming soon! Message ID: ' + messageId);
}

function closeMessageModal() {
    const modal = document.getElementById('messageModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function moderateMessageFromModal() {
    const messageId = document.getElementById('messageModal').getAttribute('data-message-id');
    closeMessageModal();
    moderateMessage(messageId);
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('messageModal');
    if (e.target === modal) {
        closeMessageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMessageModal();
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
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

<!-- JavaScript for interactive functionality -->
<script>

function clearAnnouncementForm() {
    document.getElementById('recipient_type').value = '';
    document.getElementById('priority').value = 'normal';
    document.getElementById('subject').value = '';
    document.getElementById('message_text').value = '';
}

function viewMessage(messageId) {
    // This could be expanded to show a detailed message view
    alert('Message details view coming soon! Message ID: ' + messageId);
}

function moderateMessage(messageId) {
    // This could be expanded to show moderation options
    alert('Message moderation feature coming soon! Message ID: ' + messageId);
}

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
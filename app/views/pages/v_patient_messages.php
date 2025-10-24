<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientMessages';
?>

<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/messages/patient_messages.css'>

<style>
/* Override send message button color */
.message-form .action-button.primary.large {
    background: #4a90e2 !important;
}
.message-form .action-button.primary.large:hover {
    background: #357abd !important;
}
</style>

<div class="dashboard-container patient">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

        <!-- Messages Content -->
        <div class="dashboard-content">
            
            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-card primary">
                    <div class="stat-content">
                        <h3 class="stat-title">Unread Messages</h3>
                        <div class="stat-number"><?php echo $data['unread_count'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Total Conversations</h3>
                        <div class="stat-number"><?php echo count($data['conversations'] ?? []); ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">My Doctors</h3>
                        <div class="stat-number"><?php echo count($data['doctors'] ?? []); ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Response Rate</h3>
                        <div class="stat-number">95%</div>
                    </div>
                </div>
            </div>

            <!-- Content Sections - Single Column Layout -->
            <div class="content-sections single-column">
                
                <!-- Recent Conversations Section - Now at the top -->
                <div class="content-section conversations-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Conversations</h2>
                        <p class="section-subtitle">View and manage your conversations with doctors</p>
                    </div>
                    <div class="section-content">
                        <?php if (empty($data['conversations'] ?? [])): ?>
                            <div class="empty-state">
                                <div class="empty-icon">💬</div>
                                <h3>No conversations yet</h3>
                                <p>Start a conversation with your doctor by sending a message below.</p>
                            </div>
                        <?php else: ?>
                            <div class="conversations-grid">
                                <?php foreach ($data['conversations'] as $conversation): ?>
                                    <div class="conversation-card <?php echo $conversation->unread_count > 0 ? 'unread' : ''; ?>">
                                        <div class="card-header">
                                            <div class="doctor-info">
                                                <div class="doctor-avatar">
                                                    <span class="avatar-text"><?php echo strtoupper(substr($conversation->doctor_name, 0, 2)); ?></span>
                                                </div>
                                                <div class="doctor-details">
                                                    <h4 class="doctor-name">Dr. <?php echo htmlspecialchars($conversation->doctor_name); ?></h4>
                                                    <span class="last-message-time"><?php echo date('M j, Y • g:i A', strtotime($conversation->last_message_time)); ?></span>
                                                </div>
                                            </div>
                                            <?php if ($conversation->unread_count > 0): ?>
                                                <div class="unread-badge">
                                                    <span class="unread-count"><?php echo $conversation->unread_count; ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="card-body">
                                            <div class="message-preview">
                                                <span class="message-direction"><?php echo $conversation->sender_type === 'doctor' ? 'Doctor:' : 'You:'; ?></span>
                                                <p class="message-text"><?php echo htmlspecialchars(substr($conversation->last_message, 0, 120)) . (strlen($conversation->last_message) > 120 ? '...' : ''); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="card-actions">
                                            <button class="action-button primary" onclick="viewConversation(<?php echo $conversation->doctor_id; ?>)">
                                                <span class="button-icon">👁️</span>
                                                View Chat
                                            </button>
                                            <?php if ($conversation->unread_count > 0): ?>
                                                <button class="action-button secondary" onclick="markAsRead(<?php echo $conversation->doctor_id; ?>)">
                                                    <span class="button-icon">✓</span>
                                                    Mark as Read
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Send New Message Section - Now below conversations -->
                <div class="content-section new-message-section">
                    <div class="section-header">
                        <h2 class="section-title">Send New Message</h2>
                        <p class="section-subtitle">Start a new conversation with your doctor</p>
                    </div>
                    <div class="section-content">
                        <div class="message-form-container">
                            <form method="POST" class="message-form">
                                <input type="hidden" name="action" value="send_message">
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="doctor_id">Select Doctor:</label>
                                        <select name="doctor_id" id="doctor_id" required>
                                            <option value="">Choose your doctor...</option>
                                            <?php if (!empty($data['doctors'])): ?>
                                                <?php foreach ($data['doctors'] as $doctor): ?>
                                                    <option value="<?php echo $doctor->id; ?>">
                                                        Dr. <?php echo htmlspecialchars($doctor->name); ?> (<?php echo htmlspecialchars($doctor->specialization ?? 'General'); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject:</label>
                                        <input type="text" name="subject" id="subject" placeholder="Enter message subject" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message_text">Message:</label>
                                    <textarea name="message_text" id="message_text" rows="4" placeholder="Type your message here..." required></textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="action-button primary large">
                                        <span class="button-icon">📤</span>
                                        Send Message
                                    </button>
                                    <button type="button" class="action-button secondary" onclick="clearForm()">
                                        Clear Form
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- JavaScript for interactive functionality -->
<script>

function clearForm() {
    document.getElementById('doctor_id').value = '';
    document.getElementById('subject').value = '';
    document.getElementById('message_text').value = '';
}

function markAsRead(doctorId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="action" value="mark_read">
        <input type="hidden" name="doctor_id" value="${doctorId}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function viewConversation(doctorId) {
    // This could be expanded to show a detailed conversation view
    alert('Conversation view feature coming soon! Doctor ID: ' + doctorId);
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
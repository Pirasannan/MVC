<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'doctorMessages';    
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/messages/doctor_messages.css'>

<div class="dashboard-container doctor">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>

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
                <div class="stat-card ">
                    <div class="stat-content">
                        <h3 class="stat-title">Unread Messages</h3>
                        <div class="stat-number"><?php echo $data['unread_count']; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Total Conversations</h3>
                        <div class="stat-number"><?php echo count($data['conversations']); ?></div>
                    </div>
                </div>
                <!-- <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Active Patients</h3>
                        <div class="stat-number"><?php echo count($data['patients']); ?></div>
                    </div>
                </div> -->
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Response Rate</h3>
                        <div class="stat-number">98%</div>
                    </div>
                </div>
            </div>

            <!-- Content Sections - Single Column Layout -->
            <div class="content-sections single-column">
                
                <!-- Recent Conversations Section - Now at the top -->
                <div class="content-section conversations-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Conversations</h2>
                        <p class="section-subtitle">View and manage your latest patient conversations</p>
                    </div>
                    <div class="section-content">
                        <?php if (empty($data['conversations'])): ?>
                            <div class="empty-state">
                                <div class="empty-icon">💬</div>
                                <h3>No conversations yet</h3>
                                <p>Start a conversation with your patients by sending a message below.</p>
                            </div>
                        <?php else: ?>
                            <div class="conversations-grid">
                                <?php foreach ($data['conversations'] as $conversation): ?>
                                    <div class="conversation-card <?php echo $conversation->unread_count > 0 ? 'unread' : ''; ?>">
                                        <div class="card-header">
                                            <div class="patient-info">
                                                <div class="patient-avatar">
                                                    <span class="avatar-text"><?php echo strtoupper(substr($conversation->patient_name, 0, 2)); ?></span>
                                                </div>
                                                <div class="patient-details">
                                                    <h4 class="patient-name"><?php echo htmlspecialchars($conversation->patient_name); ?></h4>
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
                                                <span class="message-direction"><?php echo $conversation->sender_type === 'patient' ? 'Patient:' : 'You:'; ?></span>
                                                <p class="message-text"><?php echo htmlspecialchars(substr($conversation->last_message, 0, 120)) . (strlen($conversation->last_message) > 120 ? '...' : ''); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="card-actions">
                                            <button class="action-button primary" onclick="viewConversation(<?php echo $conversation->patient_id; ?>)">
                                                <span class="button-icon">👁️</span>
                                                View Chat
                                            </button>
                                            <?php if ($conversation->unread_count > 0): ?>
                                                <button class="action-button secondary" onclick="markAsRead(<?php echo $conversation->patient_id; ?>)">
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
                        <p class="section-subtitle">Start a new conversation with a patient</p>
                    </div>
                    <div class="section-content">
                        <div class="message-form-container">
                            <form method="POST" class="message-form">
                                <input type="hidden" name="action" value="send_message">
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="patient_id">Select Patient:</label>
                                        <select name="patient_id" id="patient_id" required>
                                            <option value="">Choose a patient...</option>
                                            <?php foreach ($data['patients'] as $patient): ?>
                                                <option value="<?php echo $patient->id; ?>">
                                                    <?php echo htmlspecialchars($patient->name); ?> (<?php echo htmlspecialchars($patient->email); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject:</label>
                                        <input type="text" name="subject" id="subject" placeholder="Enter message subject" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message_text">Message:</label>
                                    <textarea name="message_text" id="message_text" rows="6" placeholder="Type your message here..." required></textarea>
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
    document.getElementById('patient_id').value = '';
    document.getElementById('subject').value = '';
    document.getElementById('message_text').value = '';
}

function markAsRead(patientId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="action" value="mark_read">
        <input type="hidden" name="patient_id" value="${patientId}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function viewConversation(patientId) {
    // This could be expanded to show a detailed conversation view
    alert('Conversation view feature coming soon! Patient ID: ' + patientId);
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
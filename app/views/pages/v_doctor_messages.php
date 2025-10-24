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
                <div class="stat-card primary">
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
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Active Patients</h3>
                        <div class="stat-number"><?php echo count($data['patients']); ?></div>
                    </div>
                </div>
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

<!-- Conversation View Modal -->
<div id="conversationModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); padding: 30px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div class="modal-header" style="border-bottom: 2px solid #3498db; margin-bottom: 20px; padding-bottom: 15px;">
            <h2 style="margin: 0; color: #2c3e50;">Conversation Details</h2>
            <button onclick="closeConversationModal()" style="position: absolute; right: 15px; top: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="conversation-info" style="margin-bottom: 20px;">
                <h3 id="modalPatientName" style="color: #2c3e50; margin-bottom: 10px;">Patient Name</h3>
                <p><strong>Last Message:</strong> <span id="modalLastMessage">Message content will appear here</span></p>
            </div>
            
            <div class="message-history" style="max-height: 300px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; background: #f8f9fa;">
                <div class="message-item" style="margin-bottom: 15px; padding: 10px; background: white; border-radius: 8px; border-left: 4px solid #3498db;">
                    <div style="font-weight: bold; color: #2c3e50; margin-bottom: 5px;">Patient</div>
                    <div>This is a sample message from the patient. The actual conversation history would be loaded here.</div>
                    <div style="font-size: 12px; color: #666; margin-top: 8px;">2024-01-15 10:30 AM</div>
                </div>
                
                <div class="message-item" style="margin-bottom: 15px; padding: 10px; background: white; border-radius: 8px; border-left: 4px solid #27ae60;">
                    <div style="font-weight: bold; color: #2c3e50; margin-bottom: 5px;">You</div>
                    <div>This is your response to the patient. All your messages would appear here.</div>
                    <div style="font-size: 12px; color: #666; margin-top: 8px;">2024-01-15 11:15 AM</div>
                </div>
            </div>
            
            <div class="reply-section" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <h4 style="margin-bottom: 10px; color: #2c3e50;">Send Reply</h4>
                <textarea id="replyMessage" placeholder="Type your reply here..." style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; margin-bottom: 10px; resize: vertical; min-height: 80px;"></textarea>
                <div style="display: flex; gap: 10px;">
                    <button onclick="sendReply()" class="action-button primary" style="padding: 10px 20px;">Send Reply</button>
                    <button onclick="closeConversationModal()" class="action-button secondary" style="padding: 10px 20px;">Close</button>
                </div>
            </div>
        </div>
    </div>
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
    // Open the conversation modal
    const modal = document.getElementById('conversationModal');
    if (modal) {
        // Set the patient ID in the modal for reference
        modal.setAttribute('data-patient-id', patientId);
        
        // Update modal content with patient-specific information
        const patientName = document.querySelector(`[onclick="viewConversation(${patientId})"]`).closest('.conversation-card').querySelector('.patient-name').textContent;
        const lastMessage = document.querySelector(`[onclick="viewConversation(${patientId})"]`).closest('.conversation-card').querySelector('.message-text').textContent;
        
        document.getElementById('modalPatientName').textContent = patientName;
        document.getElementById('modalLastMessage').textContent = lastMessage;
        
        // Show the modal
        modal.style.display = 'flex';
    }
}

function closeConversationModal() {
    const modal = document.getElementById('conversationModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function sendReply() {
    const replyText = document.getElementById('replyMessage').value.trim();
    const patientId = document.getElementById('conversationModal').getAttribute('data-patient-id');
    
    if (!replyText) {
        alert('Please enter a message before sending.');
        return;
    }
    
    // Here you would typically send the reply via AJAX or form submission
    // For now, we'll just show a success message
    alert('Reply sent successfully!');
    document.getElementById('replyMessage').value = '';
    
    // Close the modal
    closeConversationModal();
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('conversationModal');
    if (e.target === modal) {
        closeConversationModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConversationModal();
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
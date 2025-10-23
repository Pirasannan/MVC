<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'patientVideoCall';
?>

<div class="videocall-container">
    <!-- Top Bar -->
    <div class="videocall-topbar">
        <div class="call-info">
            <div class="participant-info">
                <div class="participant-details">
                    <h3>Dr. Maran</h3>
                </div>
            </div>
            <div class="call-duration">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12,6 12,12 16,14"/>
                </svg>
                <span id="callTimer">00:00</span>
                <span class="call-status">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 12l2 2 4-4"/>
                    </svg>
                    Connected
                </span>
            </div>
        </div>
        <div class="topbar-actions">
            <button class="btn-icon" onclick="toggleChat()" title="Chat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Video Area -->
    <div class="videocall-main">
        <!-- Doctor Video (Large) -->
        <div class="remote-video-container">
            <div class="video-placeholder">
                <div class="video-overlay">
                    <div class="participant-name">Dr. Maran</div>
                    <div class="connection-status">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                            <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                            <line x1="12" y1="20" x2="12.01" y2="20"/>
                        </svg>
                        Good connection
                    </div>
                </div>
            </div>
        </div>

        <!-- Local Video (Small) -->
        <div class="local-video-container">
            <div class="video-placeholder">
                <div class="local-video-overlay">
                    <div class="local-name">You</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Controls -->
    <div class="videocall-controls">
        <div class="control-group">
            <button class="control-btn mic-btn active" id="micBtn" title="Microphone">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                    <line x1="12" y1="19" x2="12" y2="23"/>
                    <line x1="8" y1="23" x2="16" y2="23"/>
                </svg>
            </button>
            <button class="control-btn camera-btn active" id="cameraBtn" title="Camera">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 7l-7 5 7 5V7z"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
            </button>
        </div>

        <div class="control-group">
            <button class="control-btn endcall-btn" id="endCallBtn" title="Leave Call">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"/>
                    <line x1="23" y1="1" x2="1" y2="23"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Chat Panel -->
    <div class="chat-panel" id="chatPanel">
        <div class="chat-header">
            <h4>Chat</h4>
            <button class="close-btn" onclick="toggleChat()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message received">
                <div class="message-content">Hello! I'm ready for the consultation.</div>
                <div class="message-time">2:30 PM</div>
            </div>
            <div class="message sent">
                <div class="message-content">Good morning! How are you feeling today?</div>
                <div class="message-time">2:31 PM</div>
            </div>
            <div class="message received">
                <div class="message-content">I'm feeling much better, thank you for asking.</div>
                <div class="message-time">2:32 PM</div>
            </div>
        </div>
    </div>

    <!-- Help Panel -->
    <div class="help-panel" id="helpPanel">
        <div class="panel-header">
            <h4>Help & Support</h4>
            <button class="close-btn" onclick="toggleHelp()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="help-content">
            <div class="help-section">
                <h5>Video Call Controls</h5>
                <ul>
                    <li><strong>Microphone:</strong> Toggle your microphone on/off</li>
                    <li><strong>Camera:</strong> Toggle your camera on/off</li>
                    <li><strong>Chat:</strong> Send messages during the call</li>
                    <li><strong>Leave Call:</strong> Exit the consultation</li>
                </ul>
            </div>
            <div class="help-section">
                <h5>Technical Support</h5>
                <p>If you experience any technical issues:</p>
                <ul>
                    <li>Check your internet connection</li>
                    <li>Refresh the page</li>
                    <li>Contact support at support@telemedicine.com</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let callStartTime = new Date();
let isMicOn = true;
let isCameraOn = true;
let callTimer;

// Start call timer
function startCallTimer() {
    callTimer = setInterval(() => {
        const now = new Date();
        const elapsed = now - callStartTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);
        
        const timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        document.getElementById('callTimer').textContent = timeString;
    }, 1000);
}

// Initialize controls
function initializeControls() {
    // Microphone toggle
    document.getElementById('micBtn').addEventListener('click', function() {
        isMicOn = !isMicOn;
        const btn = this;
        
        if (isMicOn) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Camera toggle
    document.getElementById('cameraBtn').addEventListener('click', function() {
        isCameraOn = !isCameraOn;
        const btn = this;
        
        if (isCameraOn) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Leave call
    document.getElementById('endCallBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to leave the call?')) {
            leaveCall();
        }
    });
}

// Toggle chat panel
function toggleChat() {
    const panel = document.getElementById('chatPanel');
    panel.classList.toggle('active');
}

// Toggle help panel
function toggleHelp() {
    const panel = document.getElementById('helpPanel');
    panel.classList.toggle('active');
}

// Leave call
function leaveCall() {
    if (callTimer) {
        clearInterval(callTimer);
    }
    
    // Redirect to appointments page
    window.location.href = '<?php echo URLROOT; ?>/Pages/patientAppointments';
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    startCallTimer();
    initializeControls();
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

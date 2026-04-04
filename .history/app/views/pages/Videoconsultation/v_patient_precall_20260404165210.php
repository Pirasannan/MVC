<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'patientPrecall';
$apt = $data['appointment'];
$appointmentId = (int)$apt->id;
?>

<div class="precall-container">
    <div class="precall-content">
        <!-- Header -->
        <div class="precall-header">
            <button class="back-btn" onclick="history.back()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back
            </button>
            <h1>Preview</h1>
        </div>

        <!-- Patient Info -->
        <div class="patient-info-card">
            <div class="patient-avatar">
            </div>
            <div class="patient-details">
                <h3>Dr.Maran</h3>
                <p class="appointment-time">Today at 2:30 PM</p>
                <p class="appointment-type">Follow-up Consultation</p>
            </div>
        </div>

        <!-- Media Preview -->
        <div class="media-setup">
            <div class="media-preview">
                <div class="video-preview">
                    <div class="video-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 7l-7 5 7 5V7z"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        <span>Camera Preview</span>
                    </div>
                </div>
            </div>

            <!-- Media Controls -->
            <div class="media-controls">
                <button class="media-btn camera-btn active" id="cameraBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 7l-7 5 7 5V7z"/>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                    </svg>
                </button>
                <button class="media-btn mic-btn active" id="micBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                        <line x1="12" y1="19" x2="12" y2="23"/>
                        <line x1="8" y1="23" x2="16" y2="23"/>
                    </svg>
                </button>
                <button class="media-btn speaker-btn active" id="speakerBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Consultation Details -->
        <div class="consultation-details">
            <h3>Consultation Details</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Apt.Type:</strong>Follow-up Consultation
                </div>
                <div class="detail-item">
                    <strong>Purpose:</strong> Review treatment progress
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="precall-actions">
<a href="<?php echo URLROOT; ?>/Pages/patientVideoCall" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 7l-7 5 7 5V7z"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
                Join Call   
            </a>         
        </div>
    </div>
</div>

<script>
let isCameraOn = true;
let isMicOn = true;
let isSpeakerOn = true;

// Toggle camera
document.getElementById('cameraBtn').addEventListener('click', function() {
    isCameraOn = !isCameraOn;
    const btn = this;
    const span = btn.querySelector('span');
    
    if (isCameraOn) {
        btn.classList.add('active');
        span.textContent = 'Camera On';
    } else {
        btn.classList.remove('active');
        span.textContent = 'Camera Off';
    }
});

// Toggle microphone
document.getElementById('micBtn').addEventListener('click', function() {
    isMicOn = !isMicOn;
    const btn = this;
    const span = btn.querySelector('span');
    
    if (isMicOn) {
        btn.classList.add('active');
        span.textContent = 'Microphone On';
    } else {
        btn.classList.remove('active');
        span.textContent = 'Microphone Off';
    }
});

// Toggle speaker
document.getElementById('speakerBtn').addEventListener('click', function() {
    isSpeakerOn = !isSpeakerOn;
    const btn = this;
    const span = btn.querySelector('span');
    
    if (isSpeakerOn) {
        btn.classList.add('active');
        span.textContent = 'Speaker On';
    } else {
        btn.classList.remove('active');
        span.textContent = 'Speaker Off';
    }
});

// Start video call
function startVideoCall() {
    // Store media settings in session storage
    sessionStorage.setItem('cameraOn', isCameraOn);
    sessionStorage.setItem('micOn', isMicOn);
    sessionStorage.setItem('speakerOn', isSpeakerOn);
    
    // Redirect to video call page
    window.location.href = '<?php echo URLROOT; ?>/Pages/doctorVideoCall';
}
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

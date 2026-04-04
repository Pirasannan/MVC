<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorPrecall';
?>

<div class="precall-container">
    <div class="precall-content">
        <div class="precall-header">
            <button class="back-btn" onclick="window.location.href='<?php echo URLROOT; ?>/Appointments/doctor'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back
            </button>
            <h1>Preview</h1>
        </div>

        <div class="patient-info-card">
            <div class="patient-avatar">
            </div>
            <div class="patient-details">
                <h3><?php echo htmlspecialchars($data['patient_name'] ?? 'Patient'); ?></h3>
                <p class="appointment-time"><?php echo htmlspecialchars($data['appointment_time'] ?? ''); ?></p>
                <p class="appointment-type"><?php echo htmlspecialchars($data['appointment_type'] ?? 'Video Consultation'); ?></p>
            </div>
        </div>

        <div class="media-setup">
            <div class="media-preview">
                <div class="video-preview">
                    <video id="localPreview" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;"></video>
                </div>
            </div>

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
            </div>
        </div>

        <div class="consultation-details">
            <h3>Consultation Details</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Apt.Type:</strong> <?php echo htmlspecialchars($data['appointment_type'] ?? 'Video Consultation'); ?>
                </div>
                <div class="detail-item">
                    <strong>Purpose:</strong> <?php echo htmlspecialchars($data['appointment_type'] ?? 'Consultation'); ?>
                </div>
            </div>
        </div>

        <div class="precall-actions">
            <button class="btn-secondary" onclick="window.location.href='<?php echo URLROOT; ?>/Appointments/doctor'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Cancel
            </button>
            <button class="btn-primary" id="startCallBtn" onclick="startVideoCall()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 7l-7 5 7 5V7z"/>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
                Start Video Call
            </button>
        </div>
    </div>
</div>

<script>
let isCameraOn = true;
let isMicOn = true;
let localStream = null;

async function setupPreview() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        const video = document.getElementById('localPreview');
        video.srcObject = localStream;
    } catch (error) {
        alert('Camera/microphone permission is required to start the consultation.');
    }
}

function updateTrackState(kind, enabled) {
    if (!localStream) return;
    const tracks = kind === 'video' ? localStream.getVideoTracks() : localStream.getAudioTracks();
    tracks.forEach((track) => {
        track.enabled = enabled;
    });
}

document.getElementById('cameraBtn').addEventListener('click', function() {
    isCameraOn = !isCameraOn;
    this.classList.toggle('active', isCameraOn);
    updateTrackState('video', isCameraOn);
});

document.getElementById('micBtn').addEventListener('click', function() {
    isMicOn = !isMicOn;
    this.classList.toggle('active', isMicOn);
    updateTrackState('audio', isMicOn);
});

function startVideoCall() {
    sessionStorage.setItem('cameraOn', isCameraOn);
    sessionStorage.setItem('micOn', isMicOn);
    window.location.href = '<?php echo URLROOT; ?>/Pages/doctorVideoCall/<?php echo (int)($data['appointment_id'] ?? 0); ?>';
}

window.addEventListener('beforeunload', function() {
    if (!localStream) return;
    localStream.getTracks().forEach((track) => track.stop());
});

document.addEventListener('DOMContentLoaded', setupPreview);
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

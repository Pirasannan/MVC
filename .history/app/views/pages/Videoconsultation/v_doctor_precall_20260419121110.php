<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorPrecall';
$apt = $data['appointment'];
$appointmentId = (int)$apt->id;
$patientAvatarPath = trim((string)($apt->patient_profile_image ?? ''));
$patientAvatarPath = str_replace('\\', '/', $patientAvatarPath);
$patientAvatarPath = ltrim($patientAvatarPath, '/');
$defaultAvatarUrl = URLROOT . '/public/img/placeholder-user.jpg';
$patientAvatarUrl = $patientAvatarPath !== '' ? URLROOT . '/' . $patientAvatarPath : $defaultAvatarUrl;
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
                <img src="<?= htmlspecialchars($patientAvatarUrl, ENT_QUOTES, 'UTF-8') ?>"
                     alt="Patient profile picture"
                     onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultAvatarUrl, ENT_QUOTES, 'UTF-8') ?>';">
            </div>
            <div class="patient-details">
                <h3><?= htmlspecialchars($apt->patient_name) ?></h3>
                <p class="appointment-time"><?= date('D d M Y, H:i', strtotime($apt->starts_at)) ?></p>
                <p class="appointment-type"><?= htmlspecialchars($apt->reason ?? 'Consultation') ?></p>
            </div>
        </div>

        <!-- Media Preview -->
        <div class="media-setup">
            <div class="media-preview">
                <div class="video-preview">
                    <video id="localPreview" autoplay playsinline muted></video>
                    <div class="video-placeholder" id="previewPlaceholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 7l-7 5 7 5V7z"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        <span>Camera Preview</span>
                    </div>

                    <!-- Media Controls (overlay at bottom of preview) -->
                    <div class="media-controls">
                        <button class="media-btn camera-btn active" id="cameraBtn" type="button" aria-label="Camera On" title="Camera On">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 7l-7 5 7 5V7z"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        </button>
                        <button class="media-btn mic-btn active" id="micBtn" type="button" aria-label="Microphone On" title="Microphone On">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                <line x1="12" y1="19" x2="12" y2="23"/>
                                <line x1="8" y1="23" x2="16" y2="23"/>
                            </svg>
                        </button>
                        <button class="media-btn speaker-btn active" id="speakerBtn" type="button" aria-label="Speaker On" title="Speaker On">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="preview-status" id="previewStatus" aria-live="polite">Initializing camera preview...</p>
            </div>

            <div class="media-right-panel">
                <!-- Consultation Details -->
                <div class="consultation-details">
                    <h3>Consultation Details</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <strong>Patient:</strong> <?= htmlspecialchars($apt->patient_name) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Reason:</strong> <?= htmlspecialchars($apt->reason ?? '—') ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="precall-actions">
                    <button class="btn-secondary" onclick="history.back()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Cancel
                    </button>
                    <a class="join-consultation-btn" id="startCallBtn" href="<?= URLROOT ?>/VideoCall/room/<?= $appointmentId ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 7l-7 5 7 5V7z"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        Start Video Call
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isCameraOn = true;
let isMicOn = true;
let isSpeakerOn = true;
let localStream = null;

const cameraBtn = document.getElementById('cameraBtn');
const micBtn = document.getElementById('micBtn');
const speakerBtn = document.getElementById('speakerBtn');
const localPreview = document.getElementById('localPreview');
const previewPlaceholder = document.getElementById('previewPlaceholder');
const previewStatus = document.getElementById('previewStatus');
const startCallBtn = document.getElementById('startCallBtn');

function updateToggleButton(button, isOn, onLabel, offLabel) {
    button.classList.toggle('active', isOn);
    const stateLabel = isOn ? onLabel : offLabel;
    button.setAttribute('aria-label', stateLabel);
    button.setAttribute('title', stateLabel);
}

function setPreviewStatus(message, isError = false) {
    if (!previewStatus) {
        return;
    }

    previewStatus.textContent = message;
    previewStatus.classList.toggle('error', isError);
}

function showPlaceholder(labelText) {
    if (previewPlaceholder) {
        const label = previewPlaceholder.querySelector('span');
        if (label && labelText) {
            label.textContent = labelText;
        }
        previewPlaceholder.classList.remove('is-hidden');
    }

    if (localPreview) {
        localPreview.style.display = 'none';
    }
}

function hidePlaceholder() {
    if (previewPlaceholder) {
        previewPlaceholder.classList.add('is-hidden');
    }

    if (localPreview) {
        localPreview.style.display = 'block';
    }
}

function updateTrackState(kind, enabled) {
    if (!localStream) {
        return;
    }

    const tracks = kind === 'video' ? localStream.getVideoTracks() : localStream.getAudioTracks();
    tracks.forEach((track) => {
        track.enabled = enabled;
    });
}

function persistMediaState() {
    sessionStorage.setItem('cameraOn', isCameraOn ? '1' : '0');
    sessionStorage.setItem('micOn', isMicOn ? '1' : '0');
    sessionStorage.setItem('speakerOn', isSpeakerOn ? '1' : '0');
}

async function initializePreview() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showPlaceholder('Camera not supported');
        setPreviewStatus('This browser does not support camera preview.', true);
        return;
    }

    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });

        if (localPreview && localPreview.srcObject !== localStream) {
            localPreview.srcObject = localStream;
            await localPreview.play().catch(() => {});
        }

        hidePlaceholder();
        setPreviewStatus('Camera and microphone are ready.');
        updateTrackState('video', isCameraOn);
        updateTrackState('audio', isMicOn);
    } catch (error) {
        console.error('Precall media init failed:', error);
        showPlaceholder('Camera unavailable');
        setPreviewStatus('Could not access camera/microphone. You can still continue to the call.', true);
    }
}

// Toggle camera
cameraBtn.addEventListener('click', function() {
    isCameraOn = !isCameraOn;
    updateTrackState('video', isCameraOn);
    updateToggleButton(this, isCameraOn, 'Camera On', 'Camera Off');

    if (isCameraOn && localStream) {
        hidePlaceholder();
        setPreviewStatus('Camera is on.');
    } else {
        showPlaceholder('Camera Off');
        setPreviewStatus('Camera is off.');
    }
});

// Toggle microphone
micBtn.addEventListener('click', function() {
    isMicOn = !isMicOn;
    updateTrackState('audio', isMicOn);
    updateToggleButton(this, isMicOn, 'Microphone On', 'Microphone Off');
    setPreviewStatus(isMicOn ? 'Microphone is on.' : 'Microphone is off.');
});

// Toggle speaker
speakerBtn.addEventListener('click', function() {
    isSpeakerOn = !isSpeakerOn;
    updateToggleButton(this, isSpeakerOn, 'Speaker On', 'Speaker Off');

    if (localPreview) {
        // Keep local preview muted by default to prevent echo in most browsers.
        localPreview.muted = !isSpeakerOn;
    }

    setPreviewStatus(isSpeakerOn ? 'Speaker is on.' : 'Speaker is off.');
});

if (startCallBtn) {
    startCallBtn.addEventListener('click', persistMediaState);
}

window.addEventListener('beforeunload', function() {
    if (!localStream) {
        return;
    }

    localStream.getTracks().forEach((track) => track.stop());
});

updateToggleButton(cameraBtn, isCameraOn, 'Camera On', 'Camera Off');
updateToggleButton(micBtn, isMicOn, 'Microphone On', 'Microphone Off');
updateToggleButton(speakerBtn, isSpeakerOn, 'Speaker On', 'Speaker Off');

document.addEventListener('DOMContentLoaded', initializePreview);
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

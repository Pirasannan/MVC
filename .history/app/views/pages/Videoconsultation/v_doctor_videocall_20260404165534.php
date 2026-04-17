<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorVideoCall';
$apt = $data['appointment'];
?>

<div class="videocall-container">
    <!-- Top Bar -->
    <div class="videocall-topbar">
        <div class="call-info">
            <div class="participant-info">
                <div class="participant-details">
                    <h3><?= htmlspecialchars($apt->patient_name) ?></h3>
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
            <a class="btn-icon" href="<?php echo URLROOT; ?>/Pages/createprescription" target="_blank" title="Create Prescription">   
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                </a> 
        </div>
    </div>

    <!-- Main Video Area -->
    <div class="videocall-main">
        <!-- Remote video (patient) -->
        <div class="remote-video-container" id="remoteVideoWrapper">
            <video id="remote-video" autoplay playsinline style="width:100%;height:100%;object-fit:cover;"></video>
            <div class="video-overlay" id="remoteOverlay">
                <div class="participant-name"><?= htmlspecialchars($apt->patient_name) ?></div>
                <div class="connection-status" id="connectionStatus">Waiting for patient…</div>
            </div>
        </div>

        <!-- Local Video (Small) -->
        <div class="local-video-container">
            <video id="local-video" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;"></video>
            <div class="local-video-overlay">
                <div class="local-name">You</div>
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
            <button class="control-btn endcall-btn" id="endCallBtn" title="End Call">
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
                <div class="message-content">Hello Doctor, I'm ready for the consultation.</div>
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


    <!-- Consultation Notes Panel -->
    <div class="notes-panel" id="notesPanel">
        <div class="panel-header">
            <h4>Consultation Notes</h4>
            <button class="close-btn" onclick="toggleNotes()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="notes-content">
            <textarea id="notesTextarea" placeholder="Add consultation notes here..."></textarea>
            <div class="notes-actions">
                <button class="btn-secondary" onclick="saveNotes()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17,21 17,13 7,13 7,21"/>
                        <polyline points="7,3 7,8 15,8"/>
                    </svg>
                    Save Notes
                </button>
                <a class="btn-primary" href="<?php echo URLROOT; ?>/Pages/createprescription" target="_blank">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    Create Prescription
                </a> 
            </div>
        </div>
    </div>
</div>

<script type="module">
import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';

/* ── Stream credentials from PHP ── */
const API_KEY   = '<?= htmlspecialchars($data['stream_api_key'],  ENT_QUOTES) ?>';
const TOKEN     = '<?= htmlspecialchars($data['stream_token'],     ENT_QUOTES) ?>';
const CALL_ID   = '<?= htmlspecialchars($data['call_id'],          ENT_QUOTES) ?>';
const USER_ID   = '<?= htmlspecialchars($data['stream_user_id'],   ENT_QUOTES) ?>';
const USER_NAME = '<?= htmlspecialchars($data['stream_user_name'], ENT_QUOTES) ?>';
const BACK_URL  = '<?= URLROOT ?>/Appointments/doctor';

/* ── State ── */
let micEnabled    = true;
let cameraEnabled = true;
let callTimerRef  = null;
let streamCall    = null;
let streamClient  = null;
let participantSub = null;

/* ── Call timer ── */
function startCallTimer() {
    const start = Date.now();
    callTimerRef = setInterval(() => {
        const elapsed  = Date.now() - start;
        const minutes  = Math.floor(elapsed / 60000);
        const seconds  = Math.floor((elapsed % 60000) / 1000);
        document.getElementById('callTimer').textContent =
            String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0');
    }, 1000);
}

/* ── Bind a MediaStream to a <video> element ── */
function bindStream(videoEl, stream) {
    if (videoEl && stream && videoEl.srcObject !== stream) {
        videoEl.srcObject = stream;
        videoEl.play().catch(() => {});
    }
}

/* ── Initialise Stream Video ── */
async function init() {
    try {
        streamClient = new StreamVideoClient({
            apiKey: API_KEY,
            user:   { id: USER_ID, name: USER_NAME },
            token:  TOKEN,
        });

        streamCall = streamClient.call('<?= STREAM_CALL_TYPE ?>', CALL_ID);

        // Doctor creates the call room (idempotent – safe to call again)
        await streamCall.join({ create: true });

        await streamCall.camera.enable();
        await streamCall.microphone.enable();

        /* Local video */
        streamCall.camera.state.mediaStream$.subscribe(stream => {
            bindStream(document.getElementById('local-video'), stream);
        });

        /* Remote participants */
        const overlay = document.getElementById('remoteOverlay');
        const connStatus = document.getElementById('connectionStatus');

        participantSub = streamCall.state.remoteParticipants$.subscribe(participants => {
            const remoteEl = document.getElementById('remote-video');
            if (participants.length > 0) {
                overlay.style.display = 'none';
                const p = participants[0];
                if (p.videoStream) bindStream(remoteEl, p.videoStream);
                if (connStatus) connStatus.textContent = 'Connected';
            } else {
                overlay.style.display = '';
                if (connStatus) connStatus.textContent = 'Waiting for patient\u2026';
            }
        });

        startCallTimer();
        document.getElementById('callTimer').closest('.call-status') &&
            (document.getElementById('callTimer').closest('.call-duration').querySelector('.call-status').textContent = 'Live');

    } catch (err) {
        console.error('Stream Video init error:', err);
        alert('Could not connect to video call. Please try again.');
    }
}

/* ── Mic toggle ── */
document.getElementById('micBtn').addEventListener('click', async () => {
    if (!streamCall) return;
    await streamCall.microphone.toggle();
    micEnabled = !micEnabled;
    document.getElementById('micBtn').classList.toggle('active', micEnabled);
});

/* ── Camera toggle ── */
document.getElementById('cameraBtn').addEventListener('click', async () => {
    if (!streamCall) return;
    await streamCall.camera.toggle();
    cameraEnabled = !cameraEnabled;
    document.getElementById('cameraBtn').classList.toggle('active', cameraEnabled);
});

/* ── End call ── */
document.getElementById('endCallBtn').addEventListener('click', async () => {
    if (!confirm('End the call?')) return;
    if (callTimerRef) clearInterval(callTimerRef);
    if (participantSub) participantSub.unsubscribe();
    if (streamCall)   await streamCall.leave();
    if (streamClient) await streamClient.disconnectUser();
    window.location.href = BACK_URL;
});

/* ── Utility panels ── */
window.toggleChat  = () => document.getElementById('chatPanel').classList.toggle('active');
window.toggleNotes = () => document.getElementById('notesPanel').classList.toggle('active');
window.saveNotes   = () => alert('Notes saved!');

init();
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

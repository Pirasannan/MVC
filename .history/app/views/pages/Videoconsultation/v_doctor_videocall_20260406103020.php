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
            <button class="btn-icon" onclick="toggleParticipants()" title="Participants">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </button>
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

    <!-- Participants Panel -->
    <div class="participants-panel" id="participantsPanel">
        <div class="panel-header">
            <h4>Participants <span id="participantCount" style="font-size:14px;color:#64748b;font-weight:400;">(0)</span></h4>
            <button class="close-btn" onclick="toggleParticipants()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="participants-list" id="participantsList">
            <p style="color:#94a3b8;text-align:center;margin-top:20px;font-size:13px;">Connecting…</p>
        </div>
    </div>
</div>

<script type="module">
import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';
const TrackType = { VIDEO: 1, AUDIO: 2 };

/* ── Stream credentials from PHP ── */
const API_KEY   = '<?= htmlspecialchars($data['stream_api_key'],  ENT_QUOTES) ?>';
const TOKEN     = '<?= htmlspecialchars($data['stream_token'],     ENT_QUOTES) ?>';
const CALL_ID   = '<?= htmlspecialchars($data['call_id'],          ENT_QUOTES) ?>';
const USER_ID   = '<?= htmlspecialchars($data['stream_user_id'],   ENT_QUOTES) ?>';
const USER_NAME = '<?= htmlspecialchars($data['stream_user_name'], ENT_QUOTES) ?>';
const BACK_URL  = '<?= URLROOT ?>/Appointments/doctor';

/* ── State ── */
let callTimerRef   = null;
let streamCall     = null;
let streamClient   = null;
let participantSub = null;

/*
 * The Stream Video SDK requires call.bindVideoElement() / call.bindAudioElement()
 * to manage WebRTC track subscriptions internally. Manually setting srcObject
 * against participant.videoStream is insufficient — the SDK must wire up the
 * track negotiation itself.
 *
 * Each bind call returns an unbind() function that must be called on cleanup.
 */
const videoBindings = new Map(); // sessionId → unbind()
const audioBindings = new Map(); // sessionId → unbind()

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

/* ── Render participants sidebar ── */
function renderParticipantsSidebar(participants) {
    const list    = document.getElementById('participantsList');
    const countEl = document.getElementById('participantCount');
    if (!list) return;
    countEl.textContent = `(${participants.length})`;
    list.innerHTML = '';
    participants.forEach(p => {
        const hasVideo = Array.isArray(p.publishedTracks) && p.publishedTracks.includes(TrackType.VIDEO);
        const hasAudio = Array.isArray(p.publishedTracks) && p.publishedTracks.includes(TrackType.AUDIO);
        const isLocal  = !!p.isLocalParticipant;
        const dispName = (p.name || p.userId || 'Unknown') + (isLocal ? ' (You)' : '');
        const initials = (p.name || '?').slice(0, 2).toUpperCase();
        const trackList = (p.publishedTracks || []).join(', ') || '—';
        const card = document.createElement('div');
        card.className = 'ptcpt-card';
        card.innerHTML = `
            <div class="ptcpt-avatar${isLocal ? ' local' : ''}">${initials}</div>
            <div class="ptcpt-body">
                <div class="ptcpt-name">${dispName}</div>
                <div class="ptcpt-icons">
                    <span class="ptcpt-icon ${hasAudio ? 'on' : 'off'}">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            ${hasAudio
                                ? '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>'
                                : '<line x1="1" y1="1" x2="23" y2="23"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>'
                            }
                        </svg>
                        ${hasAudio ? 'Mic on' : 'Muted'}
                    </span>
                    <span class="ptcpt-icon ${hasVideo ? 'on' : 'off'}">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            ${hasVideo
                                ? '<path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>'
                                : '<path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"/><line x1="1" y1="1" x2="23" y2="23"/>'
                            }
                        </svg>
                        ${hasVideo ? 'Cam on' : 'Cam off'}
                    </span>
                </div>
                <div class="ptcpt-debug">tracks:[${trackList}] | bound-video:${videoBindings.has(p.sessionId) ? 'YES' : 'NO'} | bound-audio:${audioBindings.has(p.sessionId) ? 'YES' : 'NO'}</div>
            </div>`;
        list.appendChild(card);
    });
}

/* ── Bind / unbind a remote participant using the SDK methods ── */
function bindRemoteParticipant(participant) {
    const sid    = participant.sessionId;
    const remoteVideoEl = document.getElementById('remote-video');

    // Video
    if (!videoBindings.has(sid)) {
        const unbind = streamCall.bindVideoElement(remoteVideoEl, sid, 'videoTrack');
        videoBindings.set(sid, unbind ?? null);
    }

    // Audio — must be a separate <audio> element (not the <video> element)
    if (!audioBindings.has(sid)) {
        let audioEl = document.getElementById(`audio-${sid}`);
        if (!audioEl) {
            audioEl = document.createElement('audio');
            audioEl.id        = `audio-${sid}`;
            audioEl.autoplay  = true;
            document.body.appendChild(audioEl);
        }
        const unbind = streamCall.bindAudioElement(audioEl, sid);
        audioBindings.set(sid, unbind ?? null);
    }
}

function unbindRemoteParticipant(sessionId) {
    const unbindVideo = videoBindings.get(sessionId);
    if (typeof unbindVideo === 'function') unbindVideo();
    videoBindings.delete(sessionId);

    const unbindAudio = audioBindings.get(sessionId);
    if (typeof unbindAudio === 'function') unbindAudio();
    audioBindings.delete(sessionId);

    const audioEl = document.getElementById(`audio-${sessionId}`);
    if (audioEl) audioEl.remove();
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

        // Doctor creates the call room (idempotent — safe to call again)
        await streamCall.join({ create: true });

        await streamCall.camera.enable();
        await streamCall.microphone.enable();

        /* Local video — camera.state.mediaStream$ is always correct for local preview */
        streamCall.camera.state.mediaStream$.subscribe(stream => {
            const localEl = document.getElementById('local-video');
            if (localEl && stream && localEl.srcObject !== stream) {
                localEl.srcObject = stream;
                localEl.play().catch(() => {});
            }
        });

        /* ── Button state driven by SDK status$ (no manual bool tracking) ── */
        streamCall.microphone.state.status$.subscribe(status => {
            const on  = status === 'enabled';
            const btn = document.getElementById('micBtn');
            btn.classList.toggle('active', on);
            btn.querySelector('svg').innerHTML = on
                ? '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>'
                : '<line x1="1" y1="1" x2="23" y2="23"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>';
            // Force sidebar refresh so local participant's mic badge updates
            renderParticipantsSidebar(streamCall.state.participants);
        });

        streamCall.camera.state.status$.subscribe(status => {
            const on  = status === 'enabled';
            const btn = document.getElementById('cameraBtn');
            btn.classList.toggle('active', on);
            btn.querySelector('svg').innerHTML = on
                ? '<path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>'
                : '<path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"/><line x1="1" y1="1" x2="23" y2="23"/>';
            // Force sidebar refresh so local participant's cam badge updates
            renderParticipantsSidebar(streamCall.state.participants);
        });

        /* All participants → sidebar + remote binding */
        const overlay    = document.getElementById('remoteOverlay');
        const connStatus = document.getElementById('connectionStatus');

        participantSub = streamCall.state.participants$.subscribe(participants => {
            renderParticipantsSidebar(participants);

            const remoteParticipants = participants.filter(p => !p.isLocalParticipant);

            // Bind any new remote participants
            remoteParticipants.forEach(p => bindRemoteParticipant(p));

            // Unbind participants who have left
            const currentIds = new Set(remoteParticipants.map(p => p.sessionId));
            [...videoBindings.keys()].forEach(sid => {
                if (!currentIds.has(sid)) unbindRemoteParticipant(sid);
            });

            if (remoteParticipants.length > 0) {
                overlay.style.display = 'none';
                if (connStatus) connStatus.textContent = 'Connected';
            } else {
                overlay.style.display = '';
                if (connStatus) connStatus.textContent = 'Waiting for patient\u2026';
                const remoteEl = document.getElementById('remote-video');
                if (remoteEl) remoteEl.srcObject = null;
            }
        });

        startCallTimer();

    } catch (err) {
        console.error('Stream Video init error:', err);
        alert('Could not connect to video call. Please try again.');
    }
}

/* ── Mic toggle ── */
document.getElementById('micBtn').addEventListener('click', () => {
    streamCall?.microphone.toggle();
});

/* ── Camera toggle ── */
document.getElementById('cameraBtn').addEventListener('click', () => {
    streamCall?.camera.toggle();
});

/* ── End call ── */
document.getElementById('endCallBtn').addEventListener('click', async () => {
    if (!confirm('End the call?')) return;
    if (callTimerRef)   clearInterval(callTimerRef);
    if (participantSub) participantSub.unsubscribe();
    // Unbind all remote participants
    [...videoBindings.keys()].forEach(sid => unbindRemoteParticipant(sid));
    if (streamCall)     await streamCall.leave();
    if (streamClient)   await streamClient.disconnectUser();
    window.location.href = BACK_URL;
});

/* ── Utility panels ── */
window.toggleChat         = () => document.getElementById('chatPanel').classList.toggle('active');
window.toggleNotes        = () => document.getElementById('notesPanel').classList.toggle('active');
window.toggleParticipants = () => document.getElementById('participantsPanel').classList.toggle('active');
window.saveNotes          = () => alert('Notes saved!');

init();
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

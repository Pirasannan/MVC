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
            <button class="btn-icon" onclick="togglePrescription()" title="Create Prescription">   
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
            </button>
            <button class="btn-icon" onclick="toggleParticipants()" title="Participants">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </button>
            <button class="btn-icon btn-icon-report" type="button" onclick="openCallReportModal()" title="Report Call">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4v16"/>
                    <path d="M4 4h11l-1 3 1 3H4"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Video Area -->
    <div class="videocall-main">
        <!-- Remote video (patient) -->
        <div class="remote-video-container" id="remoteVideoWrapper">
            <video id="remote-video" autoplay playsinline muted style="width:100%;height:100%;object-fit:contain;"></video>
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
                <button class="btn-primary" onclick="togglePrescription()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    Create Prescription
                </button> 
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

    <!-- Prescription Drawer -->
    <div class="prescription-drawer" id="prescriptionDrawer">
        <div class="prescription-drawer__header">
            <h3>Create Prescription</h3>
            <button class="prescription-drawer__close" onclick="togglePrescription()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="prescription-drawer__content">
            <div class="incall-pres-container">
                <form id="incallPrescriptionForm" method="POST" action="<?php echo URLROOT; ?>/Doctor/addPrescription">
                    <input type="hidden" name="patient_id" value="<?= $apt->patient_id ?>">
                    
                    <div class="section">
                        <div class="section-header">1. Medication Details</div>
                        <div class="form-group">
                            <label>Patient:</label>
                            <input type="text" value="<?= htmlspecialchars($apt->patient_name) ?>" disabled style="background: #f1f5f9;">
                        </div>

                        <div class="form-group">
                            <label for="drugName" class="required">Drug Name / Product</label>
                            <div class="autocomplete-container">
                                <input type="text" id="drugName" name="drug_name" autocomplete="off" placeholder="Start typing drug name..." required>
                                <div id="drugList" class="autocomplete-list hidden"></div>
                            </div>
                            <span class="error" id="drugNameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="formulation">Formulation / Strength</label>
                            <input type="text" id="formulation" name="formulation" placeholder="Optional">
                        </div>

                        <div class="form-group">
                            <label for="route" class="required">Route of Administration</label>
                            <select id="route" name="route" required>
                                <option value="">Select route</option>
                                <option value="Oral">Oral</option>
                                <option value="Intravenous">Intravenous</option>
                                <option value="Intramuscular">Intramuscular</option>
                                <option value="Subcutaneous">Subcutaneous</option>
                                <option value="Topical">Topical</option>
                                <option value="Ophthalmic">Ophthalmic</option>
                                <option value="Inhalation">Inhalation</option>
                                <option value="Rectal">Rectal</option>
                                <option value="Sublingual">Sublingual</option>
                            </select>
                            <span class="error" id="routeError"></span>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">2. Dosage Instructions</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="doseAmount" class="required">Dose Amount</label>
                                <input type="text" id="doseAmount" name="dose_amount" placeholder="e.g., 1" required>
                                <span class="error" id="doseAmountError"></span>
                            </div>
                            <div class="form-group">
                                <label for="doseUnit" class="required">Dose Unit</label>
                                <select id="doseUnit" name="dose_unit" required>
                                    <option value="">Select unit</option>
                                    <option value="mg">mg</option>
                                    <option value="mL">mL</option>
                                    <option value="IU">IU</option>
                                    <option value="tablet">tablet</option>
                                    <option value="capsule">capsule</option>
                                    <option value="drops">drops</option>
                                </select>
                                <span class="error" id="doseUnitError"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frequency" class="required">Dose Frequency</label>
                            <select id="frequency" name="frequency" required>
                                <option value="">Select frequency</option>
                                <option value="OD">OD (Once daily)</option>
                                <option value="BD">BD (Twice daily)</option>
                                <option value="TDS">TDS (Three times daily)</option>
                                <option value="QID">QID (Four times daily)</option>
                                <option value="Q6H">Q6H (Every 6 hours)</option>
                                <option value="Q8H">Q8H (Every 8 hours)</option>
                                <option value="custom">Every X hours</option>
                                <option value="PRN">PRN (As needed)</option>
                            </select>
                            <span class="error" id="frequencyError"></span>
                        </div>

                        <div id="customFrequencyField" class="hidden">
                            <div class="form-group">
                                <label for="customFrequency">Custom Frequency Interval (hours)</label>
                                <input type="number" id="customFrequency" name="custom_frequency" min="1" max="24" placeholder="e.g., 4">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="timeOfDay">Time(s) of Day</label>
                            <input type="text" id="timeOfDay" name="time_of_day" placeholder="e.g., 9:00 AM, 9:00 PM">
                        </div>

                        <div class="form-group">
                            <label for="mealRelation">Relation to Meals</label>
                            <select id="mealRelation" name="meal_relation">
                                <option value="Irrelevant">Irrelevant</option>
                                <option value="Before meal">Before meal</option>
                                <option value="With meal">With meal</option>
                                <option value="After meal">After meal</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="durationValue">Duration</label>
                                <input type="number" id="durationValue" name="duration_value" min="1" placeholder="e.g., 7">
                                <span class="error" id="durationError"></span>
                            </div>
                            <div class="form-group">
                                <label for="durationType">Duration Unit</label>
                                <select id="durationType" name="duration_type">
                                    <option value="Days">Days</option>
                                    <option value="Weeks">Weeks</option>
                                    <option value="Months">Months</option>
                                    <option value="Until stopped">Until stopped</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="specialInstructions">Special Instructions</label>
                            <textarea id="specialInstructions" name="special_instructions" rows="2" placeholder="e.g., Do not crush, Take with food"></textarea>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">3. Diagnosis / Indication</div>
                        <div class="form-group">
                            <label for="diagnosis" class="required">Diagnosis / Indication</label>
                            <input type="text" id="diagnosis" name="diagnosis" placeholder="Enter diagnosis" required>
                            <span class="error" id="diagnosisError"></span>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">4. Validity & Notes</div>
                        <div class="form-group">
                            <label for="validUntil">Valid Until</label>
                            <input type="date" id="validUntil" name="valid_until">
                        </div>
                        <div class="form-group">
                            <label for="pharmacyNote">Note to Pharmacy</label>
                            <textarea id="pharmacyNote" name="pharmacy_note" rows="2" placeholder="Optional"></textarea>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-header">5. Doctor Notes</div>
                        <div class="form-group">
                            <label for="doctorNotes">Notes to Patient</label>
                            <textarea id="doctorNotes" name="doctor_notes" rows="2" placeholder="Instructions for the patient"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Confirm Prescription</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="report-modal" id="reportModal" style="display:none;">
    <div class="report-modal__content">
        <h4 id="reportModalTitle">Report Call</h4>
        <form id="reportForm">
            <input type="hidden" name="appointment_id" value="<?= (int)$apt->id ?>">
            <input type="hidden" name="report_scope" id="reportScope" value="call">
            <input type="hidden" name="report_context" value="in_call">
            <input type="hidden" name="reported_user_id" id="reportedUserId" value="">

            <label for="reportReason">Reason</label>
            <select id="reportReason" name="reason" required>
                <option value="">Select a reason</option>
            </select>

            <label for="reportDescription">Description (optional)</label>
            <textarea id="reportDescription" name="description" rows="3" placeholder="Add details"></textarea>

            <div class="report-modal__actions">
                <button type="submit" class="btn btn-warning">Send Report</button>
                <button type="button" class="btn btn-light" onclick="closeReportModal()">Close</button>
            </div>
        </form>
    </div>
</div>

<style>
.btn-icon-report {
    color: #fbbf24;
}

.report-modal {
    position: fixed;
    inset: 0;
    z-index: 1200;
    background: rgba(2, 6, 23, 0.55);
    align-items: center;
    justify-content: center;
}

.report-modal__content {
    width: min(520px, calc(100% - 24px));
    background: #fff;
    border-radius: 12px;
    padding: 18px;
}

.report-modal__content label {
    display: block;
    margin-top: 12px;
    margin-bottom: 6px;
    color: #334155;
    font-weight: 600;
}

.report-modal__content select,
.report-modal__content textarea {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px;
    font: inherit;
}

.report-modal__actions {
    display: flex;
    gap: 10px;
    margin-top: 14px;
}

.ptcpt-actions {
    margin-top: 8px;
}

.ptcpt-report-btn {
    border: 1px solid #f59e0b;
    background: #fffbeb;
    color: #92400e;
    border-radius: 999px;
    font-size: 11px;
    padding: 4px 10px;
    cursor: pointer;
}
</style>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/video_consultation/incall_prescription.css">
<script src="<?php echo URLROOT; ?>/public/js/incall-pres.js"></script>

<script type="module">
import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';
const TrackType = { UNSPECIFIED: 0, AUDIO: 1, VIDEO: 2, SCREEN_SHARE: 3 };

/* ── Stream credentials from PHP ── */
const API_KEY   = '<?= htmlspecialchars($data['stream_api_key'],  ENT_QUOTES) ?>';
const TOKEN     = '<?= htmlspecialchars($data['stream_token'],     ENT_QUOTES) ?>';
const CALL_ID   = '<?= htmlspecialchars($data['call_id'],          ENT_QUOTES) ?>';
const USER_ID   = '<?= htmlspecialchars($data['stream_user_id'],   ENT_QUOTES) ?>';
const USER_NAME = '<?= htmlspecialchars($data['stream_user_name'], ENT_QUOTES) ?>';
const BACK_URL  = '<?= URLROOT ?>/Appointments/doctor';
const REPORT_ENDPOINT = '<?= URLROOT ?>/Appointments/submitReport';

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

function parseStreamUserId(streamUserId) {
    const match = /^(doctor|patient)_(\d+)$/i.exec(streamUserId || '');
    if (!match) return null;
    return {
        role: match[1].toLowerCase(),
        id: parseInt(match[2], 10),
    };
}

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

        const parsedParticipant = parseStreamUserId(p.userId || '');
        if (!isLocal && parsedParticipant && parsedParticipant.id > 0) {
            const body = card.querySelector('.ptcpt-body');
            const actions = document.createElement('div');
            actions.className = 'ptcpt-actions';

            const reportBtn = document.createElement('button');
            reportBtn.type = 'button';
            reportBtn.className = 'ptcpt-report-btn';
            reportBtn.textContent = 'Report user';
            reportBtn.addEventListener('click', () => openUserReportModal(parsedParticipant.id));

            actions.appendChild(reportBtn);
            body.appendChild(actions);
        }

        list.appendChild(card);
    });
}

/* ── Bind / unbind a remote participant using the SDK methods ── */
function bindRemoteParticipant(participant) {
    const sid    = participant.sessionId;
    const remoteVideoEl = document.getElementById('remote-video');

    // Video — only skip if we already hold a valid unbind function.
    // Map.has() returns true even for null values, so use typeof check to
    // allow retrying when the first call returned null (track not yet published).
    if (typeof videoBindings.get(sid) !== 'function') {
        const unbind = streamCall.bindVideoElement(remoteVideoEl, sid, 'videoTrack');
        if (typeof unbind === 'function') {
            videoBindings.set(sid, unbind);
        }
    }

    // Audio — same retry guard
    if (typeof audioBindings.get(sid) !== 'function') {
        let audioEl = document.getElementById(`audio-${sid}`);
        if (!audioEl) {
            audioEl = document.createElement('audio');
            audioEl.id        = `audio-${sid}`;
            audioEl.autoplay  = true;
            document.body.appendChild(audioEl);
        }
        const unbind = streamCall.bindAudioElement(audioEl, sid);
        if (typeof unbind === 'function') {
            audioBindings.set(sid, unbind);
        }
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
window.toggleChat         = () => {
    document.getElementById('prescriptionDrawer').classList.remove('active');
    document.getElementById('chatPanel').classList.toggle('active');
};
window.toggleNotes        = () => {
    document.getElementById('prescriptionDrawer').classList.remove('active');
    document.getElementById('notesPanel').classList.toggle('active');
};
window.toggleParticipants = () => {
    document.getElementById('prescriptionDrawer').classList.remove('active');
    document.getElementById('participantsPanel').classList.toggle('active');
};
window.togglePrescription = () => {
    document.getElementById('chatPanel').classList.remove('active');
    document.getElementById('notesPanel').classList.remove('active');
    document.getElementById('participantsPanel').classList.remove('active');
    document.getElementById('prescriptionDrawer').classList.toggle('active');
};
window.saveNotes          = () => alert('Notes saved!');

const reportModal = document.getElementById('reportModal');
const reportForm = document.getElementById('reportForm');
const reportScopeInput = document.getElementById('reportScope');
const reportedUserInput = document.getElementById('reportedUserId');
const reportModalTitle = document.getElementById('reportModalTitle');
const reportReasonSelect = document.getElementById('reportReason');

const USER_REPORT_REASONS = [
    'Abuse or harassment',
    'Fraud or scam',
    'No-show / missed appointment',
    'Inappropriate behavior',
    'Fake profile',
];

const CALL_REPORT_REASONS = [
    'Abusive or offensive communication',
    'Spam or unwanted call',
    'Technical issues (poor audio/video)',
    'Disruptive behavior during call',
    "Call didn't follow agreed purpose",
    'Other',
];

function setReportReasonOptions(reportScope) {
    const options = reportScope === 'user' ? USER_REPORT_REASONS : CALL_REPORT_REASONS;
    reportReasonSelect.innerHTML = '<option value="">Select a reason</option>';
    options.forEach((label) => {
        const option = document.createElement('option');
        option.value = label;
        option.textContent = label;
        reportReasonSelect.appendChild(option);
    });
}

function openCallReportModal() {
    reportScopeInput.value = 'call';
    reportedUserInput.value = '';
    reportModalTitle.textContent = 'Report Call';
    setReportReasonOptions('call');
    reportModal.style.display = 'flex';
}

function openUserReportModal(reportedUserId) {
    reportScopeInput.value = 'user';
    reportedUserInput.value = String(reportedUserId || '');
    reportModalTitle.textContent = 'Report User';
    setReportReasonOptions('user');
    reportModal.style.display = 'flex';
}

function closeReportModal() {
    reportForm.reset();
    reportScopeInput.value = 'call';
    reportedUserInput.value = '';
    reportModal.style.display = 'none';
}

window.openCallReportModal = openCallReportModal;
window.openUserReportModal = openUserReportModal;
window.closeReportModal = closeReportModal;

setReportReasonOptions('call');

reportForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const payload = new URLSearchParams(new FormData(reportForm));

    try {
        const response = await fetch(REPORT_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: payload.toString(),
        });

        const result = await response.json();
        if (!response.ok || !result.success) {
            alert(result.message || 'Could not submit report.');
            return;
        }

        alert(result.message || 'Report submitted.');
        closeReportModal();
    } catch (error) {
        console.error('Report submission failed', error);
        alert('Could not submit report. Please try again.');
    }
});

init();
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

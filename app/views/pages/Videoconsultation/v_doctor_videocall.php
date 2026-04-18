<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'doctorVideoCall';
$apt = $data['appointment'] ?? null;
$apt_patient_id = $apt ? ($apt->patient_id ?? null) : null;
$patient_name = $apt ? ($apt->patient_name ?? '') : '';
?>

<!-- Load the same stylesheet used by the standalone create-prescription page -->
<link rel="stylesheet"
    href="<?php echo URLROOT; ?>/public/css/components/form/e_pres.css?v=<?php echo filemtime(APPROOT . '/../public/css/components/form/e_pres.css'); ?>">

<div class="videocall-container">
    <!-- Top Bar -->
    <div class="videocall-topbar">
        <div class="call-info">
            <div class="participant-info">
                <div class="participant-details">
                    <h3><?= htmlspecialchars($patient_name) ?></h3>
                </div>
            </div>
            <div class="call-duration">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12,6 12,12 16,14" />
                </svg>
                <span id="callTimer">00:00</span>
                <span class="call-status">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 12l2 2 4-4" />
                    </svg>
                    Connected
                </span>
            </div>
        </div>
        <div class="topbar-actions">
            <button class="btn-icon" id="openPrescriptionBtn" title="Create Prescription"
                onclick="openPrescriptionModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14,2 14,8 20,8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" />
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
                <div class="participant-name"><?= htmlspecialchars($patient_name) ?></div>
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
                    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                    <line x1="12" y1="19" x2="12" y2="23" />
                    <line x1="8" y1="23" x2="16" y2="23" />
                </svg>
            </button>
            <button class="control-btn camera-btn active" id="cameraBtn" title="Camera">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 7l-7 5 7 5V7z" />
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                </svg>
            </button>
        </div>

        <div class="control-group">
            <button class="control-btn endcall-btn" id="endCallBtn" title="End Call">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91" />
                    <line x1="23" y1="1" x2="1" y2="23" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Consultation Notes Panel -->
    <div class="notes-panel" id="notesPanel">
        <div class="panel-header">
            <h4>Consultation Notes</h4>
            <button class="close-btn" onclick="toggleNotes()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <div class="notes-content">
            <textarea id="notesTextarea" placeholder="Add consultation notes here..."></textarea>
            <div class="notes-actions">
                <button class="btn-secondary" onclick="saveNotes()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17,21 17,13 7,13 7,21" />
                        <polyline points="7,3 7,8 15,8" />
                    </svg>
                    Save Notes
                </button>
                <button class="btn-primary" onclick="openPrescriptionModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    Create Prescription
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     PRESCRIPTION MODAL
     Uses the exact same CSS classes as v_doctor_create_prescription.php
     so the form looks 100% identical to the standalone page.
═══════════════════════════════════════════════════════════════ -->
<div id="prescriptionModal" class="rx-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="rxModalTitle">
    <div class="rx-modal-box">

        <!-- Header – matches the standalone page's .header style -->
        <div class="rx-modal-header">
            <h1 id="rxModalTitle">E-Prescription</h1>
            <button class="rx-header-close" onclick="closePrescriptionModal()" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>

        <!-- Scrollable body – uses the SAME e_pres.css class names -->
        <div class="rx-modal-body">

            <!-- Wrap in e_prescription_container so e_pres.css spacing applies -->
            <div class="e_prescription_container" style="box-shadow:none;padding:0;margin:0;max-width:100%;">

                <form id="prescriptionModalForm">

                    <!-- Hidden: patient auto-set from the current video call -->
                    <input type="hidden" name="patient_id" id="rx_patient_id"
                        value="<?php echo (int) ($apt_patient_id ?? 0); ?>">

                    <!-- Patient display (read-only) -->
                    <div class="section">
                        <div class="section-header">Patient</div>
                        <div class="form-group">
                            <label>Prescribing for</label>
                            <input type="text" value="<?php echo htmlspecialchars($patient_name); ?>" readonly
                                style="background:#f5f7fa;cursor:default;color:#555;">
                        </div>
                    </div>

                    <!-- SECTION 1: Medication Details -->
                    <div class="section">
                        <div class="section-header">1. Medication Details</div>

                        <div class="form-group">
                            <label for="rx_drugName" class="required">Drug Name / Product</label>
                            <div class="autocomplete-container">
                                <input type="text" id="rx_drugName" name="drug_name" autocomplete="off"
                                    placeholder="Start typing drug name..." required>
                                <div id="rx_drugList" class="autocomplete-list hidden"></div>
                            </div>
                            <span class="error" id="rx_drugNameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="rx_formulation">Formulation / Strength</label>
                            <input type="text" id="rx_formulation" name="formulation" placeholder="Optional">
                        </div>

                        <div class="form-group">
                            <label for="rx_route" class="required">Route of Administration</label>
                            <select id="rx_route" name="route" required>
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
                            <span class="error" id="rx_routeError"></span>
                        </div>
                    </div>

                    <!-- SECTION 2: Dosage Instructions -->
                    <div class="section">
                        <div class="section-header">2. Dosage Instructions</div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="rx_doseAmount" class="required">Dose Amount</label>
                                <input type="text" id="rx_doseAmount" name="dose_amount" placeholder="e.g., 1" required>
                                <span class="error" id="rx_doseAmountError"></span>
                            </div>
                            <div class="form-group">
                                <label for="rx_doseUnit" class="required">Dose Unit</label>
                                <select id="rx_doseUnit" name="dose_unit" required>
                                    <option value="">Select unit</option>
                                    <option value="mg">mg</option>
                                    <option value="mL">mL</option>
                                    <option value="IU">IU</option>
                                    <option value="tablet">tablet</option>
                                    <option value="capsule">capsule</option>
                                    <option value="drops">drops</option>
                                </select>
                                <span class="error" id="rx_doseUnitError"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rx_frequency" class="required">Dose Frequency</label>
                            <select id="rx_frequency" name="frequency" required>
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
                            <span class="error" id="rx_frequencyError"></span>
                        </div>

                        <div id="rx_customFrequencyField" class="hidden">
                            <div class="form-group">
                                <label for="rx_customFrequency">Custom Frequency Interval (hours)</label>
                                <input type="number" id="rx_customFrequency" name="custom_frequency" min="1" max="24"
                                    placeholder="e.g., 4">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rx_timeOfDay">Time(s) of Day</label>
                            <input type="text" id="rx_timeOfDay" name="time_of_day"
                                placeholder="e.g., 9:00 AM, 9:00 PM">
                        </div>

                        <div class="form-group">
                            <label for="rx_mealRelation">Relation to Meals</label>
                            <select id="rx_mealRelation" name="meal_relation">
                                <option value="Irrelevant">Irrelevant</option>
                                <option value="Before meal">Before meal</option>
                                <option value="With meal">With meal</option>
                                <option value="After meal">After meal</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="rx_durationValue">Duration</label>
                                <input type="number" id="rx_durationValue" name="duration_value" min="1"
                                    placeholder="e.g., 7">
                                <span class="error" id="rx_durationError"></span>
                            </div>
                            <div class="form-group">
                                <label for="rx_durationType">Duration Unit</label>
                                <select id="rx_durationType" name="duration_type">
                                    <option value="Days">Days</option>
                                    <option value="Weeks">Weeks</option>
                                    <option value="Months">Months</option>
                                    <option value="Until stopped">Until stopped</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rx_specialInstructions">Special Instructions</label>
                            <textarea id="rx_specialInstructions" name="special_instructions"
                                placeholder="e.g., Do not crush, Take with food"></textarea>
                        </div>
                    </div>

                    <!-- SECTION 4: Diagnosis / Indication -->
                    <div class="section">
                        <div class="section-header">4. Diagnosis / Indication</div>
                        <div class="form-group">
                            <label for="rx_diagnosis" class="required">Diagnosis / Indication for Use</label>
                            <input type="text" id="rx_diagnosis" name="diagnosis"
                                placeholder="Enter diagnosis or indication" required>
                            <span class="error" id="rx_diagnosisError"></span>
                        </div>
                    </div>

                    <!-- SECTION 5: Validity -->
                    <div class="section">
                        <div class="section-header">5. Validity</div>

                        <div class="form-group">
                            <label for="rx_validUntil">Valid Until</label>
                            <input type="date" id="rx_validUntil" name="valid_until">
                        </div>

                        <div class="form-group">
                            <label for="rx_pharmacyNote">Note to Pharmacy</label>
                            <textarea id="rx_pharmacyNote" name="pharmacy_note"
                                placeholder="Optional communication from doctor to pharmacist"></textarea>
                        </div>
                    </div>

                    <!-- SECTION 6: Review & Confirm -->
                    <div class="section">
                        <div class="section-header">6. Review &amp; Confirm</div>
                        <div class="form-group">
                            <label for="rx_doctorNotes">Doctor Notes to Patient</label>
                            <textarea id="rx_doctorNotes" name="doctor_notes"
                                placeholder="Instructions for the patient"></textarea>
                        </div>
                    </div>

                    <!-- Footer buttons – same classes as standalone page -->
                    <div class="footer-buttons">
                        <button type="submit" class="btn btn-primary" id="rx_submitBtn">
                            Confirm Prescription
                        </button>
                        <div class="single_acc_link">
                            <a class="goback" href="#" onclick="closePrescriptionModal(); return false;">
                                Cancel
                            </a>
                        </div>
                    </div>

                </form>
            </div><!-- /.e_prescription_container -->
        </div><!-- /.rx-modal-body -->
    </div><!-- /.rx-modal-box -->
</div><!-- /#prescriptionModal -->

<!-- ── "Prescription Sent" success toast ── -->
<div id="rxSuccessToast" class="rx-toast" role="status" aria-live="polite">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <circle cx="12" cy="12" r="10" />
        <path d="M8 12l2 2 4-4" />
    </svg>
    <span>Prescription sent successfully!</span>
</div>

<!-- ── Modal shell + toast CSS (form fields are styled by e_pres.css) ── -->
<style>
    /* Overlay backdrop */
    .rx-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.60);
        backdrop-filter: blur(4px);
        align-items: flex-start;
        justify-content: center;
        padding: 24px 16px 48px;
        overflow-y: auto;
    }

    .rx-modal-overlay.active {
        display: flex;
        animation: rxFadeIn 0.22s ease;
    }

    @keyframes rxFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* ── Box ── */
    .rx-modal-box {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
        animation: rxSlideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }

    @keyframes rxSlideUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* ── Header ── */
    .rx-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: linear-gradient(135deg, #1e3a5f 0%, #1a56db 100%);
        color: white;
        flex-shrink: 0;
    }

    .rx-modal-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rx-modal-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: white;
    }

    .rx-close-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    .rx-close-btn:hover {
        background: rgba(255, 255, 255, 0.28);
    }

    /* ── Body / scroll region ── */
    .rx-modal-body {
        overflow-y: auto;
        padding: 24px;
        flex: 1;
    }

    /* ── Sections ── */
    .rx-section {
        margin-bottom: 20px;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
    }

    .rx-section-header {
        font-size: 14px;
        font-weight: 700;
        color: #1e3a5f;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #3b82f6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ── Form groups ── */
    .rx-form-group {
        margin-bottom: 14px;
    }

    .rx-form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .rx-form-group label.required::after {
        content: " *";
        color: #ef4444;
    }

    .rx-form-group input[type="text"],
    .rx-form-group input[type="number"],
    .rx-form-group input[type="date"],
    .rx-form-group select,
    .rx-form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        font-family: inherit;
        background: #fff;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .rx-form-group input:focus,
    .rx-form-group select:focus,
    .rx-form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .rx-form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .rx-input-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }

    .rx-error {
        display: block;
        font-size: 11.5px;
        color: #ef4444;
        margin-top: 4px;
        min-height: 14px;
    }

    /* ── Two-column row ── */
    .rx-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* ── Autocomplete ── */
    .rx-autocomplete-container {
        position: relative;
    }

    .rx-autocomplete-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1.5px solid #3b82f6;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 180px;
        overflow-y: auto;
        z-index: 10000;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .rx-autocomplete-item {
        padding: 10px 12px;
        cursor: pointer;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        transition: background 0.15s;
    }

    .rx-autocomplete-item:hover {
        background: #eff6ff;
    }

    .rx-autocomplete-item:last-child {
        border-bottom: none;
    }

    /* ── Footer ── */
    .rx-footer {
        display: flex;
        gap: 12px;
        padding: 18px 0 4px;
        border-top: 1px solid #e2e8f0;
        margin-top: 8px;
    }

    .rx-btn-submit {
        flex: 1;
        padding: 12px 20px;
        background: linear-gradient(135deg, #1a56db 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
    }

    .rx-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(29, 78, 216, 0.4);
    }

    .rx-btn-cancel {
        padding: 12px 20px;
        background: #f1f5f9;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .rx-btn-cancel:hover {
        background: #e2e8f0;
        color: #374151;
    }

    /* ── hidden util ── */
    .hidden {
        display: none !important;
    }
</style>

<script type="module">
    import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';

    /* ── Stream credentials from PHP ── */
    const API_KEY = '<?= htmlspecialchars($data['stream_api_key'], ENT_QUOTES) ?>';
    const TOKEN = '<?= htmlspecialchars($data['stream_token'], ENT_QUOTES) ?>';
    const CALL_ID = '<?= htmlspecialchars($data['call_id'], ENT_QUOTES) ?>';
    const USER_ID = '<?= htmlspecialchars($data['stream_user_id'], ENT_QUOTES) ?>';
    const USER_NAME = '<?= htmlspecialchars($data['stream_user_name'], ENT_QUOTES) ?>';
    const BACK_URL = '<?= URLROOT ?>/Appointments/doctor';

    /* ── State ── */
    let micEnabled = true;
    let cameraEnabled = true;
    let callTimerRef = null;
    let streamCall = null;
    let streamClient = null;
    let participantSub = null;

    /* ── Call timer ── */
    function startCallTimer() {
        const start = Date.now();
        callTimerRef = setInterval(() => {
            const elapsed = Date.now() - start;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            document.getElementById('callTimer').textContent =
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }, 1000);
    }

    /* ── Bind a MediaStream to a <video> element ── */
    function bindStream(videoEl, stream) {
        if (videoEl && stream && videoEl.srcObject !== stream) {
            videoEl.srcObject = stream;
            videoEl.play().catch(() => { });
        }
    }

    /* ── Initialise Stream Video ── */
    async function init() {
        try {
            streamClient = new StreamVideoClient({
                apiKey: API_KEY,
                user: { id: USER_ID, name: USER_NAME },
                token: TOKEN,
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
        if (streamCall) await streamCall.leave();
        if (streamClient) await streamClient.disconnectUser();
        window.location.href = BACK_URL;
    });

    /* ── Utility panels ── */
    window.toggleChat = () => document.getElementById('chatPanel').classList.toggle('active');
    window.toggleNotes = () => document.getElementById('notesPanel').classList.toggle('active');
    window.saveNotes = () => alert('Notes saved!');

    /* ── Prescription modal ── */
    window.openPrescriptionModal = () => document.getElementById('prescriptionModal').classList.add('active');
    window.closePrescriptionModal = () => document.getElementById('prescriptionModal').classList.remove('active');

    // Close modal when clicking on the backdrop itself
    document.getElementById('prescriptionModal').addEventListener('click', function (e) {
        if (e.target === this) closePrescriptionModal();
    });
    // Close on Escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePrescriptionModal(); });

    init();
</script>

<!-- Prescription Modal JS (inline, prefixed to avoid conflicts) -->
<script>
    (function () {
        const drugDatabase = [
            { name: 'Amoxicillin', formulation: '500mg capsule' },
            { name: 'Paracetamol', formulation: '500mg tablet' },
            { name: 'Ibuprofen', formulation: '400mg tablet' },
            { name: 'Metformin', formulation: '850mg tablet' },
            { name: 'Omeprazole', formulation: '20mg capsule' },
            { name: 'Atorvastatin', formulation: '10mg tablet' },
            { name: 'Lisinopril', formulation: '10mg tablet' },
            { name: 'Aspirin', formulation: '75mg tablet' },
            { name: 'Salbutamol', formulation: '100mcg inhaler' },
            { name: 'Levothyroxine', formulation: '100mcg tablet' }
        ];

        function $(id) { return document.getElementById(id); }

        // ── Autocomplete ──
        const drugInput = $('rx_drugName');
        const drugList = $('rx_drugList');
        const formulationInput = $('rx_formulation');

        drugInput.addEventListener('input', function () {
            const val = this.value.trim().toLowerCase();
            if (val.length < 2) { drugList.classList.add('hidden'); return; }
            const matches = drugDatabase.filter(d => d.name.toLowerCase().includes(val));
            if (matches.length) {
                drugList.innerHTML = matches.map(d =>
                    `<div class="rx-autocomplete-item" data-name="${d.name}" data-form="${d.formulation}">${d.name} — ${d.formulation}</div>`
                ).join('');
                drugList.classList.remove('hidden');
            } else {
                drugList.classList.add('hidden');
            }
        });

        drugList.addEventListener('click', function (e) {
            const item = e.target.closest('.rx-autocomplete-item');
            if (!item) return;
            drugInput.value = item.dataset.name || '';
            formulationInput.value = item.dataset.form || '';
            drugList.classList.add('hidden');
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('.rx-autocomplete-container')) drugList.classList.add('hidden');
        });

        // ── Frequency → auto-fill times ──
        const frequencySelect = $('rx_frequency');
        const customFreqField = $('rx_customFrequencyField');
        const timeOfDayInput = $('rx_timeOfDay');
        const freqTimes = {
            OD: '9:00 AM',
            BD: '9:00 AM, 9:00 PM',
            TDS: '9:00 AM, 2:00 PM, 9:00 PM',
            QID: '9:00 AM, 1:00 PM, 5:00 PM, 9:00 PM',
            Q6H: '9:00 AM, 3:00 PM, 9:00 PM, 3:00 AM',
            Q8H: '9:00 AM, 5:00 PM, 1:00 AM'
        };
        frequencySelect.addEventListener('change', function () {
            customFreqField.classList.toggle('hidden', this.value !== 'custom');
            if (freqTimes[this.value]) timeOfDayInput.value = freqTimes[this.value];
        });

        // ── Duration → auto-compute valid-until ──
        const durationVal = $('rx_durationValue');
        const durationType = $('rx_durationType');
        const validUntil = $('rx_validUntil');

        function calcValidUntil() {
            const n = parseInt(durationVal.value);
            const t = durationType.value;
            if (!n || t === 'Until stopped') return;
            const d = new Date();
            if (t === 'Days') d.setDate(d.getDate() + n);
            if (t === 'Weeks') d.setDate(d.getDate() + n * 7);
            if (t === 'Months') d.setMonth(d.getMonth() + n);
            validUntil.value = d.toISOString().split('T')[0];
        }
        durationVal.addEventListener('input', calcValidUntil);
        durationType.addEventListener('change', calcValidUntil);

        // ── Validation & submit ──
        function showRxError(id, msg) {
            const el = $(id);
            if (el) el.textContent = msg;
            const inp = $(id.replace('Error', '').replace('rx_', 'rx_'));
            if (inp) inp.classList.add('rx-input-error');
        }
        function clearRxErrors() {
            document.querySelectorAll('.rx-error').forEach(el => el.textContent = '');
            document.querySelectorAll('.rx-input-error').forEach(el => el.classList.remove('rx-input-error'));
        }
        function calcDays(v, t) {
            const n = parseInt(v);
            return t === 'Days' ? n : t === 'Weeks' ? n * 7 : t === 'Months' ? n * 30 : 0;
        }
        function validateRxForm() {
            clearRxErrors();
            let ok = true;
            if (!$('rx_drugName').value.trim()) { showRxError('rx_drugNameError', 'Drug name is required'); ok = false; }
            if (!$('rx_route').value) { showRxError('rx_routeError', 'Route is required'); ok = false; }
            if (!$('rx_doseAmount').value || +$('rx_doseAmount').value <= 0) { showRxError('rx_doseAmountError', 'Dose amount is required'); ok = false; }
            if (!$('rx_doseUnit').value) { showRxError('rx_doseUnitError', 'Dose unit is required'); ok = false; }
            if (!$('rx_frequency').value) { showRxError('rx_frequencyError', 'Frequency is required'); ok = false; }
            const dur = $('rx_durationValue').value;
            const durT = $('rx_durationType').value;
            if (durT !== 'Until stopped') {
                if (!dur || +dur <= 0) { showRxError('rx_durationError', 'Duration is required'); ok = false; }
                else if (calcDays(dur, durT) > 365) { showRxError('rx_durationError', 'Duration cannot exceed 1 year'); ok = false; }
            }
            if (!$('rx_diagnosis').value.trim()) { showRxError('rx_diagnosisError', 'Diagnosis / indication is required'); ok = false; }
            return ok;
        }

        $('prescriptionModalForm').addEventListener('submit', function (e) {
            if (!validateRxForm()) {
                e.preventDefault();
                // scroll to first error
                const firstErr = document.querySelector('.rx-input-error');
                if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    })();
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
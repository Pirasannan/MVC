<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientAppointments';
$patientStatus = strtolower((string)($data['patient_status'] ?? 'active'));
$isSuspendedPatient = ($patientStatus === 'suspended');
$appointmentTz = new DateTimeZone('Asia/Colombo');
$formatLocalDateTime = static function ($value, string $format) use ($appointmentTz): string {
  if (empty($value)) {
    return '';
  }

  try {
    return (new DateTimeImmutable((string)$value, $appointmentTz))->format($format);
  } catch (Exception $e) {
    return '';
  }
};

$getDoctorStatusReason = static function ($appointment): string {
  $primary = trim((string)($appointment->status_reason ?? ''));
  if ($primary !== '') {
    return $primary;
  }

  // Backward compatibility for legacy records where the reason may be in notes.
  if (in_array(strtolower((string)($appointment->status ?? '')), ['cancelled', 'rejected'], true)) {
    return trim((string)($appointment->notes ?? ''));
  }

  return '';
};
?>




<div class="dashboard-container patient">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

        <!-- Main Content Area -->
  <div class="patient-appointments-head">
    <h2>My Appointments</h2>
    <span class="sub">View history and request a new booking</span>
  </div>

  <?php if(isset($_SESSION['flash'])){ echo '<p>'.htmlspecialchars($_SESSION['flash']).'</p>'; unset($_SESSION['flash']); } ?>

  <!-- Reschedule Notification -->
  <?php if (!empty($data['pending_reschedules'])): ?>
    <div class="reschedule-alert">
      <div class="alert-content">
        <strong>⏰ Reschedule Request</strong>
        <p>You have <?= $data['pending_reschedules'] ?> appointment<?= $data['pending_reschedules'] > 1 ? 's' : '' ?> with new time proposals from doctors. Please review and respond below.</p>
      </div>
    </div>
  <?php endif; ?>

  <?php
    $list = ($data['appointments'] ?? []);
    $nowLocal = new DateTimeImmutable('now', $appointmentTz);
    $upcomingAppointments = [];
    $pastAppointments = [];

    foreach ($list as $appointmentItem) {
      $statusForSplit = strtolower((string)($appointmentItem->status ?? ''));
      $isPastByStatus = in_array($statusForSplit, ['completed', 'cancelled', 'rejected'], true);
      $isPastByDate = false;

      try {
        $startAtLocal = new DateTimeImmutable((string)($appointmentItem->starts_at ?? ''), $appointmentTz);
        $isPastByDate = $startAtLocal < $nowLocal;
      } catch (Exception $e) {
        $isPastByDate = false;
      }

      if ($isPastByStatus || $isPastByDate) {
        $pastAppointments[] = $appointmentItem;
      } else {
        $upcomingAppointments[] = $appointmentItem;
      }
    }

    $appointmentSections = [
      [
        'title' => 'Upcoming Appointments',
        'hint' => 'Your upcoming booked sessions',
        'empty' => 'No upcoming appointments.',
        'items' => $upcomingAppointments,
      ],
      [
        'title' => 'Past Appointments',
        'hint' => 'Your previous sessions and outcomes',
        'empty' => 'No past appointments yet.',
        'items' => $pastAppointments,
      ],
    ];
  ?>

  <!-- Book -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Request a new appointment</h3>
        <span class="hint">Pick a doctor, date and time</span>
      </div>

      <form id="apptForm" class="p-form" method="post" action="<?= URLROOT ?>/Appointments/book" data-patient-status="<?= htmlspecialchars($patientStatus) ?>">
        <div class="form-grid">
          <div class="field field--doctor">
            <label class="label" for="doctor_name">Doctor</label>
            <div class="suggest-wrap">
              <input class="input" type="text" id="doctor_name" autocomplete="off" placeholder="Type a doctor name..." required>
              <input type="hidden" name="doctor_id" id="doctor_id" required>
              <div id="doctor_suggestions" class="suggest" hidden></div>
            </div>
            <div class="help">Start typing to search, then select a doctor from the list.</div>
          </div>

          <div class="field field--date">
            <label class="label" for="date">Date</label>
            <input class="input" type="date" id="date" name="date" required>
          </div>

          <div class="field field--time">
            <label class="label" for="from">Start time</label>
            <div class="time-select-wrap">
              <select class="select" id="from" name="from" required>
                <option value="">Select a start time</option>
              </select>
            </div>
            <div class="help">15-minute slots. Appointment duration is 15 minutes.</div>
          </div>

          <div class="field field--reason">
            <label class="label" for="reason">Reason</label>
            <input class="input" type="text" id="reason" name="reason" maxlength="255" placeholder="Brief reason for the visit">
          </div>
        </div>

        <button
          type="submit"
          class="join-consultation-btn"
          id="request-appointment-btn"
          <?php echo $isSuspendedPatient ? 'disabled title="Suspended accounts cannot request appointments"' : ''; ?>
        >
          <?php echo $isSuspendedPatient ? 'Request Disabled (Suspended)' : 'Request Appointment'; ?>
        </button>
      </form>
    </div>
  </section>

  <!-- Lists -->
  <?php foreach ($appointmentSections as $section): ?>
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3><?= htmlspecialchars($section['title']) ?></h3>
        <span class="hint"><?= htmlspecialchars($section['hint']) ?></span>
      </div>

      <?php $sectionItems = $section['items']; if(empty($sectionItems)): ?>
        <div class="p-empty"><?= htmlspecialchars($section['empty']) ?></div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="p-appt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($sectionItems as $a): ?>
              <tr>
                <td class="cell-date"><?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'Y-m-d')) ?></td>
                <td class="cell-time">
                  <?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'H:i')) ?>–<?= htmlspecialchars($formatLocalDateTime($a->ends_at, 'H:i')) ?>
                  <small>(15 min)</small>
                </td>
                <td><?= htmlspecialchars($a->doctor_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <?php
                  $rescheduleStatus = $a->reschedule_status ?? 'none';
                  $proposedTime = $a->proposed_datetime ?? null;
                  $status = strtolower($a->status ?? '');
                  $doctorStatusReason = $getDoctorStatusReason($a);
                ?>
                <td>
                  <?php
                    $st = $status;
                    $cls = in_array($st, ['approved','pending','rejected','cancelled','completed']) ? $st : 'pending';
                    if ($st === 'completed') {
                      $statusLabel = 'Completed';
                    } elseif ($rescheduleStatus === 'accepted') {
                      $statusLabel = 'Reschedule accepted';
                    } else {
                      $statusLabel = $a->status ?? '';
                    }
                  ?>
                  <span class="status <?= $cls ?>"><span class="dot"></span><?= htmlspecialchars($statusLabel) ?></span>
                </td>
                <td>
                  <?php if ($rescheduleStatus === 'pending_patient' && $proposedTime): ?>
                    <div class="reschedule-proposal">
                      <div class="proposal-info">
                        <strong>New time proposed:</strong><br>
                        <span class="proposed-time"><?= htmlspecialchars($formatLocalDateTime($proposedTime, 'Y-m-d H:i')) ?></span>
                        <?php if (!empty($a->reschedule_message)): ?>
                          <br><small class="proposal-message"><?= htmlspecialchars($a->reschedule_message) ?></small>
                        <?php endif; ?>
                      </div>
                      <div class="proposal-actions">
                        <a href="<?= URLROOT ?>/Appointments/reschedule_accept/<?= $a->id ?>" 
                           class="btn btn-approve" 
                           onclick="return confirm('Accept this new appointment time?')">Accept</a>
                        <a href="<?= URLROOT ?>/Appointments/reschedule_decline/<?= $a->id ?>" 
                           class="btn btn-reject"
                           onclick="return confirm('Decline this reschedule proposal?')">Decline</a>
                      </div>
                    </div>
                  <?php elseif ($status === 'approved'): ?>
                    <a href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>" class="join-consultation-btn">
                      <i class="fas fa-video"></i> Join Consultation
                    </a>
                  <?php elseif ($status === 'completed'): ?>
                    <button type="button"
                            class="btn btn-light btn-report-call"
                            title="Report call"
                            data-appointment-id="<?= (int)$a->id ?>"
                            onclick="openCallReportModal(this)">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M4 4v16"></path>
                        <path d="M4 4h11l-1 3 1 3H4"></path>
                      </svg>
                    </button>
                  <?php elseif (in_array($status, ['cancelled', 'rejected'], true) && $doctorStatusReason !== ''): ?>
                    <button
                      type="button"
                      class="btn btn-light btn-status-reason"
                      title="View doctor reason"
                      data-status-label="<?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>"
                      data-reason="<?= htmlspecialchars($doctorStatusReason, ENT_QUOTES, 'UTF-8') ?>"
                      onclick="openAppointmentReasonModal(this)">
                      View reason
                    </button>
                  <?php elseif ($rescheduleStatus === 'declined'): ?>
                    <span class="badge badge-info">Reschedule declined</span>
                  <?php elseif (strtolower($a->status) === 'approved'): ?>
                    <?php if ($isSuspendedPatient): ?>
                      <button
                        type="button"
                        class="join-consultation-btn is-disabled"
                        title="Your account is suspended. You cannot join consultations."
                        disabled
                      >
                        <i class="fas fa-video"></i> Join Consultation
                      </button>
                    <?php else: ?>
                      <a href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>" class="join-consultation-btn">
                        <i class="fas fa-video"></i> Join Consultation
                      </a>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="no-action">-</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endforeach; ?>

  <div id="callReportModal" class="res-modal" style="display:none;">
    <div class="res-modal__content">
      <h3 class="res-modal__title">Report Completed Call</h3>
      <form id="callReportForm" method="POST" action="<?= URLROOT ?>/Appointments/submitReport">
        <input type="hidden" name="appointment_id" id="callReportAppointmentId" value="">
        <input type="hidden" name="report_scope" value="call">
        <input type="hidden" name="report_context" value="post_call">

        <div class="res-modal__row">
          <label>Reason</label>
          <select name="reason" required>
            <option value="">Select a reason</option>
            <option value="Abusive or offensive communication">Abusive or offensive communication</option>
            <option value="Spam or unwanted call">Spam or unwanted call</option>
            <option value="Technical issues (poor audio/video)">Technical issues (poor audio/video)</option>
            <option value="Disruptive behavior during call">Disruptive behavior during call</option>
            <option value="Call didn't follow agreed purpose">Call didn't follow agreed purpose</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="res-modal__row">
          <label>Description (optional)</label>
          <textarea name="description" rows="3" placeholder="Add more details"></textarea>
        </div>

        <div class="res-modal__actions">
          <button type="submit" class="btn btn-warning">Send Report</button>
          <button type="button" class="btn btn-light" onclick="closeCallReportModal()">Close</button>
        </div>
      </form>
    </div>
  </div>

  <div id="appointmentReasonModal" class="res-modal" style="display:none;">
    <div class="res-modal__content">
      <h3 class="res-modal__title" id="appointmentReasonTitle">Doctor Reason</h3>
      <div class="res-modal__row">
        <p id="appointmentReasonText" style="margin:0;color:#1f2937;line-height:1.5;"></p>
      </div>
      <div class="res-modal__actions">
        <button type="button" class="btn btn-light" onclick="closeAppointmentReasonModal()">Close</button>
      </div>
    </div>
  </div>

  <style>
  #callReportModal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }

  #callReportModal .res-modal__content {
    width: min(560px, calc(100% - 24px));
    background: #fff;
    border-radius: 12px;
    padding: 18px;
  }

  #callReportModal .res-modal__row {
    margin-top: 12px;
  }

  #callReportModal .res-modal__actions {
    display: flex;
    gap: 10px;
    margin-top: 14px;
  }

  .btn-report-call {
    width: 30px;
    height: 30px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #b45309;
  }

  .btn-report-call svg {
    display: block;
  }

  .btn-status-reason {
    white-space: nowrap;
    color: #1d4ed8;
  }

  #callReportModal select,
  #callReportModal textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px;
    font: inherit;
  }
  </style>

  <script>
(function(){
  const nameInput = document.getElementById('doctor_name');
  const idInput   = document.getElementById('doctor_id');
  const box       = document.getElementById('doctor_suggestions');
  const form      = document.getElementById('apptForm');
  const dateInput = document.getElementById('date');
  const timeSelect = document.getElementById('from');
  const patientStatus = (form?.dataset?.patientStatus || '').toLowerCase();
  let timer;

  function pad2(value){
    return String(value).padStart(2, '0');
  }

  function getTodayLocalString(){
    const now = new Date();
    return `${now.getFullYear()}-${pad2(now.getMonth() + 1)}-${pad2(now.getDate())}`;
  }

  function to12HourLabel(hours, minutes){
    const period = hours >= 12 ? 'PM' : 'AM';
    const normalizedHour = hours % 12 || 12;
    return `${normalizedHour}:${pad2(minutes)} ${period}`;
  }

  function buildTimeSlots(){
    if(!timeSelect) return;

    timeSelect.innerHTML = '<option value="">Select a start time</option>';

    const firstSlotMinute = 8 * 60;
    const lastSlotMinute = 20 * 60;
    const step = 15;

    for(let totalMinutes = firstSlotMinute; totalMinutes <= lastSlotMinute; totalMinutes += step){
      const hour = Math.floor(totalMinutes / 60);
      const minute = totalMinutes % 60;
      const value = `${pad2(hour)}:${pad2(minute)}`;

      const option = document.createElement('option');
      option.value = value;
      option.textContent = `${to12HourLabel(hour, minute)} (${value})`;
      option.dataset.slotMinute = String(totalMinutes);
      timeSelect.appendChild(option);
    }
  }

  function syncSlotAvailability(){
    if(!timeSelect || !dateInput) return;

    const selectedDate = dateInput.value;
    const today = getTodayLocalString();
    const isToday = selectedDate === today;

    const now = new Date();
    const currentMinute = (now.getHours() * 60) + now.getMinutes() + 1;

    Array.from(timeSelect.options).forEach((option, index) => {
      if(index === 0){
        option.disabled = false;
        return;
      }

      const slotMinute = Number(option.dataset.slotMinute || '0');
      option.disabled = isToday && slotMinute <= currentMinute;
    });

    if(timeSelect.selectedOptions[0]?.disabled){
      timeSelect.value = '';
    }
  }

  if(dateInput){
    const today = getTodayLocalString();
    dateInput.min = today;
    if(!dateInput.value){
      dateInput.value = today;
    }
  }

  buildTimeSlots();
  syncSlotAvailability();

  dateInput?.addEventListener('change', syncSlotAvailability);

  function clearSuggestions(){
    box.innerHTML = '';
    box.setAttribute('hidden','');   // keep consistent with your HTML
  }
  function showSuggestions(){
    box.removeAttribute('hidden');
  }

  function render(list){
    clearSuggestions();
    if (!Array.isArray(list) || !list.length) return;

    const frag = document.createDocumentFragment();
    list.forEach(item => {
      const row = document.createElement('div');
      row.className = 'suggest-row';
      row.textContent = item.name;
      row.addEventListener('mousedown', () => {
        // mousedown so it runs before input blur
        nameInput.value = item.name;
        idInput.value = item.id;
        clearSuggestions();
      });
      frag.appendChild(row);
    });
    box.appendChild(frag);
    showSuggestions();
  }

  nameInput.addEventListener('input', function(){
    idInput.value = '';
    const q = nameInput.value.trim();
    clearTimeout(timer);

    if (q.length < 1){ clearSuggestions(); return; }

    timer = setTimeout(async () => {
      try{
        const res = await fetch('<?= URLROOT ?>/Appointments/findDoctors?q=' + encodeURIComponent(q), {
          headers: { 'Accept': 'application/json' }
        });
        if(!res.ok){ clearSuggestions(); return; }
        const data = await res.json();
        render(data);
      }catch(e){
        // Optional: surface errors during dev
        console.error('Doctor search failed:', e);
        clearSuggestions();
      }
    }, 250); // debounce
  });

  // Hide on blur / Esc / outside click
  nameInput.addEventListener('blur', () => setTimeout(clearSuggestions, 150));
  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape') clearSuggestions();
  });
  document.addEventListener('click', (e) => {
    const wrap = nameInput.closest('.suggest-wrap');
    if (wrap && !wrap.contains(e.target)) clearSuggestions();
  });

  // Guard: require a selected doctor
  form.addEventListener('submit', function(e){
    if(patientStatus === 'suspended'){
      e.preventDefault();
      alert('Your account is suspended. You cannot request appointments.');
      return;
    }

    if(!idInput.value){
      e.preventDefault();
      alert('Please select a doctor from the list.');
      nameInput.focus();
    }
  });

})();

function openCallReportModal(triggerBtn) {
  const appointmentId = triggerBtn?.dataset?.appointmentId || '';
  document.getElementById('callReportAppointmentId').value = appointmentId;
  document.getElementById('callReportModal').style.display = 'flex';
}

function closeCallReportModal() {
  const modal = document.getElementById('callReportModal');
  const form = document.getElementById('callReportForm');
  form.reset();
  modal.style.display = 'none';
}

function openAppointmentReasonModal(triggerBtn) {
  const statusLabel = triggerBtn?.dataset?.statusLabel || 'Appointment';
  const reason = triggerBtn?.dataset?.reason || 'No reason provided.';

  document.getElementById('appointmentReasonTitle').textContent = statusLabel + ' Reason';
  document.getElementById('appointmentReasonText').textContent = reason;
  document.getElementById('appointmentReasonModal').style.display = 'flex';
}

function closeAppointmentReasonModal() {
  document.getElementById('appointmentReasonModal').style.display = 'none';
}
</script>
</main>

    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
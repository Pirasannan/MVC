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

    foreach ($list as $appointment) {
      $startsAt = (string)($appointment->starts_at ?? '');

      if ($startsAt === '') {
        $upcomingAppointments[] = $appointment;
        continue;
      }

      try {
        $appointmentStart = new DateTimeImmutable($startsAt, $appointmentTz);
        if ($appointmentStart >= $nowLocal) {
          $upcomingAppointments[] = $appointment;
        } else {
          $pastAppointments[] = $appointment;
        }
      } catch (Exception $e) {
        $upcomingAppointments[] = $appointment;
      }
    }

    $renderAppointmentTable = static function (array $appointments, string $emptyMessage, bool $isPastAppointments = false) use ($formatLocalDateTime, $getDoctorStatusReason, $isSuspendedPatient): void {
  ?>
    <?php if (empty($appointments)): ?>
      <div class="p-empty"><?= htmlspecialchars($emptyMessage) ?></div>
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
          <?php foreach ($appointments as $a): ?>
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
                $status = strtolower((string)($a->status ?? ''));
                $doctorStatusReason = $getDoctorStatusReason($a);
              ?>
              <td>
                <?php
                  $cls = in_array($status, ['approved', 'pending', 'rejected', 'cancelled', 'completed'], true) ? $status : 'pending';
                  if ($status === 'completed') {
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
                <?php if ($isPastAppointments): ?>
                  <span class="badge badge-warning">Missed</span>
                <?php elseif ($rescheduleStatus === 'pending_patient' && $proposedTime): ?>
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
  <?php
    };
  ?>

  <!-- Book -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Request a new appointment</h3>
        <span class="hint">Pick a doctor, date and time</span>
      </div>

      <form id="apptForm" class="p-form" method="post" action="<?= URLROOT ?>/Appointments/book" data-patient-status="<?= htmlspecialchars($patientStatus) ?>">
        <div class="form-grid form-grid--appointments">
          <div class="field field-doctor">
            <label class="label" for="doctor_name">Doctor</label>
            <div class="suggest-wrap">
              <input class="input" type="text" id="doctor_name" autocomplete="off" placeholder="Type a doctor name…" required>
              <input type="hidden" name="doctor_id" id="doctor_id" required>
              <div id="doctor_suggestions" class="suggest" hidden></div>
            </div>
            <div class="help">Start typing to search; click a name to select.</div>
          </div>

          <div class="field field-date">
            <label class="label" for="date">Date</label>
            <input class="input" type="date" id="date" name="date" required>
          </div>

          <div class="field field-time">
            <label class="label" for="from">Start time</label>
            <div class="time-select-wrap">
              <select class="input select time-select" id="from" name="from" required>
                <option value="">Select a time slot</option>
                <?php for ($hour = 6; $hour <= 21; $hour++): ?>
                  <?php foreach ([0, 15, 30, 45] as $minute): ?>
                    <?php
                      $value = sprintf('%02d:%02d', $hour, $minute);
                      $display = DateTimeImmutable::createFromFormat('H:i', $value, $appointmentTz);
                    ?>
                    <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($display ? $display->format('h:i A') : $value) ?></option>
                  <?php endforeach; ?>
                <?php endfor; ?>
              </select>
            </div>
            <div class="help">Each appointment is a 15-minute session.</div>
          </div>

          <div class="field field-reason">
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
          <?php echo $isSuspendedPatient ? 'Request Disabled (Suspended)' : 'Request'; ?>
        </button>
      </form>
    </div>
  </section>

  <!-- Upcoming -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Upcoming</h3>
        <span class="hint">Your scheduled sessions</span>
      </div>
      <?php $renderAppointmentTable($upcomingAppointments, 'No upcoming appointments.', false); ?>
    </div>
  </section>

  <!-- Past -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Past Appointments</h3>
        <span class="hint">Your completed and earlier sessions</span>
      </div>
      <?php $renderAppointmentTable($pastAppointments, 'No past appointments yet.', true); ?>
    </div>
  </section>

  <div id="callReportModal" class="res-modal">
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

  <div id="appointmentReasonModal" class="res-modal">
    <div class="res-modal__content">
      <h3 class="res-modal__title" id="appointmentReasonTitle">Doctor Reason</h3>
      <div class="res-modal__row">
        <p id="appointmentReasonText" class="appointment-reason-text"></p>
      </div>
      <div class="res-modal__actions">
        <button type="button" class="btn btn-light" onclick="closeAppointmentReasonModal()">Close</button>
      </div>
    </div>
  </div>

  <script>
(function(){
  const nameInput = document.getElementById('doctor_name');
  const idInput   = document.getElementById('doctor_id');
  const box       = document.getElementById('doctor_suggestions');
  const form      = document.getElementById('apptForm');
  const timeSelect = document.getElementById('from');
  const patientStatus = (form?.dataset?.patientStatus || '').toLowerCase();
  let timer;

  function initCompactTimeSelect(selectEl){
    if(!selectEl) return;

    const wrap = selectEl.closest('.time-select-wrap');
    if(!wrap || wrap.dataset.enhanced === '1') return;

    wrap.dataset.enhanced = '1';
    wrap.classList.add('time-select-wrap--enhanced');
    selectEl.classList.add('time-select-native');

    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'time-select-trigger';
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-expanded', 'false');

    const menu = document.createElement('div');
    menu.className = 'time-select-menu';
    menu.setAttribute('role', 'listbox');
    menu.hidden = true;

    Array.from(selectEl.options).forEach((option, idx) => {
      if(idx === 0) return;

      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'time-select-option';
      item.dataset.value = option.value;
      item.textContent = option.textContent;
      item.addEventListener('click', () => {
        selectEl.value = option.value;
        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
        closeMenu();
      });

      menu.appendChild(item);
    });

    function syncSelection(){
      const selected = selectEl.options[selectEl.selectedIndex];
      const hasValue = !!(selected && selected.value);

      trigger.textContent = hasValue ? selected.textContent : 'Select a time slot';
      trigger.classList.toggle('is-selected', hasValue);

      Array.from(menu.querySelectorAll('.time-select-option')).forEach((item) => {
        item.classList.toggle('is-selected', item.dataset.value === selectEl.value);
      });
    }

    function openMenu(){
      menu.hidden = false;
      wrap.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');
    }

    function closeMenu(){
      menu.hidden = true;
      wrap.classList.remove('is-open');
      trigger.setAttribute('aria-expanded', 'false');
    }

    trigger.addEventListener('click', () => {
      if(menu.hidden) {
        openMenu();
      } else {
        closeMenu();
      }
    });

    document.addEventListener('click', (e) => {
      if(!wrap.contains(e.target)) closeMenu();
    });

    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape') closeMenu();
    });

    selectEl.addEventListener('change', syncSelection);

    wrap.appendChild(trigger);
    wrap.appendChild(menu);
    syncSelection();
  }

  initCompactTimeSelect(timeSelect);

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
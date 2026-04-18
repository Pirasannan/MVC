<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientAppointments';
$patientStatus = strtolower((string)($data['patient_status'] ?? 'active'));
$isSuspendedPatient = ($patientStatus === 'suspended');
?>




<div class="dashboard-container doctor">        <!-- Sidebar Navigation -->
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

  <!-- List -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Upcoming & past</h3>
        <span class="hint">Your booked sessions</span>
      </div>

      <?php $list = ($data['appointments'] ?? []); if(empty($list)): ?>
        <div class="p-empty">No appointments yet.</div>
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
            <?php foreach($list as $a): ?>
              <tr>
                <td class="cell-date"><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                <td class="cell-time">
                  <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
                  <small>(15 min)</small>
                </td>
                <td><?= htmlspecialchars($a->doctor_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <?php
                  $rescheduleStatus = $a->reschedule_status ?? 'none';
                  $proposedTime = $a->proposed_datetime ?? null;
                  $status = strtolower($a->status ?? '');
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
                        <span class="proposed-time"><?= date('Y-m-d H:i', strtotime($proposedTime)) ?></span>
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
                    <a href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>" class="join-consultation-btn" style="display: inline-block; padding: 8px 16px; background: #4a90e2; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500;">
                      <i class="fas fa-video" style="margin-right: 8px;"></i> Join Consultation
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
                  <?php elseif ($rescheduleStatus === 'declined'): ?>
                    <span class="badge badge-info">Reschedule declined</span>
                  <?php elseif (strtolower($a->status) === 'approved'): ?>
                    <?php if ($isSuspendedPatient): ?>
                      <a
                        href="#"
                        class="join-consultation-btn suspended-join-btn"
                        title="Double-click to see why joining is blocked"
                        style="display: inline-block; padding: 8px 16px; background: #9ca3af; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: not-allowed;"
                      >
                        <i class="fas fa-video" style="margin-right: 8px;"></i> Join Consultation
                      </a>
                    <?php else: ?>
                      <a href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>" class="join-consultation-btn" style="display: inline-block; padding: 8px 16px; background: #4a90e2; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500;">
                        <i class="fas fa-video" style="margin-right: 8px;"></i> Join Consultation
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

  #callReportModal select,
  #callReportModal textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px;
    font: inherit;
  }
  </style>

  <!-- Book -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">f
        <h3>Request a new appointment</h3>
        <span class="hint">Pick a doctor, date and time</span>
      </div>

      <form id="apptForm" class="p-form" method="post" action="<?= URLROOT ?>/Appointments/book" data-patient-status="<?= htmlspecialchars($patientStatus) ?>">
        <div class="form-grid">
          <div class="field">
            <label class="label" for="doctor_name">Doctor</label>
            <div class="suggest-wrap">
              <input class="input" type="text" id="doctor_name" autocomplete="off" placeholder="Type a doctor name…" required>
              <input type="hidden" name="doctor_id" id="doctor_id" required>
              <div id="doctor_suggestions" class="suggest" hidden></div>
            </div>
            <div class="help">Start typing to search; click a name to select.</div>
          </div>

          <div class="field">
            <label class="label" for="date">Date</label>
            <input class="input" type="date" id="date" name="date" required>
          </div>

          <div class="field">
            <label class="label" for="from">Start time</label>
            <input class="input" type="time" id="from" name="from" required>
          </div>

          <div class="field">
            <label class="label" for="reason">Reason</label>
            <input class="input" type="text" id="reason" name="reason" maxlength="255" placeholder="Brief reason for the visit">
          </div>
        </div>

        <button
          type="submit"
          class="btn-primary"
          id="request-appointment-btn"
          <?php echo $isSuspendedPatient ? 'disabled title="Suspended accounts cannot request appointments"' : ''; ?>
        >
          <?php echo $isSuspendedPatient ? 'Request Disabled (Suspended)' : 'Request'; ?>
        </button>
      </form>
      <script>
(function(){
  const nameInput = document.getElementById('doctor_name');
  const idInput   = document.getElementById('doctor_id');
  const box       = document.getElementById('doctor_suggestions');
  const form      = document.getElementById('apptForm');
  const patientStatus = (form?.dataset?.patientStatus || '').toLowerCase();
  let timer;

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

  if (patientStatus === 'suspended') {
    document.querySelectorAll('.suspended-join-btn').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
      });

      btn.addEventListener('dblclick', (e) => {
        e.preventDefault();
        alert('Your account is suspended. You cannot join consultations.');
      });
    });
  }
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
</script>

    </div>
  </section>
</main>

    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
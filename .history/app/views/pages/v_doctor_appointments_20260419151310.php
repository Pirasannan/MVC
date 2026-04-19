<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'doctorAppointments';
$doctorStatus = strtolower((string)($data['doctor_status'] ?? 'active'));
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
?>




<div class="dashboard-container doctor">        
  <!-- Sidebar Navigation -->
  <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>


        <!-- Main Content Area -->
<main class="main-content">
  <!-- Top Header -->
  <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>

  <div class="appointments-head">
    <h2>Appointments</h2>
    <span class="sub">Manage incoming requests and approved sessions</span>
  </div>

  <?php if(!empty($data['flash'])): ?>
    <p><?= htmlspecialchars($data['flash']) ?></p>
  <?php endif; ?>


  <!-- ===== pending list ===== -->
  <section class="appt-section">
    <div class="appt-card">
      <div class="appt-card-header">
        <h3>Incoming (need action)</h3>
        <span class="hint">Approve / Reject / Cancel</span>
      </div>

      <?php if(empty($data['pending'])): ?>
        <div class="appt-empty">No incoming requests.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="appt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Reason</th>
                <th>Status</th>
                <th class="nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data['pending'] as $a): ?>
              <tr>
                <td class="cell-date"><?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'Y-m-d')) ?></td>
                <td class="cell-time"><?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'H:i')) ?>–<?= htmlspecialchars($formatLocalDateTime($a->ends_at, 'H:i')) ?></td>
                <td><?= htmlspecialchars($a->patient_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <?php
                  $currentDt = $a->starts_at ?? '';
                  $rescheduleStatus = $a->reschedule_status ?? 'none';
                  $statusLabel = ($rescheduleStatus === 'accepted') ? 'Reschedule accepted' : ($a->status ?? '');
                ?>
                <td>
                  <span class="status pending"><span class="dot"></span><?= htmlspecialchars($statusLabel) ?></span>
                </td>
                <td>
                  <div class="actions">
                    <?php if ($doctorStatus === 'suspended'): ?>
                      <span class="btn btn-approve" aria-disabled="true" style="pointer-events:none;opacity:.55;">Approve</span>
                      <span class="btn btn-reject" aria-disabled="true" style="pointer-events:none;opacity:.55;">Reject</span>
                    <?php else: ?>
                      <a class="btn btn-approve" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved">Approve</a>
                      <button
                        type="button"
                        class="btn btn-reject"
                        data-id="<?= (int)$a->id ?>"
                        onclick="openStatusReasonModal('rejected', this.dataset.id)">
                        Reject
                      </button>
                    <?php endif; ?>
                    <!-- Reschedule button / info -->
                      <?php
    $currentDt = $a->starts_at ?? '';
    $rescheduleStatus = $a->reschedule_status ?? 'none';
  ?>
  <?php if ($rescheduleStatus !== 'pending_patient'): ?>
    <?php if ($doctorStatus === 'suspended' || $doctorStatus === 'inactive'): ?>
      <span class="btn btn-warning btn-reschedule" aria-disabled="true" style="pointer-events:none;opacity:.55;">Reschedule</span>
    <?php else: ?>
      <button type="button"
              class="btn btn-warning btn-reschedule"
              data-id="<?= $a->id ?>"
              data-current="<?= htmlspecialchars($currentDt) ?>"
              onclick="openResModal(this)">
        Reschedule
      </button>
    <?php endif; ?>
  <?php else: ?>
    <span class="badge badge-warning">Waiting for patient confirmation</span>
  <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== approved list ===== -->
  <section class="appt-section">
    <div class="appt-card">
      <div class="appt-card-header">
        <h3>Approved (ready to conduct)</h3>
        <span class="hint">Start / Cancel / Complete</span>
      </div>

      <?php if(empty($data['approved'])): ?>
        <div class="appt-empty">No approved appointments yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="appt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Reason</th>
                <th>Status</th>
                <th class="nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data['approved'] as $a): ?>
              <tr>
                <td class="cell-date"><?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'Y-m-d')) ?></td>
                <td class="cell-time">
                  <?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'H:i')) ?>–<?= htmlspecialchars($formatLocalDateTime($a->ends_at, 'H:i')) ?>
                  <small>(15 min)</small>
                </td>
                <td><?= htmlspecialchars($a->patient_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <?php
                  $rescheduleStatus = $a->reschedule_status ?? 'none';
                  $statusLabel = ($rescheduleStatus === 'accepted') ? 'Reschedule accepted' : ($a->status ?? '');
                ?>
                <td>
                  <span class="status approved"><span class="dot"></span><?= htmlspecialchars($statusLabel) ?></span>
                </td>
                <td>
                  <div class="actions">
                    <?php if ($doctorStatus === 'suspended'): ?>
                      <span class="btn btn-start" aria-disabled="true" style="pointer-events:none;opacity:.55;">Start</span>
                      <span class="btn btn-cancel" aria-disabled="true" style="pointer-events:none;opacity:.55;">Cancel</span>
                      <span class="btn btn-complete" aria-disabled="true" style="pointer-events:none;opacity:.55;">Complete</span>
                    <?php else: ?>
                      <a href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>" class="btn btn-start">Start</a>
                      <button
                        type="button"
                        class="btn btn-cancel"
                        data-id="<?= (int)$a->id ?>"
                        onclick="openStatusReasonModal('cancelled', this.dataset.id)">
                        Cancel
                      </button>
                      <a class="btn btn-complete" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed">Complete</a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===== completed list ===== -->
  <section class="appt-section">
    <div class="appt-card">
      <div class="appt-card-header">
        <h3>Completed appointments</h3>
        <span class="hint">Completed sessions remain visible here</span>
      </div>

      <?php if(empty($data['completed'])): ?>
        <div class="appt-empty">No completed appointments yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table class="appt-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient</th>
                <th>Reason</th>
                <th>Status</th>
                <th class="nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data['completed'] as $a): ?>
              <tr>
                <td class="cell-date\"><?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'Y-m-d')) ?></td>
                <td class="cell-time">
                  <?= htmlspecialchars($formatLocalDateTime($a->starts_at, 'H:i')) ?>–<?= htmlspecialchars($formatLocalDateTime($a->ends_at, 'H:i')) ?>
                  <small>(15 min)</small>
                </td>
                <td><?= htmlspecialchars($a->patient_name) ?></td>
                <td class="cell-reason\"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <td>
                  <span class="status completed"><span class="dot"></span>Completed</span>
                </td>
                <td>
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
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

<!-- Reschedule Modal -->
<div id="resModal" class="res-modal" style="display:none;">
  <div class="res-modal__content">
    <h3 class="res-modal__title">Propose a new time</h3>

    <form id="resForm" method="POST" action="">
      <!-- CSRF if you use one -->
      <?php if (!empty($_SESSION['csrf'])): ?>
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <?php endif; ?>

      <div class="res-modal__row">
        <label>New date & time</label>
        <!-- IMPORTANT: datetime-local expects `YYYY-MM-DDTHH:MM` -->
        <input type="datetime-local" name="new_datetime" required>
      </div>

      <div class="res-modal__row">
        <label>Note to patient (optional)</label>
        <input type="text" name="message" placeholder="e.g., I have a ward round at your original time.">
      </div>

      <div class="res-modal__actions">
        <button type="submit" class="btn btn-warning" <?php echo ($doctorStatus === 'suspended' || $doctorStatus === 'inactive') ? 'disabled style="opacity:.55;cursor:not-allowed;"' : ''; ?>>Send Proposal</button>
        <button type="button" class="btn btn-light" onclick="closeResModal()">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Status Reason Modal (Reject/Cancel) -->
<div id="statusReasonModal" class="res-modal" style="display:none;">
  <div class="res-modal__content">
    <h3 class="res-modal__title" id="statusReasonModalTitle">Provide a reason</h3>

    <form id="statusReasonForm" method="POST" action="">
      <?php if (!empty($_SESSION['csrf'])): ?>
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
      <?php endif; ?>

      <div class="res-modal__row">
        <label id="statusReasonLabel" for="statusReasonInput">Reason</label>
        <textarea
          id="statusReasonInput"
          name="status_reason"
          rows="4"
          maxlength="1000"
          required
          placeholder="Please provide the reason for this action."></textarea>
      </div>

      <div class="res-modal__actions">
        <button type="submit" id="statusReasonSubmit" class="btn btn-warning">Submit</button>
        <button type="button" class="btn btn-light" onclick="closeStatusReasonModal()">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Call Report Modal -->
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

#statusReasonModal textarea {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 10px;
  font: inherit;
  resize: vertical;
}
</style>

<script>
const statusReasonBaseUrl = '<?= URLROOT ?>/Appointments/setStatus';

function openStatusReasonModal(action, appointmentId) {
  const modal = document.getElementById('statusReasonModal');
  const form = document.getElementById('statusReasonForm');
  const title = document.getElementById('statusReasonModalTitle');
  const label = document.getElementById('statusReasonLabel');
  const submit = document.getElementById('statusReasonSubmit');
  const input = document.getElementById('statusReasonInput');

  const isReject = action === 'rejected';
  const actionLabel = isReject ? 'Reject' : 'Cancel';

  title.textContent = actionLabel + ' Appointment';
  label.textContent = 'Reason for ' + actionLabel.toLowerCase();
  submit.textContent = actionLabel;
  form.action = statusReasonBaseUrl + '/' + encodeURIComponent(appointmentId) + '/' + encodeURIComponent(action);
  input.value = '';

  modal.style.display = 'flex';
  input.focus();
}

function closeStatusReasonModal() {
  const modal = document.getElementById('statusReasonModal');
  const form = document.getElementById('statusReasonForm');

  form.reset();
  modal.style.display = 'none';
}

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

<!-- Modal functionality is handled by modal-manager.js -->

<?php if (!empty($data['cal'])): ?>
<?php $cal = $data['cal']; ?>
<div class="calendar-card">
  <div class="calendar-header">
    <a class="cal-nav" href="<?= URLROOT ?>/Appointments/doctor?month=<?= htmlspecialchars($cal['prevMonth']) ?>">&laquo;</a>
    <h3 class="calendar-title"><?= htmlspecialchars($cal['monthName']) ?></h3>
    <a class="cal-nav" href="<?= URLROOT ?>/Appointments/doctor?month=<?= htmlspecialchars($cal['nextMonth']) ?>">&raquo;</a>
  </div>

  <div class="calendar-grid">
    <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $lbl): ?>
      <div class="calendar-cell calendar-head"><?= $lbl ?></div>
    <?php endforeach; ?>

    <?php
      $gridStart = (new DateTimeImmutable('@' . (int)$cal['gridStartTs']))->setTimezone($appointmentTz);
      $gridEnd = (new DateTimeImmutable('@' . (int)$cal['gridEndTs']))->setTimezone($appointmentTz);
      for ($day = $gridStart; $day <= $gridEnd; $day = $day->modify('+1 day')):
        $ymd = $day->format('Y-m-d');
        $isCurrent = (substr($ymd,0,7) === $cal['monthStr']);
        $has = !empty($cal['byDate'][$ymd]);
    ?>
      <div class="calendar-cell <?= $isCurrent ? '' : 'is-out' ?> <?= $has ? 'has-appt' : '' ?>">
        <div class="cell-top">
          <span class="cell-date"><?= (int)$day->format('j') ?></span>
          <?php if ($has): ?><span class="cell-dot" title="Approved appointments"></span><?php endif; ?>
        </div>

        <?php if ($has): ?>
          <ul class="cell-list">
            <?php
              $items = $cal['byDate'][$ymd];
              $limit = 3;
              foreach (array_slice($items, 0, $limit) as $it): ?>
                <li>
                  <span class="t"><?= htmlspecialchars($it['time']) ?></span>
                  <?php if (!empty($it['name'])): ?>
                    <span class="p"><?= htmlspecialchars($it['name']) ?></span>
                  <?php endif; ?>
                </li>
            <?php endforeach;
              if (count($items) > $limit):
                echo '<li class="more">+'.(count($items)-$limit).' more</li>';
              endif;
            ?>
          </ul>
        <?php endif; ?>
      </div>
    <?php endfor; ?>
  </div>
</div>
<?php else: ?>
<!-- Show empty calendar when data is not available -->
<div class="calendar-card">
  <div class="calendar-header">
    <a class="cal-nav" href="<?= URLROOT ?>/Appointments/doctor?month=<?= date('Y-m', strtotime('-1 month')) ?>">&laquo;</a>
    <h3 class="calendar-title"><?= date('F Y') ?></h3>
    <a class="cal-nav" href="<?= URLROOT ?>/Appointments/doctor?month=<?= date('Y-m', strtotime('+1 month')) ?>">&raquo;</a>
  </div>

  <div class="calendar-grid">
    <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $lbl): ?>
      <div class="calendar-cell calendar-head"><?= $lbl ?></div>
    <?php endforeach; ?>

    <?php
      $firstDay = new DateTimeImmutable('first day of this month 00:00:00', $appointmentTz);
      $lastDay = new DateTimeImmutable('last day of this month 23:59:59', $appointmentTz);
      $startOfWeek = $firstDay->modify('monday this week')->setTime(0, 0, 0);
      $endOfWeek = $lastDay->modify('sunday this week')->setTime(23, 59, 59);
      
      for ($day = $startOfWeek; $day <= $endOfWeek; $day = $day->modify('+1 day')):
        $ymd = $day->format('Y-m-d');
        $isCurrent = (substr($ymd,0,7) === $firstDay->format('Y-m'));
    ?>
      <div class="calendar-cell <?= $isCurrent ? '' : 'is-out' ?>">
        <div class="cell-top">
          <span class="cell-date"><?= (int)$day->format('j') ?></span>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>



</main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
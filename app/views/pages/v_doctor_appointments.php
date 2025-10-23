<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'doctorAppointments';
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
                <td class="cell-date"><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                <td class="cell-time"><?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></td>
                <td><?= htmlspecialchars($a->patient_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <td>
                  <span class="status pending"><span class="dot"></span><?= htmlspecialchars($a->status) ?></span>
                </td>
                <td>
                  <div class="actions">
                    <a class="btn btn-approve" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved">Approve</a>
                    <a class="btn btn-reject"  href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/rejected">Reject</a>
                    <!-- Reschedule button / info -->
                      <?php
    $currentDt = $a->starts_at ?? '';
    $rescheduleStatus = $a->reschedule_status ?? 'none';
  ?>
  <?php if ($rescheduleStatus !== 'pending_patient'): ?>
    <button type="button"
            class="btn btn-warning btn-reschedule"
            data-id="<?= $a->id ?>"
            data-current="<?= htmlspecialchars($currentDt) ?>"
            onclick="openResModal(this)">
      Reschedule
    </button>
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
                <td class="cell-date"><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
                <td class="cell-time">
                  <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
                  <small>(15 min)</small>
                </td>
                <td><?= htmlspecialchars($a->patient_name) ?></td>
                <td class="cell-reason"><?= htmlspecialchars($a->reason ?? 'No reason provided') ?></td>
                <td>
                  <span class="status approved"><span class="dot"></span><?= htmlspecialchars($a->status) ?></span>
                </td>
                <td>
                  <div class="actions">
                    <!-- dummy Start button -->
                    <button type="button" class="btn btn-start" disabled>Start</button>
                    <a class="btn btn-cancel"   href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled">Cancel</a>
                    <a class="btn btn-complete" href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed">Complete</a>
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
        <button type="submit" class="btn btn-warning">Send Proposal</button>
        <button type="button" class="btn btn-light" onclick="closeResModal()">Close</button>
      </div>
    </form>
  </div>
</div>

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
      for ($ts = $cal['gridStartTs']; $ts <= $cal['gridEndTs']; $ts = strtotime('+1 day', $ts)):
        $ymd = date('Y-m-d', $ts);
        $isCurrent = (substr($ymd,0,7) === $cal['monthStr']);
        $has = !empty($cal['byDate'][$ymd]);
    ?>
      <div class="calendar-cell <?= $isCurrent ? '' : 'is-out' ?> <?= $has ? 'has-appt' : '' ?>">
        <div class="cell-top">
          <span class="cell-date"><?= (int)date('j', $ts) ?></span>
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
      $firstDay = strtotime('first day of this month');
      $lastDay = strtotime('last day of this month');
      $startOfWeek = strtotime('last monday', $firstDay);
      $endOfWeek = strtotime('next sunday', $lastDay);
      
      for ($ts = $startOfWeek; $ts <= $endOfWeek; $ts = strtotime('+1 day', $ts)):
        $ymd = date('Y-m-d', $ts);
        $isCurrent = (substr($ymd,0,7) === date('Y-m'));
    ?>
      <div class="calendar-cell <?= $isCurrent ? '' : 'is-out' ?>">
        <div class="cell-top">
          <span class="cell-date"><?= (int)date('j', $ts) ?></span>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>
<?php endif; ?>



</main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
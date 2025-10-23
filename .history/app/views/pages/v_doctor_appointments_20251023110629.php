<?php require APPROOT.'/views/inc/header.php'; ?>




<div class="dashboard-container doctor">        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <span class="logo-text">MEDILINK</span>
                </div> 
            </div>
            
            <nav class="nav-menu">
                <a href="<?php echo URLROOT; ?>/Pages/doctordashboard" class="nav-item ">
                    Dashboard
                </a>
                <a href="<?php echo URLROOT; ?>/Appointments/doctor" class="nav-item active">
                    Appointments
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions" class="nav-item">
                    Prescriptions
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMessages" class="nav-item">
                    Messages
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMedicalrecords" class="nav-item">
                    Medical Records
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorProfile" class="nav-item">
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
<main class="main-content">
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

<style>
.res-modal {
  position: fixed; inset: 0; background: rgba(0,0,0,.45);
  display: none; align-items: center; justify-content: center; z-index: 1000;
}
.res-modal__content {
  background: #fff; padding: 1.25rem; width: 100%; max-width: 480px;
  border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,.2);
}
.res-modal__title { margin: 0 0 .75rem 0; }
.res-modal__row { margin-bottom: .75rem; display:flex; flex-direction:column; gap:.35rem; }
.res-modal__actions { display:flex; gap:.5rem; justify-content:flex-end; }
.badge.badge-warning {
  display:inline-block; padding:.2rem .5rem; background:#FFC107; color:#222; border-radius:8px; font-size:.8rem;
}
</style>

<script>
let resApptId = null;

function openResModal(btn) {
  resApptId = btn.getAttribute('data-id');
  const cur = btn.getAttribute('data-current') || '';
  const form = document.getElementById('resForm');
  form.action = "<?= URLROOT ?>/Appointments/reschedule/" + resApptId;

  const input = form.querySelector('input[name="new_datetime"]');
  if (cur && cur.indexOf('T') === -1) {
    input.value = cur.replace(' ', 'T').slice(0, 16);
  } else {
    input.value = '';
  }

  document.getElementById('resModal').style.display = 'flex';
}

function closeResModal(){
  document.getElementById('resModal').style.display = 'none';
}
</script>

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

<style>
.calendar-card{background:#fff;border-radius:14px;padding:16px;box-shadow:0 8px 24px rgba(0,0,0,.08);margin-bottom:20px;margin-top:20px;}
.calendar-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.calendar-title{margin:0;font-size:1.1rem;letter-spacing:.2px}
.cal-nav{text-decoration:none;padding:6px 10px;border-radius:10px;background:#f3f4f6;color:#333}
.cal-nav:hover{filter:brightness(.95)}
.calendar-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}
.calendar-cell{border:1px solid #e5e7eb;border-radius:10px;min-height:110px;padding:8px;background:#fff;display:flex;flex-direction:column}
.calendar-head{background:#f8fafc;font-weight:600;text-align:center;min-height:auto;padding:10px 0}
.is-out{background:#fafafa;color:#9ca3af}
.cell-top{display:flex;align-items:center;justify-content:space-between}
.cell-date{font-weight:600}
.cell-dot{width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block}
.has-appt{border-color:#d1fae5;background:#f8fffb}
.cell-list{list-style:none;margin:6px 0 0 0;padding:0;display:flex;flex-direction:column;gap:4px}
.cell-list li{display:flex;gap:6px;font-size:.9rem;line-height:1.2}
.cell-list .t{font-feature-settings:"tnum";font-variant-numeric:tabular-nums}
.cell-list .p{color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.more{color:#6b7280;font-size:.85rem}
@media (max-width:900px){.cell-list li .p{display:none}}
</style>


</main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
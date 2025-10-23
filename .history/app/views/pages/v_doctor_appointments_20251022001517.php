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
<h2>Appointments</h2>

<?php if(!empty($data['flash'])): ?>
  <p><?= htmlspecialchars($data['flash']) ?></p>
<?php endif; ?>

<!-- ===== pending list ===== -->
<h3>Incoming (need action)</h3>
<?php if(empty($data['pending'])): ?>
  <p>No incoming requests.</p>
<?php else: ?>
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Time</th>
      <th>Patient</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($data['pending'] as $a): ?>
    <tr>
      <td><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
      <td><?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></td>
      <td><?= htmlspecialchars($a->patient_name) ?></td>
      <td><?= htmlspecialchars($a->status) ?></td>
      <td>
        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved">Approve</a> |
        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/rejected">Reject</a> |
        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled">Cancel</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<hr>

<!-- ===== approved list ===== -->
<h3>Approved (ready to conduct)</h3>
<?php if(empty($data['approved'])): ?>
  <p>No approved appointments yet.</p>
<?php else: ?>
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Time</th>
      <th>Patient</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($data['approved'] as $a): ?>
    <tr>
      <td><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
      <td>
        <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
        <small>(15 min)</small>
      </td>
      <td><?= htmlspecialchars($a->patient_name) ?></td>
      <td><?= htmlspecialchars($a->status) ?></td>
      <td>
        <!-- dummy Start button, no backend yet -->
        <button type="button" disabled>Start</button> |
        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled">Cancel</a> |
        <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed">Complete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>



        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
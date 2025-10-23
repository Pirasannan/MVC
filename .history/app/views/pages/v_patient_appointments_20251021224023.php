<?php require APPROOT.'/views/inc/header.php'; ?>




<div class="dashboard-container doctor">        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <span class="logo-text">MEDILINK</span>
                </div>
            </div>
            
            <nav class="nav-menu">
                <a href="<?php echo URLROOT; ?>/Pages/patientdashboard" class="nav-item ">
                    Dashboard
                </a>
                <a href="<?php echo URLROOT; ?>/Appointments/my" class="nav-item active">
                    Appointments
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/patientPrescriptions" class="nav-item ">
                    Prescriptions
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/patientMessages" class="nav-item">
                    Messages
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/patientMedicalrecords" class="nav-item">
                    Medical Records
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/patientProfile" class="nav-item">
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <?php /* excerpt to list + book */ ?>
<h2>My Appointments</h2>
<?php if(isset($_SESSION['flash'])){ echo '<p>'.htmlspecialchars($_SESSION['flash']).'</p>'; unset($_SESSION['flash']); } ?>
<table>
  <thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Status</th></tr></thead>
  <tbody>
  <?php foreach(($data['appointments'] ?? []) as $a): ?>
    <tr>
      <td><?= date('Y-m-d', strtotime($a->starts_at)) ?></td>
      <td><?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></td>
      <td><?= htmlspecialchars($a->doctor_name) ?></td>
      <td><?= htmlspecialchars($a->status) ?></td>
      <td>
  <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?>
  <small>(15 min)</small>
</td>

    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<hr>

<h3>Request a new appointment</h3>
<form method="post" action="<?= URLROOT ?>/Appointments/book">
  <label>Doctor ID</label>
  <input type="number" name="doctor_id" required>

  <label>Date</label>
  <input type="date" name="date" required>

  <label>Start time</label>
  <input type="time" name="from" required>

  <label>Reason (optional)</label>
  <input type="text" name="reason" maxlength="255">

  <button type="submit">Request</button>
</form>


<script>
const doctorInput = document.getElementById('doctor_id');
const slotSelect  = document.getElementById('slot_id');
doctorInput.addEventListener('change', async () => {
  slotSelect.innerHTML = '';
  const d = doctorInput.value.trim();
  if(!d) return;
  const res = await fetch('<?= URLROOT ?>/Appointments/slots/' + d);
  const slots = await res.json();
  slots.forEach(s => {
    const txt = new Date(s.slot_start).toLocaleString() + ' → ' + new Date(s.slot_end).toLocaleTimeString();
    const opt = new Option(txt, s.id);
    slotSelect.add(opt);
  });
});
</script>

        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
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
<form id="apptForm" method="post" action="<?= URLROOT ?>/Appointments/book">
  <label>Doctor</label>
  <input type="text" id="doctor_name" autocomplete="off" placeholder="Type a doctor name…" required>
  <input type="hidden" name="doctor_id" id="doctor_id" required>

  <div id="doctor_suggestions" style="position:relative;">
    <!-- suggestions will appear here -->
  </div>

  <label>Date</label>
  <input type="date" name="date" required>

  <label>Start time</label>
  <input type="time" name="from" required>

  <label>Reason (optional)</label>
  <input type="text" name="reason" maxlength="255">

  <button type="submit">Request</button>
</form>

<script>
(function(){
  const nameInput = document.getElementById('doctor_name');
  const idInput   = document.getElementById('doctor_id');
  const box       = document.getElementById('doctor_suggestions');
  const form      = document.getElementById('apptForm');
  let timer;

  function clearSuggestions(){
    box.innerHTML = '';
  }

  function render(list){
    clearSuggestions();
    if (!list || !list.length) return;

    const wrap = document.createElement('div');
    wrap.style.position = 'absolute';
    wrap.style.top = '100%';
    wrap.style.left = '0';
    wrap.style.right = '0';
    wrap.style.zIndex = '10';
    wrap.style.border = '1px solid #ccc';
    wrap.style.background = '#fff';

    list.forEach(item => {
      const row = document.createElement('div');
      row.textContent = item.name + ' (ID: ' + item.id + ')';
      row.style.padding = '6px 8px';
      row.style.cursor = 'pointer';
      row.addEventListener('mousedown', function(e){
        // mousedown to fire before input blur
        nameInput.value = item.name;
        idInput.value = item.id;
        clearSuggestions();
      });
      wrap.appendChild(row);
    });
    box.appendChild(wrap);
  }

  nameInput.addEventListener('input', function(){
    idInput.value = '';           // reset until a selection is made
    const q = nameInput.value.trim();
    clearTimeout(timer);
    if (q.length < 1){ clearSuggestions(); return; }

    timer = setTimeout(async () => {
      try{
        const res = await fetch('<?= URLROOT ?>/Appointments/findDoctors?q=' + encodeURIComponent(q));
        const data = await res.json();
        render(data);
      }catch(e){ /* ignore */ }
    }, 250); // debounce
  });

  // hide suggestions when leaving the field
  nameInput.addEventListener('blur', function(){ setTimeout(clearSuggestions, 150); });

  // guard: don’t submit if no doctor selected
  form.addEventListener('submit', function(e){
    if(!idInput.value){
      e.preventDefault();
      alert('Please select a doctor from the list.');
      nameInput.focus();
    }
  });
})();
</script>


        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
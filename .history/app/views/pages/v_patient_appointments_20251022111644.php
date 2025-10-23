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
  <div class="patient-appointments-head">
    <h2>My Appointments</h2>
    <span class="sub">View history and request a new booking</span>
  </div>

  <?php if(isset($_SESSION['flash'])){ echo '<p>'.htmlspecialchars($_SESSION['flash']).'</p>'; unset($_SESSION['flash']); } ?>

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
                <th>Status</th>
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
                <td>
                  <?php
                    $st = strtolower($a->status);
                    $cls = in_array($st, ['approved','pending','rejected','cancelled']) ? $st : 'pending';
                  ?>
                  <span class="status <?= $cls ?>"><span class="dot"></span><?= htmlspecialchars($a->status) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Book -->
  <section class="p-appt-section">
    <div class="p-card">
      <div class="p-card-header">
        <h3>Request a new appointment</h3>
        <span class="hint">Pick a doctor, date and time</span>
      </div>

      <form id="apptForm" class="p-form" method="post" action="<?= URLROOT ?>/Appointments/book">
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

        <button type="submit" class="btn-primary">Request</button>
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
      row.textContent = `${item.name} (ID: ${item.id})`;
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
    if(!idInput.value){
      e.preventDefault();
      alert('Please select a doctor from the list.');
      nameInput.focus();
    }
  });
})();
</script>

    </div>
  </section>
</main>

    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
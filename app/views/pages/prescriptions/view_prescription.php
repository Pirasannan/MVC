<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form/view_prescription.css?v=<?php echo filemtime(APPROOT.'/public/css/components/form/view_prescription.css'); ?>">

<!-- Prescription View Popup -->
<div class="prescription-overlay" id="prescriptionPopup">
  <div class="prescription-modal">
    <div class="modal-header">
      <h2>E-Prescription</h2>
    </div>

    <div class="modal-body">
      <!-- Doctor / Clinic Info -->
      <div class="clinic-header">
        <h3>Dr. John</h3>
        <p>MBBS, MD (General Medicine)</p>
        <p>Tel: +94 77 123 4567 | Email: john@gmail.com</p>
      </div>

      <hr class="divider">

      <!-- Patient Info -->
      <div class="patient-info">
        <p><strong>Patient:</strong> Sarah</p>
        <p><strong>Age / Sex:</strong> 32 / Female</p>
        <p><strong>Date:</strong> 2025-10-22</p>
        <p><strong>Prescription ID:</strong> RX-10258</p>
      </div>

      <div class="diagnosis">
        <p><strong>Diagnosis:</strong> Acute Sinus Infection</p>
      </div>

      <hr class="divider">

      <!-- Medication Table -->
      <div class="medication-list">
        <table>
          <thead>
            <tr>
              <th>Drug Name</th>
              <th>Formulation</th>
              <th>Route</th>
              <th>Dosage</th>
              <th>Frequency</th>
              <th>Duration</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Amoxicillin</td>
              <td>500 mg Capsule</td>
              <td>Oral</td>
              <td>1 Capsule</td>
              <td>BD (Twice Daily)</td>
              <td>7 Days</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="special-instructions">
        <p><strong>Special Instructions:</strong> Take with food. Do not skip doses.</p>
      </div>

      <div class="pharmacy-note">
        <p><strong>Note to Pharmacy:</strong> No brand substitution permitted.</p>
      </div>

      <div class="validity">
        <p><strong>Valid Until:</strong> 2025-11-05</p>
      </div>

      <hr class="divider">

      <div class="doctor-signature">
        <p><strong>Dr. John Doe</strong></p>
        <p>Signature & Date</p>
      </div>
    </div>

    <div class="modal-footer">
      <a href="#" class="btn btn-primary">Print Prescription</a>
      <a href="#" class="btn btn-secondary" onclick="closePrescriptionModal()">Close</a>
    </div>
  </div>
</div>

<script>
function closePrescriptionModal() {
  // Find the prescription overlay and hide it
  const overlay = document.querySelector('.prescription-overlay');
  if (overlay) {
    overlay.style.display = 'none';
  }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
  const overlay = document.querySelector('.prescription-overlay');
  if (e.target === overlay) {
    closePrescriptionModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closePrescriptionModal();
  }
});
</script>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form/view_prescription.css?v=<?php echo filemtime(APPROOT.'/public/css/components/form/view_prescription.css'); ?>">

<!-- Prescription View Popup -->
<div class="prescription-overlay" id="prescriptionPopup" style="display: none;">
  <div class="prescription-modal">
    <div class="modal-header">
      <h2>E-Prescription</h2>
    </div>

    <div class="modal-body">
      <!-- Doctor / Clinic Info -->
      <div class="clinic-header">
        <h3 id="vp-doctor-name">Doctor Name</h3>
        <p>MBBS, MD (General Medicine)</p>
        <p id="vp-doctor-contact">Contact info</p>
      </div>

      <hr class="divider">

      <!-- Patient Info -->
      <div class="patient-info">
        <p><strong>Patient:</strong> <span id="vp-patient-name"></span></p>
        <p><strong>Date:</strong> <span id="vp-date"></span></p>
        <p><strong>Prescription ID:</strong> <span id="vp-prescription-id"></span></p>
      </div>

      <div class="diagnosis">
        <p><strong>Diagnosis:</strong> <span id="vp-diagnosis"></span></p>
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
              <td id="vp-drug"></td>
              <td id="vp-formulation"></td>
              <td id="vp-route"></td>
              <td id="vp-dosage"></td>
              <td id="vp-frequency"></td>
              <td id="vp-duration"></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="special-instructions">
        <p><strong>Special Instructions:</strong> <span id="vp-special-instructions"></span></p>
      </div>

      <div class="pharmacy-note">
        <p><strong>Note to Pharmacy:</strong> <span id="vp-pharmacy-note"></span></p>
      </div>

      <div class="validity">
        <p><strong>Valid Until:</strong> <span id="vp-valid-until"></span></p>
      </div>

      <hr class="divider">

      <div class="doctor-signature">
        <p><strong id="vp-doctor-sign"></strong></p>
        <p>Signature & Date</p>
      </div>
    </div>

    <div class="modal-footer">
      <a href="#" class="btn btn-primary" onclick="downloadPrescription(event)">Download</a>
      <a href="#" class="btn btn-secondary" onclick="closePrescriptionModal()">Close</a>
    </div>
  </div>
</div>

<!-- Modal functionality is handled by modal-manager.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPrescription(e) {
    if (e) e.preventDefault();
    const element = document.querySelector('.modal-body');
    const opt = {
        margin:       0.5,
        filename:     'Prescription.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    // Implement simple download logic
    html2pdf().set(opt).from(element).save();
}
</script>


<?php
class Patient extends Controller {
    private $prescriptionModel;

    public function __construct() {
        // Load the Prescription model
        $this->prescriptionModel = $this->model('Prescription');
    }

    // 🩺 Patient Prescriptions Page
    public function patientPrescriptions() {
        
        // Get the currently logged-in patient ID
        if (!isset($_SESSION['user_id'])) {

            // redirect if not logged in
            redirect('Users/login');
            return; 
        }

        $patient_id = $_SESSION['user_id'];

        // Fetch prescriptions for that patient
        $prescriptions = $this->prescriptionModel->getPrescriptionsByPatient($patient_id);

        // Send data to view
        $data = [
            'title' => 'My Prescriptions',
            'prescriptions' => $prescriptions
        ];

        $this->view('pages/v_patientPrescriptions', $data);
    }

    // 🩺 View one prescription (used by modal)
    public function viewPrescription($id) {
        $prescription = $this->prescriptionModel->getPrescriptionById($id);

        // Extra safety: Only allow the patient who owns this prescription to view it
        if ($prescription && $prescription->patient_id == $_SESSION['user_id']) {
            $this->view('pages/v_patient_view_prescription', ['prescription' => $prescription]);
        } else {
            echo "<p>Unauthorized or prescription not found.</p>";
        }
    }
}
?>

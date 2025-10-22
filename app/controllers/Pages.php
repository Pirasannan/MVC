<?php 

class Pages extends Controller{
    private $pagesModel;
    public function __construct() {
        
        $this->pagesModel = $this->model('M_Pages');
    
    }
    
    public function index() {
        $data = [];
        $this->view('pages/v_index',$data);
    }




    public function adminProfile() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_profile', []);
    }

    public function adminDashboard() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_dashboard', []);
    }

    public function adminDoctors() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_doctors', []);
    }

    public function adminPatients() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_patients', []);
    }

    public function adminNotifications() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_notifications', []);
    }

    public function adminRecords() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_admin_records', []);
    }


    public function adminSettings() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_admin_settings', []);
    }






    public function doctorDashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_dashboard', []);
    }

    public function doctorPrescriptions() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $prescriptionModel = $this->model('Prescription');
        $doctor_id = $_SESSION['user_id'] ?? null;
        $prescriptions = [];
        $patient_name = null;
        
        if ($doctor_id) {
            $prescriptions = $prescriptionModel->getPrescriptionsByDoctor($doctor_id);
            
            // If prescription was just created or updated, get the patient name from the most recent prescription
            if ((isset($_GET['created']) && $_GET['created'] == '1') || (isset($_GET['updated']) && $_GET['updated'] == '1')) {
                if (!empty($prescriptions)) {
                    $most_recent = $prescriptions[0]; // First prescription is most recent due to ORDER BY created_at DESC
                    $patient_name = $most_recent->patient_name ?? 'Patient';
                }
            }
        }
        
        $this->view('pages/v_doctor_prescriptions', [
            'title' => 'My Issued Prescriptions',
            'prescriptions' => $prescriptions,
            'patient_name' => $patient_name
        ]);
    }

    public function doctorMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_messages', []);
    }

    public function doctorMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_medicalrecords', []);
    }

    public function doctorprofile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_profile', []);
    }

    public function doctorAppointments() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_appointments', []);
    }

    public function createprescription() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor'){
            redirect('Pages/index');
            return;
        }
        
        $usersModel = $this->model('M_Users');
        $patients = $usersModel->getPatients();
        
        $this->view('pages/v_doctor_create_prescription', [
            'patients' => $patients
        ]);
    }

    public function doctorSettings() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_doctor_settings', []);
    }

    



    
    public function patientDashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_patient_dashboard', []);
    }

    public function patientPrescriptions() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $prescriptionModel = $this->model('Prescription');
        $patient_id = $_SESSION['user_id'] ?? null;
        $prescriptions = [];
        if ($patient_id) {
            $prescriptions = $prescriptionModel->getPrescriptionsByPatient($patient_id);
        }
        $this->view('pages/v_patient_prescriptions', [
            'title' => 'My Prescriptions',
            'prescriptions' => $prescriptions
        ]);
    }
    
    public function patientMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_messages', []);
    }
    
    public function patientProfile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_profile', []);
    }
    
    public function patientMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_medicalrecords', []);
    }

    public function patientAppointments() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_appointments', []);
    }

    public function patientSettings() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_settings', []);
    }



}   

?>
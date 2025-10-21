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

    public function about() {
        $users = $this->pagesModel->getUsers();

        $data = [
            'users' => $users
        ];
        $this->view('v_about',$data);
    }

    public function adminDashboard() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_admin_dashboard', []);
    }

    public function doctorDashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_dashboard', []);
    }

    public function doctorPrescriptions() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_prescriptions', []);
    }

    Public function doctorMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_messages', []);
    }

    Public function doctorMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_medicalrecords', []);
    }

    Public function doctorprofile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_profile', []);
    }

    Public function doctorAppointments() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_doctor_appointments', []);
    }

    Public function createprescription() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor'){
            return redirect ('Pages/index');
        }
        $this->view('pages/v_doctor_create_prescription', []);
    }

    



    
    public function patientDashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_patient_dashboard', []);
    }

    public function patientPrescriptions() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         return redirect('Pages/index');
        }
        $this->view('pages/v_patient_prescriptions', []);
    }
    
    public function patientMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         return redirect('Pages/index');
        }
        $this->view('pages/v_patient_messages', []);
    }
    
    public function patientProfile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         return redirect('Pages/index');
        }
        $this->view('pages/v_patient_profile', []);
    }
    
    public function patientMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         return redirect('Pages/index');
        }
        $this->view('pages/v_patient_medicalrecords', []);
    }

    public function patientAppointments() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         return redirect('Pages/index');
        }
        $this->view('pages/v_patient_appointments', []);
    }
}   

?>
<?php 

class Pages extends Controller{
    private $pagesModel;
    private $adminModel;
    public function __construct() {
        
        $this->pagesModel = $this->model('M_Pages');
        $this->adminModel = $this->model('M_Admin');
    
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

    public function adminProfile() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_admin_profile', []);
    }


    public function adminDashboard() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

		// Get data from model
		$data = [
			'stats' => $this->adminModel->getDashboardStats(),
			'pendingDoctors' => $this->adminModel->getPendingDoctorsForDashboard(),
			'pendingPatients' => $this->adminModel->getPendingPatientsForDashboard(),
			'recentActivity' => $this->adminModel->getRecentActivityForDashboard()
		];

		// Pass data to view
		$this->view('pages/v_admin_dashboard', $data);
    }

    public function adminDoctors() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }
        $data = [
            'pendingDoctors' => $this->adminModel->getPendingDoctors(),
            'verifiedDoctors' => $this->adminModel->getVerifiedDoctors(),
            'rejectedDoctors' => $this->adminModel->getRejectedDoctors(),
            'inactiveDoctors' => $this->adminModel->getInactiveDoctors()
        ];
        $this->view('pages/v_admin_doctors', $data);
    }

    public function adminDoctorVerification() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'pendingDoctors' => $this->adminModel->getPendingDoctors()
        ];

        $this->view('pages/v_admin_doctor_verification', $data);
    }

    public function approveDoctor() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $doctor_id = $input['doctor_id'] ?? null;

        if (!$doctor_id) {
            echo json_encode(['success' => false, 'message' => 'Doctor ID required']);
            return;
        }

        $result = $this->adminModel->approveDoctor($doctor_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor approved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve doctor']);
        }
    }

    public function rejectDoctor() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $doctor_id = $input['doctor_id'] ?? null;
        $reason = $input['reason'] ?? null;

        if (!$doctor_id || !$reason) {
            echo json_encode(['success' => false, 'message' => 'Doctor ID and reason required']);
            return;
        }

        $result = $this->adminModel->rejectDoctor($doctor_id, $reason);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject doctor']);
        }
    }

    public function approvePatient() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $patient_id = $input['patient_id'] ?? null;

        if (!$patient_id) {
            echo json_encode(['success' => false, 'message' => 'Patient ID required']);
            return;
        }

        $result = $this->adminModel->approvePatient($patient_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Patient approved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve patient']);
        }
    }

    public function rejectPatient() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $patient_id = $input['patient_id'] ?? null;
        $reason = $input['reason'] ?? null;

        if (!$patient_id || !$reason) {
            echo json_encode(['success' => false, 'message' => 'Patient ID and reason required']);
            return;
        }

        $result = $this->adminModel->rejectPatient($patient_id, $reason);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Patient rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject patient']);
        }
    }

    public function adminPatients() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'pendingPatients' => $this->adminModel->getPendingPatients(),
            'verifiedPatients' => $this->adminModel->getVerifiedPatients(),
            'rejectedPatients' => $this->adminModel->getRejectedPatients(),
            'inactivePatients' => $this->adminModel->getInactivePatients()
        ];

        $this->view('pages/v_admin_patients', $data);
    }

    public function adminNotifications() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'recentNotifications' => $this->adminModel->getRecentNotifications(5)
        ];

        $this->view('pages/v_admin_notifications', $data);
    }

    public function sendNotification() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [
            'recipient_type' => $input['recipient_type'] ?? 'all',
            'recipient_id' => $input['recipient_id'] ?? null,
            'title' => $input['title'] ?? '',
            'message' => $input['message'] ?? '',
            'notification_type' => $input['notification_type'] ?? 'info'
        ];

        // Validate required fields
        if (empty($data['title']) || empty($data['message'])) {
            echo json_encode(['success' => false, 'message' => 'Title and message are required']);
            return;
        }

        $result = $this->adminModel->createNotification($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Notification sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send notification']);
        }
    }

    public function adminAllDoctors() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'verifiedDoctors' => $this->adminModel->getVerifiedDoctors()
        ];

        $this->view('pages/v_admin_all_doctors', $data);
    }

    public function adminRejectedDoctors() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'rejectedDoctors' => $this->adminModel->getRejectedDoctors()
        ];

        $this->view('pages/v_admin_rejected_doctors', $data);
    }

    public function adminPatientVerification() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'pendingPatients' => $this->adminModel->getPendingPatients()
        ];

        $this->view('pages/v_admin_patient_verification', $data);
    }

    public function adminAllPatients() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'verifiedPatients' => $this->adminModel->getVerifiedPatients()
        ];

        $this->view('pages/v_admin_all_patients', $data);
    }

    public function adminRejectedPatients() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'rejectedPatients' => $this->adminModel->getRejectedPatients()
        ];

        $this->view('pages/v_admin_rejected_patients', $data);
    }

    public function adminInactivePatients() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'inactivePatients' => $this->adminModel->getInactivePatients()
        ];

        $this->view('pages/v_admin_inactive_patients', $data);
    }

    public function adminReports() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'reportedMessages' => $this->adminModel->getReportedMessages()
        ];

        $this->view('pages/v_admin_reports', $data);
    }

    public function adminAllNotifications() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'allNotifications' => $this->adminModel->getAllNotifications()
        ];

        $this->view('pages/v_admin_all_notifications', $data);
    }

    public function adminResolvedReports() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'resolvedReports' => $this->adminModel->getResolvedReports()
        ];

        $this->view('pages/v_admin_resolved_reports', $data);
    }

    public function adminProfileUpdate() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'user' => $this->adminModel->getAdminProfile($_SESSION['user_id'])
        ];

        $this->view('pages/v_admin_profile_update', $data);
    }

    public function adminChangePassword() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $this->view('pages/v_admin_change_password', []);
    }

    public function adminActiveSessions() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'activeSessions' => $this->adminModel->getActiveSessions($_SESSION['user_id'])
        ];

        $this->view('pages/v_admin_active_sessions', $data);
    }

    public function adminSecurityOverview() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'securityInfo' => $this->adminModel->getSecurityOverview($_SESSION['user_id'])
        ];

        $this->view('pages/v_admin_security_overview', $data);
    }

    public function adminActivityLog() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'activityLog' => $this->adminModel->getAdminActivityLog($_SESSION['user_id'])
        ];

        $this->view('pages/v_admin_activity_log', $data);
    }

    public function adminSystemActivityLog() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'systemActivity' => $this->adminModel->getSystemActivityLog()
        ];

        $this->view('pages/v_admin_system_activity_log', $data);
    }

    public function adminRecords() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }
        $this->view('pages/v_admin_records', []);
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
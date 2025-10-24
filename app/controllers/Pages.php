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
            redirect('Pages/index');
            return;
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
            redirect('Pages/index');
            return;
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
            redirect('Pages/index');
            return;
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

    public function adminMessages() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }
        
        // Handle POST requests (sending announcements, moderating messages)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'send_announcement':
                        $_SESSION['message'] = 'Announcement sent successfully! (Demo Mode)';
                        break;
                        
                    case 'moderate_message':
                        $_SESSION['message'] = 'Message moderated successfully! (Demo Mode)';
                        break;
                }
            }
        }
        
        // Mock data for demo purposes (replace with database calls when ready)
        $mockRecentMessages = [
            (object)[
                'id' => 1,
                'doctor_name' => 'Sarah Johnson',
                'patient_name' => 'Michael Chen',
                'subject' => 'Prescription Follow-up',
                'message_text' => 'Thank you for the prescription. I have a question about the dosage timing and when I should take it with meals.',
                'sent_at' => '2024-10-23 10:30:00',
                'status' => 'delivered'
            ],
            (object)[
                'id' => 2,
                'doctor_name' => 'Robert Wilson',
                'patient_name' => 'Emma Davis',
                'subject' => 'Lab Results Discussion',
                'message_text' => 'The lab results look good. When should I schedule my next appointment for follow-up?',
                'sent_at' => '2024-10-22 14:15:00',
                'status' => 'read'
            ],
            (object)[
                'id' => 3,
                'doctor_name' => 'Michael Chen',
                'patient_name' => 'Lisa Anderson',
                'subject' => 'Medication Side Effects',
                'message_text' => 'I am experiencing some side effects from the new medication. Should I continue taking it?',
                'sent_at' => '2024-10-21 16:45:00',
                'status' => 'pending'
            ],
            (object)[
                'id' => 4,
                'doctor_name' => 'Emily Davis',
                'patient_name' => 'David Brown',
                'subject' => 'Appointment Confirmation',
                'message_text' => 'Thank you for the quick response. I feel much better now and would like to schedule a follow-up.',
                'sent_at' => '2024-10-20 11:20:00',
                'status' => 'sent'
            ]
        ];
        
        $data = [
            'total_messages' => 156,
            'active_conversations' => 42,
            'messages_today' => 18,
            'recent_messages' => $mockRecentMessages
        ];
        
        $this->view('pages/v_admin_messages', $data);
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

        // Show alert that profile was updated
        echo "<script>alert('Profile updated successfully!'); window.history.back();</script>";
        exit;
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

    Public function doctorMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        
        $doctorId = $_SESSION['user_id'];
        
        // Handle POST requests (sending messages, marking as read)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'send_message':
                        $_SESSION['message'] = 'Message sent successfully! (Demo Mode)';
                        break;
                        
                    case 'mark_read':
                        $_SESSION['message'] = 'Messages marked as read! (Demo Mode)';
                        break;
                }
            }
        }
        
        // Mock data for demo purposes (replace with database calls when ready)
        $mockConversations = [
            (object)[
                'patient_id' => 1,
                'patient_name' => 'Sarah Johnson',
                'patient_email' => 'sarah.johnson@email.com',
                'last_message' => 'Thank you for the prescription. I have a question about the dosage timing.',
                'last_message_time' => '2024-10-23 10:30:00',
                'is_read' => false,
                'sender_type' => 'patient',
                'unread_count' => 2
            ],
            (object)[
                'patient_id' => 2,
                'patient_name' => 'Michael Chen',
                'patient_email' => 'michael.chen@email.com',
                'last_message' => 'The lab results look good. When should I schedule my next appointment?',
                'last_message_time' => '2024-10-22 14:15:00',
                'is_read' => true,
                'sender_type' => 'patient',
                'unread_count' => 0
            ],
            (object)[
                'patient_id' => 3,
                'patient_name' => 'Emma Davis',
                'patient_email' => 'emma.davis@email.com',
                'last_message' => 'I am experiencing some side effects from the new medication.',
                'last_message_time' => '2024-10-21 16:45:00',
                'is_read' => false,
                'sender_type' => 'patient',
                'unread_count' => 1
            ],
            (object)[
                'patient_id' => 4,
                'patient_name' => 'Robert Wilson',
                'patient_email' => 'robert.wilson@email.com',
                'last_message' => 'Thank you for the quick response. I feel much better now.',
                'last_message_time' => '2024-10-20 11:20:00',
                'is_read' => true,
                'sender_type' => 'doctor',
                'unread_count' => 0
            ]
        ];
        
        $mockPatients = [
            (object)['id' => 1, 'name' => 'Sarah Johnson', 'email' => 'sarah.johnson@email.com'],
            (object)['id' => 2, 'name' => 'Michael Chen', 'email' => 'michael.chen@email.com'],
            (object)['id' => 3, 'name' => 'Emma Davis', 'email' => 'emma.davis@email.com'],
            (object)['id' => 4, 'name' => 'Robert Wilson', 'email' => 'robert.wilson@email.com'],
            (object)['id' => 5, 'name' => 'Lisa Anderson', 'email' => 'lisa.anderson@email.com'],
            (object)['id' => 6, 'name' => 'David Brown', 'email' => 'david.brown@email.com']
        ];
        
        // Calculate unread count from mock data
        $unreadCount = 0;
        foreach ($mockConversations as $conv) {
            $unreadCount += $conv->unread_count;
        }
        
        $data = [
            'user_id' => $doctorId,
            'user_name' => $_SESSION['user_name'],
            'unread_count' => $unreadCount,
            'conversations' => $mockConversations,
            'patients' => $mockPatients
        ];
        
        $this->view('pages/v_doctor_messages', $data);
    }

    public function doctorMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        $this->view('pages/v_doctor_medicalrecords', []);
    }

    Public function doctorprofile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }
        
        // Load verification model to get verification status
        $verificationModel = $this->model('M_Verification');
        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'];
        $userName = $_SESSION['user_name'];
        
        // Get verification data
        $verification = $verificationModel->getVerificationByUserId($userId);
        
        $data = [
            'user_id' => $userId,
            'user_email' => $userEmail,
            'user_name' => $userName,
            'verification' => $verification
        ];
        
        $this->view('pages/v_doctor_profile', $data);
    }

    public function doctorAppointments() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        
        // Redirect to the proper Appointments controller method
        redirect('Appointments/doctor');
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
            return redirect('Pages/index');
        }
        
        // Get patient data from session
        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'];
        $userName = $_SESSION['user_name'];
        
        $data = [
            'user_id' => $userId,
            'user_email' => $userEmail,
            'user_name' => $userName
        ];
        
        $this->view('pages/v_patient_profile', $data);
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
        
        // Redirect to the proper Appointments controller method
        redirect('Appointments/my');
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
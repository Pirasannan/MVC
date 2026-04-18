<?php 

require_once '../app/libraries/FileValidator.php';

class Pages extends Controller{
    private $pagesModel;
    private $adminModel;
    private $verificationModel;
    private $appointmentModel;
    private $prescriptionModel;
    private $messageModel;
    private $userModel;
    public function __construct() {
        
        $this->pagesModel = $this->model('M_Pages');
        $this->adminModel = $this->model('M_Admin');
        $this->verificationModel = $this->model('M_Verification');
        $this->appointmentModel = $this->model('Appointment');
        $this->prescriptionModel = $this->model('Prescription');
        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('M_Users');
    
    }
    
    
    public function index() {
        $this->view('pages/v_index');
    }

    public function error() {
        $this->view('pages/error');
    }


    public function adminProfile() {
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }

        $adminId = $_SESSION['user_id'];
        $adminProfile = $this->adminModel->getAdminProfile($adminId);

        $adminName = $adminProfile->name ?? ($_SESSION['user_name'] ?? 'Admin');
        $adminEmail = $adminProfile->email ?? ($_SESSION['user_email'] ?? '');
        $nameErr = '';
        $emailErr = '';
        $profileImageErr = '';
        $profileImageSuccess = '';
        $profileSuccess = '';
        $currentProfileImage = $this->userModel->getUserProfileImage($adminId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $formType = trim($_POST['form_type'] ?? '');

            if ($formType === 'update_profile_image') {
                if (!isset($_FILES['profile_image']) || empty($_FILES['profile_image']['tmp_name'])) {
                    $profileImageErr = 'Please choose an image first.';
                } else {
                    $file = $_FILES['profile_image'];
                    $validation = FileValidator::validateFile($file);

                    if (!$validation['valid']) {
                        $profileImageErr = implode(', ', $validation['errors']);
                    } else {
                        $uploadDir = $this->ensurePatientProfileImageDirectory($adminId);

                        if ($uploadDir === false) {
                            $profileImageErr = 'Failed to prepare upload directory.';
                        } else {
                            try {
                                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $fileName = 'profile_' . $adminId . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
                                $targetAbsolutePath = $uploadDir['absolute'] . $fileName;

                                if (move_uploaded_file($file['tmp_name'], $targetAbsolutePath)) {
                                    chmod($targetAbsolutePath, 0644);
                                    $newRelativePath = $uploadDir['relative'] . $fileName;

                                    if ($this->userModel->updateUserProfileImage($adminId, $newRelativePath)) {
                                        if (!empty($currentProfileImage) && $currentProfileImage !== $newRelativePath) {
                                            $oldAbsolutePath = dirname(APPROOT) . '/public/' . $currentProfileImage;
                                            if (file_exists($oldAbsolutePath)) {
                                                @unlink($oldAbsolutePath);
                                            }
                                        }

                                        $currentProfileImage = $newRelativePath;
                                        $_SESSION['user_profile_image'] = $newRelativePath;
                                        $profileImageSuccess = 'Profile picture updated successfully.';
                                        $this->adminModel->logAdminAction($adminId, 'Profile Picture Updated', 'Updated profile picture');
                                    } else {
                                        @unlink($targetAbsolutePath);
                                        $profileImageErr = 'Failed to save profile picture path.';
                                    }
                                } else {
                                    $profileImageErr = 'Failed to upload selected image.';
                                }
                            } catch (Throwable $exception) {
                                $profileImageErr = 'Image upload failed. Please try again.';
                            }
                        }
                    }
                }
            }

            if ($formType === 'update_profile_details' || $formType === 'update_name') {
                $submittedName = trim($_POST['user_name'] ?? '');
                $submittedEmail = trim($_POST['user_email'] ?? '');

                if ($submittedName === '') {
                    $nameErr = 'Please enter your name.';
                } elseif (strlen($submittedName) < 3) {
                    $nameErr = 'Name must be at least 3 characters.';
                }

                if ($submittedEmail === '') {
                    $emailErr = 'Please enter your email.';
                } elseif (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = 'Please enter a valid email address.';
                } elseif ($this->userModel->findUserByEmailExcludingUser($submittedEmail, $adminId)) {
                    $emailErr = 'This email is already in use.';
                }

                if ($nameErr === '' && $emailErr === '') {
                    if ($this->userModel->updateUserProfile($adminId, $submittedName, $submittedEmail)) {
                        $_SESSION['user_name'] = $submittedName;
                        $_SESSION['user_email'] = $submittedEmail;
                        $adminName = $submittedName;
                        $adminEmail = $submittedEmail;
                        $profileSuccess = 'Profile updated successfully.';
                        $this->adminModel->logAdminAction($adminId, 'Profile Updated', 'Updated personal information');
                    }
                } else {
                    $adminName = $submittedName;
                    $adminEmail = $submittedEmail;
                }
            }
        }

        $recentActivity = $this->adminModel->getAdminActivityLog($adminId);

        $data = [
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'admin_email' => $adminEmail,
            'role' => 'Admin',
            'status' => $adminProfile->status ?? 'active',
            'profile_success' => $profileSuccess,
            'profile_image' => $currentProfileImage,
            'profile_image_success' => $profileImageSuccess,
            'profile_image_err' => $profileImageErr,
            'name_err' => $nameErr,
            'email_err' => $emailErr,
            'recent_activity' => $recentActivity
        ];

        $this->view('pages/v_admin_profile', $data);
    }

    public function doctorVideoConsultation() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'patient_name' => 'Sarah Wilson',
            'doctor_name' => 'Dr. John Smith',
            'appointment_time' => 'Today at 2:30 PM',
            'appointment_type' => 'Follow-up Consultation'
        ];
        
        $this->view('pages/Videoconsultation/v_doctor_videoconsultation', $data);
    }

    public function patientVideoConsultation() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'patient_name' => 'Sarah Wilson',
            'doctor_name' => 'Dr. John Smith',
            'appointment_time' => 'Today at 2:30 PM',
            'appointment_type' => 'Follow-up Consultation'
        ];
        
        $this->view('pages/Videoconsultation/v_patient_videoconsultation', $data);
    }

    public function doctorPrecall() {
        // Precall now requires an appointment ID — use VideoCall/precall/{id}
        redirect('Appointments/doctor');
    }

    public function patientPrecall() {
        // Precall now requires an appointment ID — use VideoCall/precall/{id}
        redirect('Appointments/my');
    }

    public function doctorVideoCall() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'patient_name' => 'Sarah Wilson',
            'doctor_name' => 'Dr. John Smith',
            'appointment_time' => 'Today at 2:30 PM',
            'appointment_type' => 'Follow-up Consultation'
        ];
        
        $this->view('pages/Videoconsultation/v_doctor_videocall', $data);
    }

    public function patientVideoCall() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'patient_name' => 'Sarah Wilson',
            'doctor_name' => 'Dr. John Smith',
            'appointment_time' => 'Today at 2:30 PM',
            'appointment_type' => 'Follow-up Consultation'
        ];
        
        $this->view('pages/Videoconsultation/v_patient_videocall', $data);
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
            'pendingDoctors' => $this->verificationModel->getPendingDoctorVerificationsForAdmin(),
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
            'pendingVerifications' => $this->verificationModel->getPendingDoctorVerificationsForAdmin()
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

        $verification = $this->verificationModel->getVerificationByUserId($doctor_id);
        if (!$verification) {
            echo json_encode(['success' => false, 'message' => 'No verification document found for this doctor']);
            return;
        }

        $accountUpdated = $this->adminModel->approveDoctor($doctor_id);
        $verificationUpdated = $this->verificationModel->updateStatus((int)$verification->id, 'verified');
        
        if ($accountUpdated && $verificationUpdated) {
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

        $verification = $this->verificationModel->getVerificationByUserId($doctor_id);
        if (!$verification) {
            echo json_encode(['success' => false, 'message' => 'No verification document found for this doctor']);
            return;
        }

        $accountUpdated = $this->adminModel->rejectDoctor($doctor_id, $reason);
        $verificationUpdated = $this->verificationModel->updateStatus((int)$verification->id, 'rejected', $reason);
        
        if ($accountUpdated && $verificationUpdated) {
            echo json_encode(['success' => true, 'message' => 'Doctor rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject doctor']);
        }
    }

    public function suspendDoctor() {
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

        $result = $this->adminModel->suspendDoctor($doctor_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor suspended successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to suspend doctor']);
        }
    }

    public function deactivateDoctor() {
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

        $result = $this->adminModel->deactivateDoctor($doctor_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor deactivated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to deactivate doctor']);
        }
    }

    public function reactivateDoctor() {
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

        $result = $this->adminModel->reactivateDoctor($doctor_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Doctor reactivated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reactivate doctor']);
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

    public function deactivatePatient() {
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

        $result = $this->adminModel->deactivatePatient($patient_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Patient deactivated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to deactivate patient']);
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
            'rejectedDoctors' => $this->adminModel->getRejectedDoctors(),
            'inactiveDoctors' => $this->adminModel->getInactiveDoctors()
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

        $doctorId = $_SESSION['user_id'] ?? null;
        $dashboardData = $this->buildDoctorDashboardData($doctorId);
        $dashboardData['title'] = 'Doctor Dashboard';

        $this->view('pages/v_doctor_dashboard', $dashboardData);
    }

    private function buildDoctorDashboardData($doctorId) {
        $today = new DateTimeImmutable('today');
        $now = new DateTimeImmutable('now');

        $appointments = $doctorId ? $this->appointmentModel->getByDoctor($doctorId) : [];
        $prescriptions = $doctorId ? $this->prescriptionModel->getPrescriptionsByDoctor($doctorId) : [];

        $todayAppointments = 0;
        $upcomingAppointments = [];
        $prescribedPatientIds = [];

        foreach ($prescriptions as $prescription) {
            if (($prescription->is_deleted ?? 'not_deleted') !== 'deleted') {
                $prescribedPatientIds[$prescription->patient_id] = true;
            }
        }

        foreach ($appointments as $appointment) {
            $startsAt = new DateTimeImmutable($appointment->starts_at);
            $status = strtolower((string)($appointment->status ?? ''));
            $isActiveStatus = !in_array($status, ['cancelled', 'rejected'], true);

            if ($startsAt->format('Y-m-d') === $today->format('Y-m-d') && $isActiveStatus) {
                $todayAppointments++;
            }

            if ($startsAt >= $now && $isActiveStatus) {
                $upcomingAppointments[] = $appointment;
            }
        }

        usort($upcomingAppointments, function ($left, $right) {
            return strtotime($left->starts_at) <=> strtotime($right->starts_at);
        });

        $recentPrescriptions = [];
        foreach ($prescriptions as $prescription) {
            if (($prescription->is_deleted ?? 'not_deleted') !== 'deleted') {
                $recentPrescriptions[] = $prescription;
            }
        }
        $recentPrescriptions = array_slice($recentPrescriptions, 0, 3);

        $pendingPrescriptionPatients = [];
        foreach ($appointments as $appointment) {
            $startsAt = new DateTimeImmutable($appointment->starts_at);
            $status = strtolower((string)($appointment->status ?? ''));

            if ($startsAt > $now || !in_array($status, ['approved', 'completed'], true)) {
                continue;
            }

            if (!isset($prescribedPatientIds[$appointment->patient_id])) {
                $pendingPrescriptionPatients[$appointment->patient_id] = true;
            }
        }

        return [
            'todayAppointmentsCount' => $todayAppointments,
            'prescribedPatientsCount' => count($prescribedPatientIds),
            'unreadMessagesCount' => $doctorId ? (int) $this->messageModel->getUnreadCount($doctorId) : 0,
            'pendingPrescriptionsCount' => count($pendingPrescriptionPatients),
            'upcomingAppointments' => $upcomingAppointments,
            'recentPrescriptions' => $recentPrescriptions,
        ];
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
        
        // Mock data for demo purposes (replace with database calls when ready)
        $mockConversations = [
            (object)[
                'patient_id' => 1,
                'patient_name' => 'Sarah Johnson',
                'last_message' => 'Thank you for the prescription. I have a question about the dosage timing.',
                'last_message_time' => '2024-01-25 10:30:00',
                'unread_count' => 2
            ],
            (object)[
                'patient_id' => 2,
                'patient_name' => 'Michael Chen',
                'last_message' => 'The lab results look good. When should I schedule my next appointment?',
                'last_message_time' => '2024-01-24 14:15:00',
                'unread_count' => 0
            ],
            (object)[
                'patient_id' => 3,
                'patient_name' => 'Emma Davis',
                'last_message' => 'I am experiencing some side effects from the new medication.',
                'last_message_time' => '2024-01-23 16:45:00',
                'unread_count' => 1
            ],
            (object)[
                'patient_id' => 4,
                'patient_name' => 'Robert Wilson',
                'last_message' => 'Thank you for the quick response. I feel much better now.',
                'last_message_time' => '2024-01-22 11:20:00',
                'unread_count' => 0
            ]
        ];
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['user_name'],
            'conversations' => $mockConversations
        ];
        
        $this->view('pages/messages/v_doctor_messages', $data);
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
        $userNameErr = '';
        $userEmailErr = '';
        $profileImageErr = '';
        $profileImageSuccess = '';
        $profileSuccess = '';
        $currentProfileImage = $this->userModel->getUserProfileImage($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $formType = trim($_POST['form_type'] ?? '');

            if ($formType === 'update_profile_image') {
                if (!isset($_FILES['profile_image']) || empty($_FILES['profile_image']['tmp_name'])) {
                    $profileImageErr = 'Please choose an image first.';
                } else {
                    $file = $_FILES['profile_image'];
                    $validation = FileValidator::validateFile($file);

                    if (!$validation['valid']) {
                        $profileImageErr = implode(', ', $validation['errors']);
                    } else {
                        $uploadDir = $this->ensurePatientProfileImageDirectory($userId);

                        if ($uploadDir === false) {
                            $profileImageErr = 'Failed to prepare upload directory.';
                        } else {
                            try {
                                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $fileName = 'profile_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
                                $targetAbsolutePath = $uploadDir['absolute'] . $fileName;

                                if (move_uploaded_file($file['tmp_name'], $targetAbsolutePath)) {
                                    chmod($targetAbsolutePath, 0644);
                                    $newRelativePath = $uploadDir['relative'] . $fileName;

                                    if ($this->userModel->updateUserProfileImage($userId, $newRelativePath)) {
                                        if (!empty($currentProfileImage) && $currentProfileImage !== $newRelativePath) {
                                            $oldAbsolutePath = dirname(APPROOT) . '/public/' . $currentProfileImage;
                                            if (file_exists($oldAbsolutePath)) {
                                                @unlink($oldAbsolutePath);
                                            }
                                        }

                                        $currentProfileImage = $newRelativePath;
                                        $_SESSION['user_profile_image'] = $newRelativePath;
                                        $profileImageSuccess = 'Profile picture updated successfully.';
                                    } else {
                                        @unlink($targetAbsolutePath);
                                        $profileImageErr = 'Failed to save profile picture path.';
                                    }
                                } else {
                                    $profileImageErr = 'Failed to upload selected image.';
                                }
                            } catch (Throwable $exception) {
                                $profileImageErr = 'Image upload failed. Please try again.';
                            }
                        }
                    }
                }
            }

            if ($formType === 'update_profile_details' || $formType === 'update_name') {
                $submittedName = trim($_POST['user_name'] ?? '');
                $submittedEmail = trim($_POST['user_email'] ?? '');

                if ($submittedName === '') {
                    $userNameErr = 'Please enter your name.';
                } elseif (strlen($submittedName) < 3) {
                    $userNameErr = 'Name must be at least 3 characters.';
                }

                if ($submittedEmail === '') {
                    $userEmailErr = 'Please enter your email.';
                } elseif (!filter_var($submittedEmail, FILTER_VALIDATE_EMAIL)) {
                    $userEmailErr = 'Please enter a valid email address.';
                } elseif ($this->userModel->findUserByEmailExcludingUser($submittedEmail, $userId)) {
                    $userEmailErr = 'This email is already in use.';
                }

                if ($userNameErr === '' && $userEmailErr === '') {
                    if ($this->userModel->updateUserProfile($userId, $submittedName, $submittedEmail)) {
                        $_SESSION['user_name'] = $submittedName;
                        $_SESSION['user_email'] = $submittedEmail;
                        $userName = $submittedName;
                        $userEmail = $submittedEmail;
                        $profileSuccess = 'Profile updated successfully.';
                    }
                } else {
                    $userName = $submittedName;
                    $userEmail = $submittedEmail;
                }
            }
        }
        
        // Get verification data
        $verification = $verificationModel->getVerificationByUserId($userId);
        
        $data = [
            'user_id' => $userId,
            'user_email' => $userEmail,
            'user_name' => $userName,
            'verification' => $verification,
            'user_name_err' => $userNameErr,
            'user_email_err' => $userEmailErr,
            'profile_image' => $currentProfileImage,
            'profile_image_success' => $profileImageSuccess,
            'profile_image_err' => $profileImageErr,
            'profile_success' => $profileSuccess
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

        $patientId = $_SESSION['user_id'] ?? null;
        $dashboardData = $this->buildPatientDashboardData($patientId);
        $dashboardData['title'] = 'Patient Dashboard';

        $this->view('pages/v_patient_dashboard', $dashboardData);
    }

    private function buildPatientDashboardData($patientId) {
        $today = new DateTimeImmutable('today');
        $now = new DateTimeImmutable('now');

        $appointments = $patientId ? $this->appointmentModel->getByPatient($patientId) : [];
        $todayAppointments = 0;
        $upcomingAppointments = [];

        foreach ($appointments as $appointment) {
            $startsAt = new DateTimeImmutable($appointment->starts_at);
            $status = strtolower((string)($appointment->status ?? ''));

            if ($startsAt->format('Y-m-d') === $today->format('Y-m-d')) {
                $todayAppointments++;
            }

            if ($startsAt >= $now && !in_array($status, ['cancelled', 'rejected', 'completed'], true)) {
                $upcomingAppointments[] = $appointment;
            }
        }

        usort($upcomingAppointments, function ($left, $right) {
            return strtotime($left->starts_at) <=> strtotime($right->starts_at);
        });

        $prescriptions = $patientId ? $this->prescriptionModel->getPrescriptionsByPatient($patientId) : [];
        $activeMedications = [];
        $recentPrescriptions = array_slice($prescriptions, 0, 3);

        foreach ($prescriptions as $prescription) {
            $isDeleted = strtolower((string)($prescription->is_deleted ?? 'not_deleted')) === 'not_deleted';
            $validUntil = !empty($prescription->valid_until)
                ? new DateTimeImmutable($prescription->valid_until)
                : null;

            if ($isDeleted && (!$validUntil || $validUntil >= $today)) {
                $activeMedications[] = $prescription;
            }
        }

        $unreadMessages = $patientId ? (int) $this->messageModel->getUnreadCount($patientId) : 0;

        return [
            'todayAppointmentsCount' => $todayAppointments,
            'activeMedicationsCount' => count($activeMedications),
            'unreadMessagesCount' => $unreadMessages,
            'recentPrescriptionsCount' => count($recentPrescriptions),
            'upcomingAppointments' => $upcomingAppointments,
            'recentPrescriptions' => $recentPrescriptions,
        ];
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
    
    public function patientProfile() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            return redirect('Pages/index');
        }
        
        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'];
        $userName = $_SESSION['user_name'];
        $nameErr = '';
        $nameSuccess = '';
        $medicalSuccess = '';
        $profileImageSuccess = '';
        $profileImageErr = '';
        $currentProfileImage = $this->userModel->getUserProfileImage($userId);

        $medicalInfo = $this->userModel->getPatientMedicalInfo($userId);

        $medicalForm = [
            'blood_type' => $medicalInfo->blood_type ?? '',
            'date_of_birth' => $medicalInfo->date_of_birth ?? '',
            'emergency_contact' => $medicalInfo->emergency_contact ?? '',
            'insurance_provider' => $medicalInfo->insurance_provider ?? '',
            'allergies' => $medicalInfo->allergies ?? ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $formType = trim($_POST['form_type'] ?? '');

            if ($formType === 'update_name') {
                $submittedName = trim($_POST['user_name'] ?? '');

                if ($submittedName === '') {
                    $nameErr = 'Please enter your name.';
                } elseif (strlen($submittedName) < 3) {
                    $nameErr = 'Name must be at least 3 characters.';
                } else {
                    if ($this->userModel->updateUserName($userId, $submittedName)) {
                        $_SESSION['user_name'] = $submittedName;
                        $userName = $submittedName;
                        $nameSuccess = 'Name updated successfully.';
                    }
                }

                if ($nameErr !== '') {
                    $userName = $submittedName;
                }
            }

            if ($formType === 'update_profile_image') {
                if (!isset($_FILES['profile_image']) || empty($_FILES['profile_image']['tmp_name'])) {
                    $profileImageErr = 'Please choose an image first.';
                } else {
                    $file = $_FILES['profile_image'];
                    $validation = FileValidator::validateFile($file);

                    if (!$validation['valid']) {
                        $profileImageErr = implode(', ', $validation['errors']);
                    } else {
                        $uploadDir = $this->ensurePatientProfileImageDirectory($userId);

                        if ($uploadDir === false) {
                            $profileImageErr = 'Failed to prepare upload directory.';
                        } else {
                            try {
                                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $fileName = 'profile_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
                                $targetAbsolutePath = $uploadDir['absolute'] . $fileName;

                                if (move_uploaded_file($file['tmp_name'], $targetAbsolutePath)) {
                                    chmod($targetAbsolutePath, 0644);
                                    $newRelativePath = $uploadDir['relative'] . $fileName;

                                    if ($this->userModel->updateUserProfileImage($userId, $newRelativePath)) {
                                        if (!empty($currentProfileImage) && $currentProfileImage !== $newRelativePath) {
                                            $oldAbsolutePath = dirname(APPROOT) . '/public/' . $currentProfileImage;
                                            if (file_exists($oldAbsolutePath)) {
                                                @unlink($oldAbsolutePath);
                                            }
                                        }

                                        $currentProfileImage = $newRelativePath;
                                        $profileImageSuccess = 'Profile picture updated successfully.';
                                    } else {
                                        @unlink($targetAbsolutePath);
                                        $profileImageErr = 'Failed to save profile picture path.';
                                    }
                                } else {
                                    $profileImageErr = 'Failed to upload selected image.';
                                }
                            } catch (Throwable $exception) {
                                $profileImageErr = 'Image upload failed. Please try again.';
                            }
                        }
                    }
                }
            }

            if ($formType === 'update_medical') {
                $medicalForm = [
                    'blood_type' => trim($_POST['blood_type'] ?? ''),
                    'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
                    'emergency_contact' => trim($_POST['emergency_contact'] ?? ''),
                    'insurance_provider' => trim($_POST['insurance_provider'] ?? ''),
                    'allergies' => trim($_POST['allergies'] ?? '')
                ];

                $medicalPayload = $medicalForm;
                $medicalPayload['patient_id'] = $userId;

                if ($this->userModel->savePatientMedicalInfo($medicalPayload)) {
                    $medicalSuccess = 'Medical information saved successfully.';
                    $medicalInfo = $this->userModel->getPatientMedicalInfo($userId);
                    $medicalForm = [
                        'blood_type' => $medicalInfo->blood_type ?? '',
                        'date_of_birth' => $medicalInfo->date_of_birth ?? '',
                        'emergency_contact' => $medicalInfo->emergency_contact ?? '',
                        'insurance_provider' => $medicalInfo->insurance_provider ?? '',
                        'allergies' => $medicalInfo->allergies ?? ''
                    ];
                }
            }
        }
        
        $data = [
            'user_id' => $userId,
            'user_email' => $userEmail,
            'user_name' => $userName,
            'name_err' => $nameErr,
            'name_success' => $nameSuccess,
            'medical_success' => $medicalSuccess,
            'profile_image' => $currentProfileImage,
            'profile_image_success' => $profileImageSuccess,
            'profile_image_err' => $profileImageErr,
            'medical_info' => $medicalInfo,
            'medical_form' => $medicalForm
        ];
        
        $this->view('pages/v_patient_profile', $data);
    }

    private function ensurePatientProfileImageDirectory($userId) {
        $relativeDir = 'uploads/profile_images/' . (int)$userId . '/';
        $absoluteDir = dirname(APPROOT) . '/public/' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0755, true)) {
            return false;
        }

        $htaccessPath = $absoluteDir . '.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "Options -Indexes\n";
            $htaccessContent .= "<FilesMatch \"\\.(php|phtml|php3|php4|php5|phar)$\">\n";
            $htaccessContent .= "    Deny from all\n";
            $htaccessContent .= "</FilesMatch>\n";
            @file_put_contents($htaccessPath, $htaccessContent);
        }

        return [
            'relative' => $relativeDir,
            'absolute' => $absoluteDir
        ];
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

    public function patientBookAppointment() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        
        // Redirect to the patient appointments page where they can book new appointments
        redirect('Appointments/my');
    }

    public function patientSettings() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
         redirect('Pages/index');
         return;
        }
        $this->view('pages/v_patient_settings', []);
    }

    public function adminMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['user_name'],
            'user_status' => $this->userModel->getUserStatusById($_SESSION['user_id'])
        ];
        
        $this->view('pages/messages/v_admin_messages', $data);
    }

    public function patientMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            redirect('Pages/index');
            return;
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['user_name'],
            'user_status' => $this->userModel->getUserStatusById($_SESSION['user_id'])
        ];
        
        $this->view('pages/messages/v_patient_messages', $data);
    }

}   

?>
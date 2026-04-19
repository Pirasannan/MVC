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
        $pendingVerifications = $this->verificationModel->getPendingDoctorVerificationsForAdmin();
        $unverifiedDoctors = $this->adminModel->getUnverifiedDoctors();
        $pendingDoctorsMap = [];

        foreach ($pendingVerifications as $doctor) {
            $doctorKey = $doctor->user_id ?? $doctor->email ?? null;
            if ($doctorKey === null) {
                continue;
            }
            $pendingDoctorsMap[$doctorKey] = $doctor;
        }

        foreach ($unverifiedDoctors as $doctor) {
            $doctorKey = $doctor->id ?? $doctor->email ?? null;
            if ($doctorKey === null || isset($pendingDoctorsMap[$doctorKey])) {
                continue;
            }
            $pendingDoctorsMap[$doctorKey] = $doctor;
        }

        $data = [
            'pendingDoctors' => array_values($pendingDoctorsMap),
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

        $pendingVerifications = $this->verificationModel->getPendingDoctorVerificationsForAdmin();
        $unverifiedDoctors = $this->adminModel->getUnverifiedDoctors();
        $pendingVerificationsMap = [];

        foreach ($pendingVerifications as $doctor) {
            $doctorKey = $doctor->user_id ?? $doctor->email ?? null;
            if ($doctorKey === null) {
                continue;
            }
            $pendingVerificationsMap[$doctorKey] = $doctor;
        }

        foreach ($unverifiedDoctors as $doctor) {
            $doctorKey = $doctor->id ?? $doctor->email ?? null;
            if ($doctorKey === null || isset($pendingVerificationsMap[$doctorKey])) {
                continue;
            }
            $pendingVerificationsMap[$doctorKey] = $doctor;
        }

        $data = [
            'pendingVerifications' => array_values($pendingVerificationsMap)
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
        $verificationUpdated = $this->verificationModel->updateStatus((int)$verification->id, 'verified', null);
        
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

        // Detect request type: JSON or form POST
        $isJsonRequest = $this->isJsonRequest();
        
        // Parse input based on request type
        if ($isJsonRequest) {
            $input = json_decode(file_get_contents('php://input'), true);
        } else {
            $input = $_POST;
        }
        
        // Build data array
        $data = [
            'recipient_type' => $input['recipient_type'] ?? '',
            'recipient_id' => $input['recipient_id'] ?? null,
            'title' => $input['title'] ?? '',
            'message' => $input['message'] ?? '',
            'notification_type' => $input['notification_type'] ?? 'info'
        ];

        // Validate recipient_type
        $validRecipients = ['all', 'admin', 'doctor', 'patient'];
        if (empty($data['recipient_type']) || !in_array($data['recipient_type'], $validRecipients)) {
            $errorMsg = 'Invalid recipient type';
            if ($isJsonRequest) {
                echo json_encode(['success' => false, 'message' => $errorMsg]);
            } else {
                redirect('Pages/adminNotifications?error=' . urlencode($errorMsg));
            }
            return;
        }

        // Validate title and message
        if (empty(trim($data['title'])) || empty(trim($data['message']))) {
            $errorMsg = 'Title and message are required';
            if ($isJsonRequest) {
                echo json_encode(['success' => false, 'message' => $errorMsg]);
            } else {
                redirect('Pages/adminNotifications?error=' . urlencode($errorMsg));
            }
            return;
        }

        // Insert notification via model
        $result = $this->adminModel->createNotification($data);
        
        if ($result) {
            $emailReport = $this->sendNotificationEmails($data);
            if (($emailReport['failed'] ?? 0) > 0) {
                error_log('[System Notification] Email failures: ' . ($emailReport['failed'] ?? 0));
            }
            if ($isJsonRequest) {
                echo json_encode(['success' => true, 'message' => 'Notification sent successfully']);
            } else {
                redirect('Pages/adminNotifications?sent=1');
            }
        } else {
            $errorMsg = 'Failed to send notification';
            if ($isJsonRequest) {
                echo json_encode(['success' => false, 'message' => $errorMsg]);
            } else {
                redirect('Pages/adminNotifications?error=' . urlencode($errorMsg));
            }
        }
    }

    public function getLatestNotification() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $role = strtolower($_SESSION['user_role'] ?? '');

        if ($role === '') {
            echo json_encode(['success' => true, 'hasNew' => false]);
            return;
        }

        $notification = $this->adminModel->getLatestNotificationForUser($role, $userId);
        if (!$notification) {
            echo json_encode(['success' => true, 'hasNew' => false]);
            return;
        }

        $lastSeenId = $this->userModel->getLastNotificationSeenId($userId);
        $notificationId = (int)($notification->id ?? 0);

        if ($notificationId === 0 || ($lastSeenId !== null && $notificationId <= (int)$lastSeenId)) {
            echo json_encode(['success' => true, 'hasNew' => false]);
            return;
        }

        echo json_encode([
            'success' => true,
            'hasNew' => true,
            'notification' => [
                'id' => $notificationId,
                'title' => $notification->title ?? '',
                'message' => $notification->message ?? '',
                'notification_type' => $notification->notification_type ?? 'info',
                'created_at' => $notification->created_at ?? ''
            ]
        ]);
    }

    public function markNotificationSeen() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $isJsonRequest = $this->isJsonRequest();
        $input = $isJsonRequest ? json_decode(file_get_contents('php://input'), true) : $_POST;
        $notificationId = (int)($input['notification_id'] ?? 0);

        if ($notificationId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Notification ID required']);
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $currentSeenId = $this->userModel->getLastNotificationSeenId($userId);

        if ($currentSeenId === null || $notificationId > (int)$currentSeenId) {
            $this->userModel->updateLastNotificationSeenId($userId, $notificationId);
        }

        echo json_encode(['success' => true]);
    }

    /**
     * Helper method to detect if request is JSON
     */
    private function isJsonRequest() {
        return !empty($_SERVER['CONTENT_TYPE']) && 
               strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function sendNotificationEmails($data) {
        $recipientType = strtolower($data['recipient_type'] ?? '');
        $recipientId = !empty($data['recipient_id']) ? (int)$data['recipient_id'] : null;
        $recipients = [];

        if ($recipientId) {
            $user = $this->userModel->getUserContactById($recipientId);
            if ($user && strtolower($user->status ?? '') === 'active') {
                $recipients = [$user];
            }
        } elseif ($recipientType === 'all') {
            $recipients = $this->userModel->getAllActiveUsers();
        } elseif (in_array($recipientType, ['admin', 'doctor', 'patient'], true)) {
            $recipients = $this->userModel->getActiveUsersByRole($recipientType);
        }

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            $email = $recipient->email ?? '';
            $name = $recipient->name ?? 'User';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failed++;
                continue;
            }

            $ok = Mailer::sendNotification($email, $name, $data['title'], $data['message']);
            if ($ok) {
                $sent++;
            } else {
                $failed++;
            }
        }

        return [
            'attempted' => count($recipients),
            'sent' => $sent,
            'failed' => $failed
        ];
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

        return redirect('Pages/adminPatients');
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
            'callReports' => $this->adminModel->getPendingCallReports(),
            'userReports' => $this->adminModel->getPendingUserReports(),
            'resolvedReports' => $this->adminModel->getResolvedReports(),
            'flash' => $_SESSION['flash'] ?? null,
        ];

        unset($_SESSION['flash']);

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

    public function resolveReport($reportId = null) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('Pages/adminReports');
        }

        $reportId = (int)$reportId;
        $resolution = trim($_POST['resolution'] ?? '');

        if ($reportId <= 0 || $resolution === '') {
            $_SESSION['flash'] = 'Resolution note is required.';
            return redirect('Pages/adminReports');
        }

        $ok = $this->adminModel->resolveReport($reportId, (int)$_SESSION['user_id'], $resolution);
        $_SESSION['flash'] = $ok
            ? 'Report marked as resolved.'
            : 'Could not resolve report. It may already be resolved.';

        return redirect('Pages/adminReports');
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

    public function adminLoginLogs() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            return redirect('Pages/index');
        }

        $data = [
            'loginLogs' => $this->adminModel->getLoginLogs()
        ];

        $this->view('pages/v_admin_login_logs', $data);
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

        usort($upcomingAppointments, function ($left, $right) use ($nowTimestamp) {
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
        $doctor_status = strtolower((string)$this->userModel->getUserStatusById((int)$doctor_id));
        
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
            'patient_name' => $patient_name,
            'doctor_status' => $doctor_status
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
            'user_status' => $this->userModel->getUserStatusById($_SESSION['user_id']),
            'conversations' => $mockConversations
        ];
        
        $this->view('pages/messages/v_doctor_messages', $data);
    }

    public function doctorMedicalrecords() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
            return;
        }
        redirect('MedicalRecords/doctor');
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

        $doctorStatus = strtolower((string)$this->userModel->getUserStatusById((int)($_SESSION['user_id'] ?? 0)));
        if ($doctorStatus === 'inactive') {
            $_SESSION['flash'] = 'Your account is deactivated. You cannot create prescriptions.';
            redirect('Users/logout');
            return;
        }

        if ($doctorStatus === 'suspended') {
            $_SESSION['flash'] = 'Your account is suspended. You cannot create prescriptions.';
            redirect('Pages/doctorPrescriptions');
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
        $nowTimestamp = $now->getTimestamp();

        $appointments = $patientId ? $this->appointmentModel->getByPatient($patientId) : [];
        $todayAppointments = 0;
        $upcomingAppointments = [];

        foreach ($appointments as $appointment) {
            $startsAt = new DateTimeImmutable($appointment->starts_at);
            $status = strtolower((string)($appointment->status ?? ''));

            if ($startsAt->format('Y-m-d') === $today->format('Y-m-d')) {
                $todayAppointments++;
            }

            $isCancelledOrRejected = in_array($status, ['cancelled', 'rejected'], true);
            $isUpcomingActive = ($startsAt >= $now && $status !== 'completed');

            if ($isUpcomingActive || $isCancelledOrRejected) {
                $upcomingAppointments[] = $appointment;
            }
        }

        usort($upcomingAppointments, function ($left, $right) use ($nowTimestamp) {
            $leftTs = strtotime($left->starts_at ?? '');
            $rightTs = strtotime($right->starts_at ?? '');
            $leftIsFuture = $leftTs >= $nowTimestamp;
            $rightIsFuture = $rightTs >= $nowTimestamp;

            if ($leftIsFuture !== $rightIsFuture) {
                return $rightIsFuture <=> $leftIsFuture;
            }

            if ($leftIsFuture) {
                return $leftTs <=> $rightTs;
            }

            return $rightTs <=> $leftTs;
        });

        $upcomingAppointments = array_slice($upcomingAppointments, 0, 8);

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
        redirect('MedicalRecords/patient');
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

    public function getPrescriptionJSON($id) {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $prescriptionModel = $this->model('Prescription');
        $prescription = $prescriptionModel->getPrescriptionDetails($id);

        if (!$prescription) {
            http_response_code(404);
            echo json_encode(['error' => 'Prescription not found']);
            return;
        }

        // Check if user is authorized to view this prescription
        if ($_SESSION['user_role'] === 'doctor' && $prescription->doctor_id != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        if ($_SESSION['user_role'] === 'patient' && $prescription->patient_id != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($prescription);
    }

}   

?>
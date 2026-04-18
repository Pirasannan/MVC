<?php

class MedicalRecords extends Controller {
    private $recordsModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            redirect('Users/login');
        }
        $this->recordsModel = $this->model('M_MedicalRecords');
    }

    // Default route redirects based on role
    public function index() {
        if ($_SESSION['user_role'] === 'patient') {
            redirect('MedicalRecords/patient');
        } elseif ($_SESSION['user_role'] === 'doctor') {
            redirect('MedicalRecords/doctor');
        } else {
            redirect('Pages/index');
        }
    }

    // PATIENT DASHBOARD (VIEW)
    public function patient() {
        if ($_SESSION['user_role'] !== 'patient') {
            redirect('Pages/index');
        }

        $patient_id = $_SESSION['user_id'];
        $records = $this->recordsModel->getRecordsByPatient($patient_id);
        $stats = $this->recordsModel->getStatsByPatient($patient_id);

        $data = [
            'records' => $records,
            'stats' => $stats,
            'current_page' => 'patientMedicalrecords' // Retained for sidebar active state
        ];

        $this->view('pages/v_patient_medicalrecords', $data);
    }

    // DOCTOR DASHBOARD (VIEW)
    public function doctor() {
        if ($_SESSION['user_role'] !== 'doctor') {
            redirect('Pages/index');
        }

        $doctor_id = $_SESSION['user_id'];
        $records = $this->recordsModel->getSharedRecordsForDoctor($doctor_id);

        $data = [
            'records' => $records,
            'current_page' => 'doctorMedicalrecords'
        ];

        $this->view('pages/v_doctor_medicalrecords', $data);
    }

    // UPLOAD RECORD (AJAX POST)
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $patient_id = $_SESSION['user_id'];

        // Collect and sanitize form inputs
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        $doctorName = trim($_POST['doctorName'] ?? '');
        $reportType = trim($_POST['reportType'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Define human readable record names based on types if none provided
        $recordName = 'Medical Record';
        if($reportType === 'lab') $recordName = 'Lab Report';
        if($reportType === 'scan') $recordName = 'Scan / Imaging';
        if($reportType === 'prescription') $recordName = 'Physical Prescription';
        if($reportType === 'hospital') $recordName = 'Hospital Visit Notes';
        if($reportType === 'vaccination') $recordName = 'Vaccination Record';

        if (empty($reportType) || !isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid form submission or missing file.']);
            return;
        }

        $file = $_FILES['document'];
        
        // Validation: Size (10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 10MB limit.']);
            return;
        }

        // Validation: MIME types and extension
        $allowedMimes = [
            'application/pdf', 
            'image/jpeg', 
            'image/png', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        
        $mimeType = mime_content_type($file['tmp_name']);
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedMimes) || !in_array($fileExt, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, JPG, PNG, and DOC are allowed.']);
            return;
        }

        // Prepare File System Paths
        $uploadDir = APPROOT . '/../public/uploads/medical_records/' . $patient_id . '/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $error = error_get_last();
                echo json_encode(['success' => false, 'message' => 'Failed to create directory: ' . ($error['message'] ?? 'Unknown error')]);
                return;
            }
            // Additionally secure directory
            file_put_contents($uploadDir . '.htaccess', "Options -Indexes -ExecCGI\nphp_flag engine off\n");
        }

        $newFileName = uniqid('rec_') . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $data = [
                'patient_id' => $patient_id,
                'record_name' => $recordName,
                'record_type' => $reportType,
                'doctor_name' => $doctorName,
                'description' => $description,
                'file_name' => $newFileName,
                'original_name' => $file['name'],
                'file_size' => $file['size'],
                'mime_type' => $mimeType
            ];

            if ($this->recordsModel->createRecord($data)) {
                echo json_encode(['success' => true, 'message' => 'Record uploaded successfully.']);
            } else {
                // If DB fails, remove the uploaded file
                unlink($destination);
                echo json_encode(['success' => false, 'message' => 'Database error while saving record.']);
            }
        } else {
            $error = error_get_last();
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file. Error: ' . ($error['message'] ?? 'Unknown')]);
        }
    }

    // EDIT RECORD (AJAX POST)
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $patient_id = $_SESSION['user_id'];
        $record = $this->recordsModel->getRecordById($id, $patient_id);

        if (!$record) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            return;
        }

        // Collect and sanitize form inputs
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $doctorName = trim($_POST['doctorName'] ?? '');
        $reportType = trim($_POST['reportType'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $recordName = 'Medical Record';
        if($reportType === 'lab') $recordName = 'Lab Report';
        if($reportType === 'scan') $recordName = 'Scan / Imaging';
        if($reportType === 'prescription') $recordName = 'Physical Prescription';
        if($reportType === 'hospital') $recordName = 'Hospital Visit Notes';
        if($reportType === 'vaccination') $recordName = 'Vaccination Record';

        if (empty($reportType)) {
            echo json_encode(['success' => false, 'message' => 'Report type is required.']);
            return;
        }

        $data = [
            'record_name' => $recordName,
            'record_type' => $reportType,
            'doctor_name' => $doctorName,
            'description' => $description
        ];

        // Did they upload a new file?
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['document'];
            
            if ($file['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File size exceeds 10MB limit.']);
                return;
            }

            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
            
            $mimeType = mime_content_type($file['tmp_name']);
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($mimeType, $allowedMimes) || !in_array($fileExt, $allowedExtensions)) {
                echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, JPG, PNG, and DOC are allowed.']);
                return;
            }

            $uploadDir = APPROOT . '/../public/uploads/medical_records/' . $patient_id . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                file_put_contents($uploadDir . '.htaccess', "Options -Indexes -ExecCGI\nphp_flag engine off\n");
            }

            $newFileName = uniqid('rec_') . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Delete old file
                $oldFilePath = $uploadDir . $record->file_name;
                if (file_exists($oldFilePath) && $record->file_name !== $newFileName) {
                    unlink($oldFilePath);
                }
                
                $data['file_name'] = $newFileName;
                $data['original_name'] = $file['name'];
                $data['file_size'] = $file['size'];
                $data['mime_type'] = $mimeType;
                
                if ($this->recordsModel->updateRecordWithFile($id, $patient_id, $data)) {
                    echo json_encode(['success' => true, 'message' => 'Record and file updated successfully.']);
                } else {
                    unlink($destination);
                    echo json_encode(['success' => false, 'message' => 'Database error.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload new document.']);
            }
        } else {
            // Update metadata only
            if ($this->recordsModel->updateRecord($id, $patient_id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Record metadata updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error while saving record.']);
            }
        }
    }

    // DELTE RECORD (AJAX POST)
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $patient_id = $_SESSION['user_id'];
        $record = $this->recordsModel->getRecordById($id, $patient_id);

        if (!$record) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            return;
        }

        if ($this->recordsModel->deleteRecord($id, $patient_id)) {
            $filePath = APPROOT . '/../public/uploads/medical_records/' . $patient_id . '/' . $record->file_name;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record.']);
        }
    }

    // VIEW OR DOWNLOAD
    public function view_file($id, $action = 'view') {
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $record = null;

        if ($role === 'patient') {
            $record = $this->recordsModel->getRecordById($id, $user_id);
        } elseif ($role === 'doctor') {
            // Check if record is shared with this doctor
            if ($this->recordsModel->isRecordSharedWith($id, $user_id)) {
                $record = $this->recordsModel->getRecordByIdOnly($id);
            }
        }

        if (!$record) {
            die('Unauthorized or Record not found.');
        }

        $patient_id = $record->patient_id;
        $filePath = APPROOT . '/../public/uploads/medical_records/' . $patient_id . '/' . $record->file_name;

        if (!file_exists($filePath)) {
            die('File missing from server.');
        }

        header('Content-Type: ' . $record->mime_type);
        header('Content-Length: ' . filesize($filePath));
        
        if ($action === 'download') {
            header('Content-Disposition: attachment; filename="' . $record->original_name . '"');
        } else {
            header('Content-Disposition: inline; filename="' . $record->original_name . '"');
        }

        readfile($filePath);
        exit;
    }

    // API Wrapper for download Action
    public function download($id) {
        $this->view_file($id, 'download');
    }

    // AJAX: Search for active doctors
    public function search_doctors() {
        if ($_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            return;
        }

        $term = trim($_GET['term'] ?? '');
        $doctors = $this->recordsModel->getActiveDoctors($term);
        echo json_encode($doctors);
    }

    // AJAX: Share a record with a doctor
    public function share() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $recordId = $_POST['record_id'] ?? null;
        $doctorId = $_POST['doctor_id'] ?? null;

        if (!$recordId || !$doctorId) {
            echo json_encode(['success' => false, 'message' => 'Missing information']);
            return;
        }

        // Verify patient owns the record
        $record = $this->recordsModel->getRecordById($recordId, $_SESSION['user_id']);
        if (!$record) {
            echo json_encode(['success' => false, 'message' => 'Record not found or access denied']);
            return;
        }

        if ($this->recordsModel->shareRecord($recordId, $doctorId)) {
            echo json_encode(['success' => true, 'message' => 'Record successfully shared']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to share record']);
        }
    }
}

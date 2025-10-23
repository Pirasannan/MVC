<?php
/**
 * Verification Controller
 * Handles doctor profile verification functionality
 */

// Include required libraries
require_once '../app/libraries/FileValidator.php';
require_once '../app/libraries/FileManager.php';

class Verification extends Controller {
    private $verificationModel;
    private $userModel;
    
    public function __construct() {
        $this->verificationModel = $this->model('M_Verification');
        $this->userModel = $this->model('M_Users');
        
        // Initialize upload directory
        FileManager::initializeUploadDirectory();
    }
    
    /**
     * Main verification page - shows current status and options
     */
    public function index() {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            redirect('Users/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'];
        
        // Get current verification status
        $verification = $this->verificationModel->getVerificationByUserId($userId);
        
        $data = [
            'verification' => $verification,
            'user_id' => $userId,
            'user_email' => $userEmail,
            'page_title' => 'Profile Verification'
        ];
        
        $this->view('verification/profile', $data);
    }
    
    /**
     * Handle file upload
     */
    public function upload() {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userEmail = $_SESSION['user_email'];
            
            // Check if file was uploaded
            if (!isset($_FILES['verification_photo']) || empty($_FILES['verification_photo']['tmp_name'])) {
                $this->jsonResponse(['success' => false, 'message' => 'No file selected']);
                return;
            }
            
            $file = $_FILES['verification_photo'];
            
            // Upload file using FileManager
            $uploadResult = FileManager::uploadFile($file, $userId);
            
            if ($uploadResult['success']) {
                // Check if user already has verification record
                $existingVerification = $this->verificationModel->getVerificationByUserId($userId);
                
                if ($existingVerification) {
                    // Update existing record
                    $updateData = [
                        'photo_path' => $uploadResult['file_path'],
                        'verification_status' => 'pending'
                    ];
                    
                    // Delete old file
                    FileManager::deleteFile($existingVerification->photo_path);
                    
                    if ($this->verificationModel->updateVerification($existingVerification->id, $updateData)) {
                        $this->jsonResponse([
                            'success' => true, 
                            'message' => 'Verification photo updated successfully. Status reset to pending.'
                        ]);
                    } else {
                        // Clean up uploaded file if database update fails
                        FileManager::deleteFile($uploadResult['file_path']);
                        $this->jsonResponse(['success' => false, 'message' => 'Failed to update verification record']);
                    }
                } else {
                    // Create new verification record
                    $verificationData = [
                        'user_id' => $userId,
                        'email' => $userEmail,
                        'photo_path' => $uploadResult['file_path'],
                        'verification_status' => 'pending'
                    ];
                    
                    if ($this->verificationModel->createVerification($verificationData)) {
                        $this->jsonResponse([
                            'success' => true, 
                            'message' => 'Verification photo uploaded successfully. Your verification is now pending review.'
                        ]);
                    } else {
                        // Clean up uploaded file if database insert fails
                        FileManager::deleteFile($uploadResult['file_path']);
                        $this->jsonResponse(['success' => false, 'message' => 'Failed to create verification record']);
                    }
                }
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Upload failed: ' . implode(', ', $uploadResult['errors'])
                ]);
            }
        } else {
            // Show upload form
            $this->view('verification/upload', ['page_title' => 'Upload Verification Photo']);
        }
    }
    
    /**
     * View uploaded verification photo
     */
    public function viewPhoto($verificationId = null) {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            redirect('Users/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $verification = $this->verificationModel->getVerificationByUserId($userId);
        
        if (!$verification) {
            redirect('Verification/index');
            return;
        }
        
        // Check if file exists
        if (!FileManager::fileExists($verification->photo_path)) {
            $data = [
                'error' => 'Verification photo not found',
                'verification' => $verification
            ];
            $this->view('verification/view', $data);
            return;
        }
        
        $data = [
            'verification' => $verification,
            'file_url' => FileManager::getFileUrl($verification->photo_path),
            'file_size' => FileManager::getFileSize($verification->photo_path),
            'page_title' => 'View Verification Photo'
        ];
        
        $this->view('verification/view', $data);
    }
    
    /**
     * Update verification photo
     */
    public function update() {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Get current verification
            $verification = $this->verificationModel->getVerificationByUserId($userId);
            if (!$verification) {
                $this->jsonResponse(['success' => false, 'message' => 'No verification record found']);
                return;
            }
            
            // Check if file was uploaded
            if (!isset($_FILES['verification_photo']) || empty($_FILES['verification_photo']['tmp_name'])) {
                $this->jsonResponse(['success' => false, 'message' => 'No file selected']);
                return;
            }
            
            $file = $_FILES['verification_photo'];
            
            // Replace existing file
            $replaceResult = FileManager::replaceFile($file, $verification->photo_path, $userId);
            
            if ($replaceResult['success']) {
                // Update database record
                $updateData = [
                    'photo_path' => $replaceResult['file_path'],
                    'verification_status' => 'pending'
                ];
                
                if ($this->verificationModel->updateVerification($verification->id, $updateData)) {
                    $this->jsonResponse([
                        'success' => true, 
                        'message' => 'Verification photo updated successfully. Status reset to pending.'
                    ]);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update verification record']);
                }
            } else {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Update failed: ' . implode(', ', $replaceResult['errors'])
                ]);
            }
        } else {
            redirect('Verification/index');
        }
    }
    
    /**
     * Delete verification record and photo
     */
    public function delete() {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Get current verification
            $verification = $this->verificationModel->getVerificationByUserId($userId);
            if (!$verification) {
                $this->jsonResponse(['success' => false, 'message' => 'No verification record found']);
                return;
            }
            
            // Delete file first
            FileManager::deleteFile($verification->photo_path);
            
            // Delete database record
            if ($this->verificationModel->deleteVerification($verification->id)) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Verification record deleted successfully'
                ]);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to delete verification record']);
            }
        } else {
            redirect('Verification/index');
        }
    }
    
    /**
     * Get verification status (AJAX endpoint)
     */
    public function getStatus() {
        // Check if user is logged in and is a doctor
        if (!$this->isLoggedIn() || !$this->isDoctor()) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $verification = $this->verificationModel->getVerificationByUserId($userId);
        
        if ($verification) {
            $this->jsonResponse([
                'success' => true,
                'status' => $verification->verification_status,
                'uploaded_at' => $verification->uploaded_at,
                'verified_at' => $verification->verified_at,
                'rejection_reason' => $verification->rejection_reason
            ]);
        } else {
            $this->jsonResponse([
                'success' => true,
                'status' => 'none'
            ]);
        }
    }
    
    /**
     * Check if user is logged in
     */
    private function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user is a doctor
     */
    private function isDoctor() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'doctor';
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>
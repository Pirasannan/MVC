<?php
/**
 * FileManager Class
 * Handles secure file storage and management
 */
class FileManager {
    
    // Base upload directory
    private static $baseUploadDir = '../public/uploads/verifications/';
    
    /**
     * Upload file securely
     * @param array $file $_FILES array element
     * @param int $userId
     * @return array Upload result
     */
    public static function uploadFile($file, $userId) {
        $result = [
            'success' => false,
            'file_path' => null,
            'errors' => []
        ];
        
        try {
            // Validate file first
            $validation = FileValidator::validateFile($file);
            if (!$validation['valid']) {
                $result['errors'] = $validation['errors'];
                return $result;
            }
            
            // Create user directory if it doesn't exist
            $userDir = self::createUserDirectory($userId);
            if (!$userDir) {
                $result['errors'][] = 'Failed to create upload directory';
                return $result;
            }
            
            // Generate secure filename
            $secureFileName = FileValidator::generateSecureFileName($file['name'], $userId);
            $targetPath = $userDir . $secureFileName;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Set proper file permissions
                chmod($targetPath, 0644);
                
                // Return relative path for database storage
                $relativePath = 'uploads/verifications/' . $userId . '/' . $secureFileName;
                
                $result['success'] = true;
                $result['file_path'] = $relativePath;
            } else {
                $result['errors'][] = 'Failed to move uploaded file';
            }
            
        } catch (Exception $e) {
            $result['errors'][] = 'Upload error: ' . $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Delete file from storage
     * @param string $filePath Relative path from database
     * @return bool
     */
    public static function deleteFile($filePath) {
        try {
            // Convert relative path to absolute path
            $absolutePath = '../public/' . $filePath;
            
            // Check if file exists and delete it
            if (file_exists($absolutePath)) {
                return unlink($absolutePath);
            }
            
            // File doesn't exist, consider it deleted
            return true;
            
        } catch (Exception $e) {
            error_log('File deletion error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Replace existing file with new one
     * @param array $newFile $_FILES array element
     * @param string $oldFilePath Existing file path to replace
     * @param int $userId
     * @return array
     */
    public static function replaceFile($newFile, $oldFilePath, $userId) {
        $result = [
            'success' => false,
            'file_path' => null,
            'errors' => []
        ];
        
        try {
            // Upload new file first
            $uploadResult = self::uploadFile($newFile, $userId);
            
            if ($uploadResult['success']) {
                // Delete old file
                self::deleteFile($oldFilePath);
                
                $result['success'] = true;
                $result['file_path'] = $uploadResult['file_path'];
            } else {
                $result['errors'] = $uploadResult['errors'];
            }
            
        } catch (Exception $e) {
            $result['errors'][] = 'File replacement error: ' . $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Create user-specific directory
     * @param int $userId
     * @return string|false Directory path or false on failure
     */
    private static function createUserDirectory($userId) {
        try {
            $userDir = self::$baseUploadDir . $userId . '/';
            
            // Create directory if it doesn't exist
            if (!is_dir($userDir)) {
                if (!mkdir($userDir, 0755, true)) {
                    return false;
                }
            }
            
            // Create .htaccess file for security
            self::createHtaccessFile($userDir);
            
            return $userDir;
            
        } catch (Exception $e) {
            error_log('Directory creation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create .htaccess file for directory security
     * @param string $directory
     */
    private static function createHtaccessFile($directory) {
        $htaccessPath = $directory . '.htaccess';
        
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "# Deny direct access to uploaded files\n";
            $htaccessContent .= "Options -Indexes\n";
            $htaccessContent .= "<Files *.php>\n";
            $htaccessContent .= "    Deny from all\n";
            $htaccessContent .= "</Files>\n";
            $htaccessContent .= "<Files *.html>\n";
            $htaccessContent .= "    Deny from all\n";
            $htaccessContent .= "</Files>\n";
            
            file_put_contents($htaccessPath, $htaccessContent);
        }
    }
    
    /**
     * Generate secure file path for user
     * @param int $userId
     * @return string
     */
    public static function generateSecurePath($userId) {
        return 'uploads/verifications/' . $userId . '/';
    }
    
    /**
     * Check if file exists
     * @param string $filePath Relative path from database
     * @return bool
     */
    public static function fileExists($filePath) {
        $absolutePath = '../public/' . $filePath;
        return file_exists($absolutePath);
    }
    
    /**
     * Get file size
     * @param string $filePath Relative path from database
     * @return int|false File size in bytes or false on failure
     */
    public static function getFileSize($filePath) {
        $absolutePath = '../public/' . $filePath;
        
        if (file_exists($absolutePath)) {
            return filesize($absolutePath);
        }
        
        return false;
    }
    
    /**
     * Get file URL for display
     * @param string $filePath Relative path from database
     * @return string
     */
    public static function getFileUrl($filePath) {
        return URLROOT . '/' . $filePath;
    }
    
    /**
     * Clean up orphaned files (files without database records)
     * @param array $validFilePaths Array of valid file paths from database
     * @return array Cleanup result
     */
    public static function cleanupOrphanedFiles($validFilePaths = []) {
        $result = [
            'deleted_count' => 0,
            'errors' => []
        ];
        
        try {
            $baseDir = self::$baseUploadDir;
            
            if (!is_dir($baseDir)) {
                return $result;
            }
            
            // Get all user directories
            $userDirs = glob($baseDir . '*', GLOB_ONLYDIR);
            
            foreach ($userDirs as $userDir) {
                $userId = basename($userDir);
                
                // Get all files in user directory
                $files = glob($userDir . '/*');
                
                foreach ($files as $file) {
                    if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) !== 'htaccess') {
                        $relativePath = 'uploads/verifications/' . $userId . '/' . basename($file);
                        
                        // If file is not in valid paths array, delete it
                        if (!in_array($relativePath, $validFilePaths)) {
                            if (unlink($file)) {
                                $result['deleted_count']++;
                            } else {
                                $result['errors'][] = 'Failed to delete: ' . $relativePath;
                            }
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            $result['errors'][] = 'Cleanup error: ' . $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Get directory size
     * @param int $userId
     * @return int Size in bytes
     */
    public static function getUserDirectorySize($userId) {
        $userDir = self::$baseUploadDir . $userId . '/';
        $size = 0;
        
        if (is_dir($userDir)) {
            $files = glob($userDir . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $size += filesize($file);
                }
            }
        }
        
        return $size;
    }
    
    /**
     * Initialize upload directory structure
     * @return bool
     */
    public static function initializeUploadDirectory() {
        try {
            $baseDir = self::$baseUploadDir;
            
            // Create base directory if it doesn't exist
            if (!is_dir($baseDir)) {
                if (!mkdir($baseDir, 0755, true)) {
                    return false;
                }
            }
            
            // Create main .htaccess file
            $htaccessPath = $baseDir . '.htaccess';
            if (!file_exists($htaccessPath)) {
                $htaccessContent = "# Verification uploads directory\n";
                $htaccessContent .= "Options -Indexes\n";
                $htaccessContent .= "# Allow image files only\n";
                $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png)$\">\n";
                $htaccessContent .= "    Allow from all\n";
                $htaccessContent .= "</FilesMatch>\n";
                $htaccessContent .= "# Deny everything else\n";
                $htaccessContent .= "<FilesMatch \"^(?!.*\\.(jpg|jpeg|png)$).*$\">\n";
                $htaccessContent .= "    Deny from all\n";
                $htaccessContent .= "</FilesMatch>\n";
                
                file_put_contents($htaccessPath, $htaccessContent);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('Upload directory initialization error: ' . $e->getMessage());
            return false;
        }
    }
}
?>
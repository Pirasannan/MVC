<?php
/**
 * FileValidator Class
 * Handles file validation for secure uploads
 */
class FileValidator {
    
    // Allowed file types
    private static $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    private static $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
    // File size limits (in bytes)
    private static $maxFileSize = 5242880; // 5MB
    private static $minFileSize = 1024; // 1KB
    
    /**
     * Validate uploaded file
     * @param array $file $_FILES array element
     * @return array Validation result
     */
    public static function validateFile($file) {
        $result = [
            'valid' => false,
            'errors' => []
        ];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $result['errors'][] = 'No file was uploaded';
            return $result;
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['errors'][] = self::getUploadErrorMessage($file['error']);
            return $result;
        }
        
        // Validate file size
        $sizeValidation = self::validateFileSize($file);
        if (!$sizeValidation['valid']) {
            $result['errors'] = array_merge($result['errors'], $sizeValidation['errors']);
        }
        
        // Validate file type
        $typeValidation = self::validateFileType($file);
        if (!$typeValidation['valid']) {
            $result['errors'] = array_merge($result['errors'], $typeValidation['errors']);
        }
        
        // Validate file content (check actual file headers)
        $contentValidation = self::validateFileContent($file);
        if (!$contentValidation['valid']) {
            $result['errors'] = array_merge($result['errors'], $contentValidation['errors']);
        }
        
        // If no errors, file is valid
        if (empty($result['errors'])) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    /**
     * Validate file type by extension and MIME type
     * @param array $file
     * @return array
     */
    public static function validateFileType($file) {
        $result = [
            'valid' => false,
            'errors' => []
        ];
        
        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check extension
        if (!in_array($extension, self::$allowedExtensions)) {
            $result['errors'][] = 'Invalid file type. Only JPG and PNG files are allowed';
            return $result;
        }
        
        // Check MIME type
        $mimeType = $file['type'];
        if (!in_array($mimeType, self::$allowedTypes)) {
            $result['errors'][] = 'Invalid MIME type. Only JPEG and PNG images are allowed';
            return $result;
        }
        
        $result['valid'] = true;
        return $result;
    }
    
    /**
     * Validate file size
     * @param array $file
     * @return array
     */
    public static function validateFileSize($file) {
        $result = [
            'valid' => false,
            'errors' => []
        ];
        
        $fileSize = $file['size'];
        
        // Check minimum size
        if ($fileSize < self::$minFileSize) {
            $result['errors'][] = 'File is too small. Minimum size is ' . self::formatBytes(self::$minFileSize);
            return $result;
        }
        
        // Check maximum size
        if ($fileSize > self::$maxFileSize) {
            $result['errors'][] = 'File is too large. Maximum size is ' . self::formatBytes(self::$maxFileSize);
            return $result;
        }
        
        $result['valid'] = true;
        return $result;
    }
    
    /**
     * Validate file content by checking file headers
     * @param array $file
     * @return array
     */
    public static function validateFileContent($file) {
        $result = [
            'valid' => false,
            'errors' => []
        ];
        
        // Read first few bytes to check file signature
        $handle = fopen($file['tmp_name'], 'rb');
        if (!$handle) {
            $result['errors'][] = 'Unable to read file content';
            return $result;
        }
        
        $header = fread($handle, 8);
        fclose($handle);
        
        // Check file signatures (magic numbers)
        $isValidImage = false;
        
        // JPEG signatures
        if (substr($header, 0, 3) === "\xFF\xD8\xFF") {
            $isValidImage = true;
        }
        
        // PNG signature
        if (substr($header, 0, 8) === "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
            $isValidImage = true;
        }
        
        if (!$isValidImage) {
            $result['errors'][] = 'File content does not match expected image format';
            return $result;
        }
        
        // Additional check using getimagesize
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $result['errors'][] = 'Invalid image file';
            return $result;
        }
        
        // Check image type
        $allowedImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        if (!in_array($imageInfo[2], $allowedImageTypes)) {
            $result['errors'][] = 'Unsupported image type';
            return $result;
        }
        
        $result['valid'] = true;
        return $result;
    }
    
    /**
     * Sanitize filename for security
     * @param string $filename
     * @return string
     */
    public static function sanitizeFileName($filename) {
        // Remove path information
        $filename = basename($filename);
        
        // Remove special characters except dots, hyphens, and underscores
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Remove multiple dots
        $filename = preg_replace('/\.+/', '.', $filename);
        
        // Ensure filename is not empty
        if (empty($filename)) {
            $filename = 'file';
        }
        
        // Limit filename length
        if (strlen($filename) > 100) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filename = substr($name, 0, 96 - strlen($extension)) . '.' . $extension;
        }
        
        return $filename;
    }
    
    /**
     * Generate secure filename with timestamp
     * @param string $originalName
     * @param int $userId
     * @return string
     */
    public static function generateSecureFileName($originalName, $userId) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        
        return "verification_{$userId}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Get upload error message
     * @param int $errorCode
     * @return string
     */
    private static function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds the maximum allowed size';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds the form maximum size';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Format bytes to human readable format
     * @param int $bytes
     * @return string
     */
    private static function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Get allowed file extensions
     * @return array
     */
    public static function getAllowedExtensions() {
        return self::$allowedExtensions;
    }
    
    /**
     * Get maximum file size
     * @return int
     */
    public static function getMaxFileSize() {
        return self::$maxFileSize;
    }
}
?>
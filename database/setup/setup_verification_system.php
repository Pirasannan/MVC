<?php
/**
 * Setup Script for Doctor Profile Verification System
 * Run this script to initialize the verification system
 */

echo "=== Doctor Profile Verification System Setup ===\n\n";

// Create upload directory structure
function createUploadDirectories() {
    echo "Creating upload directory structure...\n";
    
    $baseDir = 'public/uploads/verifications/';
    
    // Create base directory
    if (!is_dir($baseDir)) {
        if (mkdir($baseDir, 0755, true)) {
            echo "✓ Created base directory: $baseDir\n";
        } else {
            echo "✗ Failed to create base directory: $baseDir\n";
            return false;
        }
    } else {
        echo "✓ Base directory already exists: $baseDir\n";
    }
    
    // Create .htaccess for security
    $htaccessPath = $baseDir . '.htaccess';
    $htaccessContent = "# Verification uploads directory security\n";
    $htaccessContent .= "Options -Indexes\n";
    $htaccessContent .= "# Allow image files only\n";
    $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png)$\">\n";
    $htaccessContent .= "    Allow from all\n";
    $htaccessContent .= "</FilesMatch>\n";
    $htaccessContent .= "# Deny everything else\n";
    $htaccessContent .= "<FilesMatch \"^(?!.*\\.(jpg|jpeg|png)$).*$\">\n";
    $htaccessContent .= "    Deny from all\n";
    $htaccessContent .= "</FilesMatch>\n";
    
    if (file_put_contents($htaccessPath, $htaccessContent)) {
        echo "✓ Created security .htaccess file\n";
    } else {
        echo "✗ Failed to create .htaccess file\n";
    }
    
    // Set proper permissions
    chmod($baseDir, 0755);
    echo "✓ Set directory permissions to 755\n";
    
    return true;
}

// Check PHP extensions
function checkRequirements() {
    echo "Checking system requirements...\n";
    
    $required = [
        'gd' => 'GD extension for image processing',
        'fileinfo' => 'Fileinfo extension for MIME type detection',
        'json' => 'JSON extension for API responses'
    ];
    
    $allGood = true;
    
    foreach ($required as $ext => $description) {
        if (extension_loaded($ext)) {
            echo "✓ $description\n";
        } else {
            echo "✗ Missing: $description\n";
            $allGood = false;
        }
    }
    
    // Check upload settings
    $maxFileSize = ini_get('upload_max_filesize');
    $maxPostSize = ini_get('post_max_size');
    $memoryLimit = ini_get('memory_limit');
    
    echo "\nPHP Upload Settings:\n";
    echo "- upload_max_filesize: $maxFileSize\n";
    echo "- post_max_size: $maxPostSize\n";
    echo "- memory_limit: $memoryLimit\n";
    
    // Convert to bytes for comparison
    $maxFileSizeBytes = return_bytes($maxFileSize);
    $requiredSize = 5 * 1024 * 1024; // 5MB
    
    if ($maxFileSizeBytes >= $requiredSize) {
        echo "✓ Upload size limit is sufficient\n";
    } else {
        echo "⚠ Warning: upload_max_filesize should be at least 5MB\n";
    }
    
    return $allGood;
}

// Convert PHP size format to bytes
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    
    return $val;
}

// Test database connection
function testDatabaseConnection() {
    echo "Testing database connection...\n";
    
    try {
        // Include config if available
        if (file_exists('app/config/config.php')) {
            require_once 'app/config/config.php';
            
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
                          DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo "✓ Database connection successful\n";
            
            // Check if Users table exists
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'Users'");
            $stmt->execute();
            
            if ($stmt->fetch()) {
                echo "✓ Users table exists\n";
            } else {
                echo "✗ Users table not found\n";
                return false;
            }
            
            return true;
            
        } else {
            echo "⚠ Config file not found, skipping database test\n";
            return true;
        }
        
    } catch (Exception $e) {
        echo "✗ Database connection failed: " . $e->getMessage() . "\n";
        return false;
    }
}

// Create sample user directories for testing
function createSampleDirectories() {
    echo "Creating sample user directories...\n";
    
    $baseDir = 'public/uploads/verifications/';
    $sampleUsers = [1, 2, 3]; // Sample user IDs
    
    foreach ($sampleUsers as $userId) {
        $userDir = $baseDir . $userId . '/';
        
        if (!is_dir($userDir)) {
            if (mkdir($userDir, 0755, true)) {
                echo "✓ Created user directory: $userDir\n";
                
                // Create user-specific .htaccess
                $userHtaccess = $userDir . '.htaccess';
                $content = "# User $userId verification uploads\n";
                $content .= "Options -Indexes\n";
                $content .= "<Files *.php>\n";
                $content .= "    Deny from all\n";
                $content .= "</Files>\n";
                
                file_put_contents($userHtaccess, $content);
                
            } else {
                echo "✗ Failed to create user directory: $userDir\n";
            }
        } else {
            echo "✓ User directory already exists: $userDir\n";
        }
    }
}

// Generate setup report
function generateSetupReport() {
    echo "\n=== Setup Report ===\n";
    
    $report = [
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'memory_limit' => ini_get('memory_limit'),
        'extensions' => get_loaded_extensions()
    ];
    
    $reportFile = 'verification_setup_report.json';
    
    if (file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT))) {
        echo "✓ Setup report saved to: $reportFile\n";
    } else {
        echo "✗ Failed to save setup report\n";
    }
}

// Main setup process
function runSetup() {
    echo "Starting verification system setup...\n\n";
    
    $steps = [
        'checkRequirements' => 'System Requirements Check',
        'testDatabaseConnection' => 'Database Connection Test',
        'createUploadDirectories' => 'Upload Directory Creation',
        'createSampleDirectories' => 'Sample Directory Creation',
        'generateSetupReport' => 'Setup Report Generation'
    ];
    
    $results = [];
    
    foreach ($steps as $function => $description) {
        echo "\n--- $description ---\n";
        $results[$function] = $function();
    }
    
    echo "\n=== Setup Complete ===\n";
    
    $success = array_filter($results);
    $total = count($results);
    $passed = count($success);
    
    echo "Steps completed: $passed/$total\n";
    
    if ($passed === $total) {
        echo "✓ All setup steps completed successfully!\n";
        echo "\nNext steps:\n";
        echo "1. Run database/setup_verification_table.php to create the verification table\n";
        echo "2. Test the verification system by accessing /Verification/index\n";
        echo "3. Check the setup report for detailed information\n";
    } else {
        echo "⚠ Some setup steps failed. Please review the output above.\n";
    }
}

// Run the setup
runSetup();
?>
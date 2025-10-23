<?php
/**
 * Database Setup Script for Doctor Profile Verification System
 * Run this script to create the doctor_verifications table
 */

// Include database configuration
require_once __DIR__ . '/../app/config/config.php';

// Database connection parameters
$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    
    // Create doctor_verifications table
    $sql = "
    CREATE TABLE IF NOT EXISTS doctor_verifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        photo_path VARCHAR(500) NOT NULL,
        verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        verified_at DATETIME NULL,
        rejection_reason TEXT NULL,
        
        -- Foreign key constraint to Users table
        FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
        
        -- Indexes for performance optimization
        INDEX idx_user_id (user_id),
        INDEX idx_status (verification_status),
        INDEX idx_uploaded_at (uploaded_at),
        
        -- Ensure one verification record per user
        UNIQUE KEY unique_user_verification (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    echo "Doctor verifications table created successfully!\n";
    
    // Add table comment
    $pdo->exec("ALTER TABLE doctor_verifications COMMENT = 'Stores doctor profile verification documents and status'");
    
    echo "\nTable includes:\n";
    echo "- Foreign key relationship to Users table\n";
    echo "- Performance indexes on user_id, verification_status, and uploaded_at\n";
    echo "- Unique constraint to ensure one verification per user\n";
    echo "- Proper ENUM values for verification status\n";
    
    // Verify table creation
    $stmt = $pdo->prepare("DESCRIBE doctor_verifications");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nTable structure verified:\n";
    foreach ($columns as $column) {
        echo sprintf("  %-20s %-30s %-10s\n", 
            $column['Field'], 
            $column['Type'], 
            $column['Key']
        );
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
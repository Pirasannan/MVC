<?php
/**
 * Simple Database Setup Page for Verification System
 * Visit this page in your browser to create the verification table
 */

// Include database configuration
require_once 'app/config/config.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_table'])) {
    try {
        // Create connection using mysqli (more compatible)
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        // Check connection
        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }
        
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "✓ Connected to database successfully!<br>";
        
        // Create the table
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
            
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
            
            INDEX idx_user_id (user_id),
            INDEX idx_status (verification_status),
            INDEX idx_uploaded_at (uploaded_at),
            
            UNIQUE KEY unique_user_verification (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        if ($connection->query($sql) === TRUE) {
            echo "✓ Table 'doctor_verifications' created successfully!<br>";
        } else {
            throw new Exception("Error creating table: " . $connection->error);
        }
        
        // Add table comment
        $commentSql = "ALTER TABLE doctor_verifications COMMENT = 'Stores doctor profile verification documents and status'";
        if ($connection->query($commentSql) === TRUE) {
            echo "✓ Table comment added successfully!<br>";
        }
        
        // Verify table structure
        $result = $connection->query("DESCRIBE doctor_verifications");
        if ($result) {
            echo "<br><strong>Table structure verified:</strong><br>";
            echo "<table border='1' cellpadding='5' cellspacing='0' style='margin-top: 10px;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<br><strong>🎉 Setup completed successfully!</strong><br>";
        echo "You can now use the verification system.<br>";
        echo "<a href='" . URLROOT . "/Pages/doctorProfile' style='color: #007bff;'>Go to Doctor Profile</a>";
        echo "</div>";
        
        $connection->close();
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "❌ Error: " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Verification System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f7fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .info-box {
            background: #e3f2fd;
            color: #1565c0;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .warning-box {
            background: #fff3e0;
            color: #ef6c00;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ff9800;
        }
        .btn {
            background: #4a90e2;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }
        .btn:hover {
            background: #357abd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠️ Database Setup</h1>
        <h2>Doctor Profile Verification System</h2>
        
        <div class="info-box">
            <h3>📋 What this will do:</h3>
            <ul>
                <li>Create the <code>doctor_verifications</code> table</li>
                <li>Set up proper foreign key relationships with Users table</li>
                <li>Add performance indexes</li>
                <li>Configure proper data types and constraints</li>
            </ul>
        </div>
        
        <div class="warning-box">
            <h3>⚠️ Before proceeding:</h3>
            <ul>
                <li>Make sure your <code>Users</code> table exists</li>
                <li>Ensure you have database backup (recommended)</li>
                <li>This is safe to run multiple times (uses IF NOT EXISTS)</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <p><strong>Current Database:</strong> <?php echo DB_NAME; ?></p>
            <p><strong>Host:</strong> <?php echo DB_HOST; ?></p>
            
            <form method="POST">
                <button type="submit" name="create_table" class="btn">
                    🚀 Create Verification Table
                </button>
            </form>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <p>After setup is complete, you can delete this file for security.</p>
        </div>
    </div>
</body>
</html>
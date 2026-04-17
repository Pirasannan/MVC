<?php
/**
 * Setup script – Forgot Password (password_resets table)
 * Visit this page once in your browser to create the table, then delete it.
 */

require_once 'app/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_table'])) {
    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }

        echo "<div style='background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:20px 0;'>";
        echo "✓ Connected to database successfully!<br>";

        $sql = "
        CREATE TABLE IF NOT EXISTS password_resets (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            email      VARCHAR(255) NOT NULL,
            otp        VARCHAR(6)   NOT NULL,
            expires_at DATETIME     NOT NULL,
            used       TINYINT(1)   DEFAULT 0,
            created_at DATETIME     DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        if ($connection->query($sql) === TRUE) {
            echo "✓ Table <code>password_resets</code> created (or already exists)!<br>";
        } else {
            throw new Exception("Error creating table: " . $connection->error);
        }

        // Show table structure
        $result = $connection->query("DESCRIBE password_resets");
        if ($result) {
            echo "<br><strong>Table structure:</strong><br>";
            echo "<table border='1' cellpadding='5' cellspacing='0' style='margin-top:10px;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Field'])           . "</td>";
                echo "<td>" . htmlspecialchars($row['Type'])            . "</td>";
                echo "<td>" . htmlspecialchars($row['Null'])            . "</td>";
                echo "<td>" . htmlspecialchars($row['Key'])             . "</td>";
                echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<br><strong>🎉 Setup complete!</strong><br>";
        echo "<a href='" . URLROOT . "/Users/login' style='color:#007bff;'>Go to Login</a>";
        echo "</div>";

        $connection->close();

    } catch (Exception $e) {
        echo "<div style='background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin:20px 0;'>";
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
    <title>Setup – Forgot Password Table</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; background: #f5f7fa; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        h1 { color: #333; text-align: center; }
        .info-box { background: #e3f2fd; color: #1565c0; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #2196f3; }
        .btn { background: #4a90e2; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; display: block; margin: 20px auto; }
        .btn:hover { background: #357abd; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Forgot Password – DB Setup</h1>

        <div class="info-box">
            <strong>What this creates:</strong>
            <ul>
                <li>Table <code>password_resets</code> — stores OTPs with expiry & used flag</li>
                <li>OTPs expire in 15 minutes and are single-use</li>
                <li>Safe to run multiple times (IF NOT EXISTS)</li>
            </ul>
        </div>

        <p style="text-align:center;"><strong>Database:</strong> <?php echo DB_NAME; ?> &nbsp;|&nbsp; <strong>Host:</strong> <?php echo DB_HOST; ?></p>

        <form method="POST">
            <button type="submit" name="create_table" class="btn">🚀 Create password_resets Table</button>
        </form>

        <p style="text-align:center;color:#999;font-size:13px;margin-top:20px;">Delete this file after setup is complete.</p>
    </div>
</body>
</html>

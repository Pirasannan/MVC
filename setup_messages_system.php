<?php
// Database configuration - Update these with your actual database credentials
$host = 'localhost';
$dbname = 'medilink_db'; // Update with your actual database name
$username = 'root'; // Update with your database username
$password = ''; // Update with your database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    
    // Read and execute the messages schema
    $sql = file_get_contents('database/messages_schema.sql');
    
    // Split the SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo "Executed: " . substr($statement, 0, 50) . "...\n";
        }
    }
    
    echo "\nMessages system setup completed successfully!\n";
    echo "You can now use the Messages Dashboard in your MediLink system.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration and try again.\n";
}
?>
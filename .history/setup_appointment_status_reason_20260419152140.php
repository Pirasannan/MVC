<?php
/**
 * One-time setup for appointment status reasons.
 * Adds appointments.status_reason for doctor cancel/reject messages.
 */

require_once 'app/config/config.php';

$messages = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_setup'])) {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) AS total
             FROM information_schema.columns
             WHERE table_schema = :db
               AND table_name = 'appointments'
               AND column_name = 'status_reason'"
        );
        $stmt->execute([':db' => DB_NAME]);
        $exists = (int)($stmt->fetch(PDO::FETCH_OBJ)->total ?? 0) > 0;

        if ($exists) {
            $messages[] = "Column appointments.status_reason already exists.";
        } else {
            $pdo->exec("ALTER TABLE appointments ADD COLUMN status_reason TEXT NULL AFTER status");
            $messages[] = "Column appointments.status_reason added successfully.";
        }

        $messages[] = 'Setup finished.';
    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Appointment Status Reason</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 24px;
        }
        .card {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }
        h1 {
            margin: 0 0 8px;
        }
        .info, .success, .error {
            border-radius: 8px;
            padding: 12px 14px;
            margin: 14px 0;
        }
        .info {
            background: #e8f1fd;
            color: #0b4ea2;
        }
        .success {
            background: #e9f7ef;
            color: #1f6d3f;
        }
        .error {
            background: #fdecef;
            color: #9d1c30;
        }
        button {
            background: #1f6feb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover {
            background: #1859bc;
        }
        ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Appointment Status Reason Setup</h1>
    <p>This adds a new column used to store doctor reasons when rejecting or cancelling appointments.</p>

    <div class="info">
        <strong>Database:</strong> <?php echo htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8'); ?>
    </div>

    <?php if (!empty($messages)): ?>
        <div class="success">
            <strong>Success</strong>
            <ul>
                <?php foreach ($messages as $message): ?>
                    <li><?php echo $message; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <strong>Error</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="run_setup">Run setup</button>
    </form>
</div>
</body>
</html>

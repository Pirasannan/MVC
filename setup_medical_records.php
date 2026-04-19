<?php
/**
 * Medical Records Setup
 * Visit this page once in your browser to:
 *   1. Create the medical_records table
 *   2. Create the uploads/medical_records directory with protection
 */

require_once 'app/config/config.php';

$messages = [];
$errors   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {

    /* ── 1. DATABASE TABLE ─────────────────────────────────────── */
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // ── Drop & re-create only when asked (idempotent) ──────────
        $sql = "
        CREATE TABLE IF NOT EXISTS `medical_records` (
            `id`            INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `patient_id`    INT(10) UNSIGNED NOT NULL,
            `record_name`   VARCHAR(255)     NOT NULL,
            `record_type`   ENUM('lab','scan','prescription','hospital','vaccination') NOT NULL,
            `doctor_name`   VARCHAR(255)     NOT NULL DEFAULT '',
            `description`   TEXT,
            `file_name`     VARCHAR(255)     NOT NULL,
            `original_name` VARCHAR(255)     NOT NULL,
            `file_size`     INT UNSIGNED     NOT NULL,
            `mime_type`     VARCHAR(100)     NOT NULL,
            `uploaded_at`   DATETIME         DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_patient_id`  (`patient_id`),
            INDEX `idx_record_type` (`record_type`),
            INDEX `idx_uploaded_at` (`uploaded_at`),
            CONSTRAINT `fk_mr_patient`
                FOREIGN KEY (`patient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ";

        $pdo->exec($sql);
        $messages[] = "✓ Table <code>medical_records</code> created (or already exists).";

        // Verify
        $cols = $pdo->query("DESCRIBE `medical_records`")->fetchAll(PDO::FETCH_OBJ);
        $messages[] = "✓ Table structure verified — " . count($cols) . " columns.";

    } catch (PDOException $e) {
        $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
    }

    /* ── 2. UPLOAD DIRECTORY ───────────────────────────────────── */
    $uploadBase = __DIR__ . '/public/uploads/medical_records';

    if (!is_dir($uploadBase)) {
        if (mkdir($uploadBase, 0755, true)) {
            $messages[] = "✓ Created directory <code>public/uploads/medical_records/</code>";
        } else {
            $errors[] = "Could not create upload directory. Please create <code>public/uploads/medical_records/</code> manually with write permissions.";
        }
    } else {
        $messages[] = "✓ Upload directory already exists.";
    }

    /* ── .htaccess inside uploads folder ──────────────────────── */
    $htaccess = $uploadBase . '/.htaccess';
    if (!file_exists($htaccess)) {
        $htContent = "Options -Indexes -ExecCGI\n"
                   . "AddHandler cgi-script .php .php3 .php4 .php5 .phtml .pl .py .jsp .asp .htm .shtml .sh .cgi\n"
                   . "RemoveHandler .php .php3 .php4 .php5 .phtml\n"
                   . "php_flag engine off\n";
        if (file_put_contents($htaccess, $htContent) !== false) {
            $messages[] = "✓ Created <code>.htaccess</code> in upload folder to block script execution.";
        } else {
            $errors[] = "Could not write .htaccess to upload folder.";
        }
    } else {
        $messages[] = "✓ Upload folder .htaccess already exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup – Medical Records | MediLink</title>
    <style>
        body { font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif; background:#f5f7fa; margin:0; padding:40px 20px; }
        .container { max-width:700px; margin:auto; background:#fff; padding:36px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        h1 { margin:0 0 4px; color:#1a1a2e; font-size:1.6rem; }
        h2 { color:#444; font-size:1rem; font-weight:400; margin:0 0 28px; }
        .info  { background:#e8f4fd; color:#1565c0; padding:18px; border-radius:8px; border-left:4px solid #2196f3; margin-bottom:20px; }
        .warn  { background:#fff8e1; color:#7c5c00; padding:18px; border-radius:8px; border-left:4px solid #ffc107; margin-bottom:20px; }
        .success { background:#e8f5e9; color:#2e7d32; padding:18px; border-radius:8px; border-left:4px solid #4caf50; margin-bottom:16px; }
        .error   { background:#fce4ec; color:#b71c1c; padding:18px; border-radius:8px; border-left:4px solid #f44336; margin-bottom:16px; }
        ul { margin:8px 0 0; padding-left:20px; }
        li { margin-bottom:4px; }
        code { background:#f0f0f0; padding:1px 5px; border-radius:3px; font-size:.9em; }
        .btn { display:block; width:100%; padding:14px; background:#4a90e2; color:#fff; border:none; border-radius:8px; font-size:1rem; font-weight:600; cursor:pointer; transition:background .2s; }
        .btn:hover { background:#357abd; }
        .meta { margin-top:10px; font-size:.85rem; color:#777; }
        .divider { margin:28px 0; border:none; border-top:1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <h1>🏥 MediLink – Medical Records Setup</h1>
    <h2>One-time setup for the medical records upload feature</h2>

    <?php if (!empty($messages)): ?>
    <div class="success">
        <strong> Setup completed successfully!</strong>
        <ul>
            <?php foreach ($messages as $m): ?>
            <li><?= $m ?></li>
            <?php endforeach; ?>
        </ul>
        <p style="margin-top:14px">
            You can now <a href="<?= URLROOT ?>/Pages/patientMedicalrecords">go to Medical Records</a>
            or <strong>delete this file</strong> for security.
        </p>
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="error">
        <strong> Errors occurred:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
    </div>
    <?php endif; ?>

    <div class="info">
        <strong>📋 What this does:</strong>
        <ul>
            <li>Creates <code>medical_records</code> table with correct data types &amp; FK</li>
            <li>Creates <code>public/uploads/medical_records/</code> directory</li>
            <li>Adds <code>.htaccess</code> to block script execution in that folder</li>
        </ul>
        <p class="meta">
            DB: <strong><?= DB_HOST ?> / <?= DB_NAME ?></strong>
        </p>
    </div>

    <div class="warn">
        <strong>⚠️ Note:</strong> Safe to run multiple times — uses <code>CREATE TABLE IF NOT EXISTS</code>.
        Delete this file after successful setup.
    </div>

    <form method="POST">
        <button type="submit" name="setup" class="btn">🚀 Run Setup</button>
    </form>
</div>
</body>
</html>

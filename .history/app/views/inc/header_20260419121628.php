<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script>
            (function () {
                try {
                    if (localStorage.getItem('medilinkSidebarCollapsed') === '1') {
                        document.documentElement.classList.add('sidebar-collapsed-persisted');
                    }
                } catch (error) {
                    // Ignore storage access issues.
                }
            })();
        </script>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css?v=<?php echo filemtime(APPROOT.'/../public/css/style.css'); ?>">
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/system-notifications.css?v=<?php echo filemtime(APPROOT.'/../public/css/system-notifications.css'); ?>">
        <script src="<?php echo URLROOT; ?>/public/js/main.js?v=<?php echo filemtime(APPROOT.'/../public/js/main.js'); ?>"></script>
        <script src="<?php echo URLROOT; ?>/public/js/modal-manager.js?v=<?php echo filemtime(APPROOT.'/../public/js/modal-manager.js'); ?>"></script>
        <script src="<?php echo URLROOT; ?>/public/js/sidebar-toggle.js?v=<?php echo filemtime(APPROOT.'/../public/js/sidebar-toggle.js'); ?>"></script>
        
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/appointments.css">
        <script>
            window.APP_CONFIG = {
                urlRoot: <?php echo json_encode(URLROOT); ?>,
                isAuthenticated: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>,
                userId: <?php echo isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 'null'; ?>
            };
        </script>
        <title><?php echo SITENAME; ?></title>
    </head>
    <body>


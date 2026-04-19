<?php
    // Define page titles based on current page
    $page_titles = [
        'adminProfile' => 'Profile',
        'adminDashboard' => 'Admin Dashboard',
        'adminDoctors' => 'Doctors Management',
        'adminPatients' => 'Patients Management',
        'adminRecords' => 'Medical Records',
        'adminNotifications' => 'Notifications',
        'adminReports' => 'Reports',
        'adminResolvedReports' => 'Resolved Reports',
        'adminMessages' => 'Messages'
    ];

    // Get the current page title, default to 'Admin' if not found
    $page_title = isset($current_page) && isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Admin';

    // Admin user information (you can replace this with session data)
    $admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'John Smith';
    $admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '23545';
    $avatar_path = trim((string)($_SESSION['user_profile_image'] ?? ''));
    $avatar_path = str_replace('\\', '/', $avatar_path);
    $avatar_path = preg_replace('#/+#', '/', $avatar_path);
    $avatar_path = ltrim($avatar_path, '/');
    $avatar_url = $avatar_path !== '' ? URLROOT . '/' . $avatar_path : '';
?>

<!-- Top Header -->
<header class="top-header">
    <div class="header-left">
        <h1 class="page-title"><?php echo $page_title; ?></h1>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar">
                <?php if ($avatar_url !== ''): ?>
                    <img src="<?php echo htmlspecialchars($avatar_url); ?>" alt="User avatar" class="avatar-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                    <span class="avatar-icon" style="display:none;">👤</span>
                <?php else: ?>
                    <span class="avatar-icon">👤</span>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <span class="user-name"><?php echo $admin_name; ?></span>
            </div>
        </div>
    </div>
</header>
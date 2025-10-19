<?php
    // Define page titles based on current page
    $page_titles = [
        'adminProfile' => 'Profile',
        'adminDashboard' => 'Admin Dashboard',
        'adminDoctors' => 'Doctors Management',
        'adminPatients' => 'Patients Management',
        'adminMessages' => 'Notifications & Messages',
    ];

    // Get the current page title, default to 'Admin' if not found
    $page_title = isset($current_page) && isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Admin';

    // Admin user information (you can replace this with session data)
    $admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'John Smith';
    $admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : '23545';
?>

<!-- Top Header -->
<header class="top-header">
    <div class="header-left">
        <h1 class="page-title"><?php echo $page_title; ?></h1>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar">
                <span class="avatar-icon">👤</span>
            </div>
            <div class="user-details">
                <span class="user-name"><?php echo $admin_name; ?></span>
                <span class="user-id">ID: <?php echo $admin_id; ?></span>
            </div>
        </div>
    </div>
</header>
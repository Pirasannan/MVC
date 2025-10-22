<?php
    // Define page titles based on current page
    $page_titles = [
        'doctorProfile' => 'Profile',
        'doctorDashboard' => 'Doctor Dashboard',
        'doctorAppointments' => 'Appointments',
        'doctorPrescriptions' => 'Prescriptions',
        'doctorMessages' => 'Messages',
        'doctorMedicalrecords' => 'Medical Records',
    ];

    // Get the current page title, default to 'Doctor' if not found
    $page_title = isset($current_page) && isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Doctor';

    // Doctor user information (you can replace this with session data)
    $doctor_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Doctor';
    $doctor_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A';
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
                <span class="user-name"><?php echo $doctor_name; ?></span>
                <span class="user-id">ID: <?php echo $doctor_id; ?></span>
            </div>
        </div>
    </div>
</header>
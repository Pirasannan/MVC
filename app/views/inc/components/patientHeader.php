<?php
    // Define page titles based on current page
    $page_titles = [
        'patientProfile' => 'Profile',
        'patientDashboard' => 'Patient Dashboard',
        'patientAppointments' => 'Appointments',
        'patientPrescriptions' => 'Prescriptions',
        'patientMessages' => 'Messages',
        'patientMedicalrecords' => 'Medical Records',
    ];

    // Get the current page title, default to 'Patient' if not found
    $page_title = isset($current_page) && isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Patient';

    // Patient user information (you can replace this with session data)
    $patient_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Patient';
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
                <span class="user-name"><?php echo $patient_name; ?></span>
                <span class="user-id">ID: <?php echo $patient_id; ?></span>
            </div>
        </div>
    </div>
</header>
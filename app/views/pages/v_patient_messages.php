<?php require APPROOT.'/views/inc/header.php'; ?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/messages.css?v=<?php echo time(); ?>'>

<div class="dashboard-container patient" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-user-type="<?php echo $_SESSION['user_role'] ?? 'patient'; ?>">
    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <span class="logo-text">MEDILINK</span>
            </div>
        </div>
        
        <nav class="nav-menu">
            <a href="<?php echo URLROOT; ?>/Pages/patientdashboard" class="nav-item">
                Dashboard
            </a>
            <a href="<?php echo URLROOT; ?>/Pages/patientAppointments" class="nav-item">
                Appointments
            </a>
            <a href="<?php echo URLROOT; ?>/Pages/patientPrescriptions" class="nav-item">
                Prescriptions
            </a>
            <a href="<?php echo URLROOT; ?>/Pages/patientMessages" class="nav-item active">
                Messages
            </a>
            <a href="<?php echo URLROOT; ?>/Pages/patientMedicalrecords" class="nav-item">
                Medical Records
            </a>
            <a href="<?php echo URLROOT; ?>/Pages/patientProfile" class="nav-item">
                Profile
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="<?php echo URLROOT; ?>/Users/logout" class="nav-item-bottom" id="logoutBtn">Logout</a>
            <a href="<?php echo URLROOT; ?>/Pages/patientSettings" class="nav-item-bottom" id="settingsBtn">Settings</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <h1 class="page-title">Messages</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <span class="avatar-icon">👤</span>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Patient'); ?></span>
                        <span class="user-id">ID: <?php echo $_SESSION['user_id'] ?? ''; ?></span>
                    </div>
                </div>
            </div>
        </header>

        <?php require APPROOT.'/views/inc/components/messageComponent.php'; ?>
    </main>
</div>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
<script src='<?php echo URLROOT; ?>/js/messages.js?v=<?php echo time(); ?>'></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

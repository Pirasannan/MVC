<?php require APPROOT.'/views/inc/header.php'; ?>




<div class="dashboard-container doctor">        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <span class="logo-text">MEDILINK</span>
                </div> 
            </div>
            
            <nav class="nav-menu">
                <a href="<?php echo URLROOT; ?>/Pages/doctordashboard" class="nav-item ">
                    Dashboard
                </a>
                <a href="<?php echo URLROOT; ?>/Appointments/doctor" class="nav-item active">
                    Appointments
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions" class="nav-item">
                    Prescriptions
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMessages" class="nav-item">
                    Messages
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorMedicalrecords" class="nav-item">
                    Medical Records
                </a>
                <a href="<?php echo URLROOT; ?>/Pages/doctorProfile" class="nav-item">
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <h1 class="page-title">Appointments</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <span class="avatar-icon">👤</span>
                        </div>
                        <div class="user-details">
                            <span class="user-name">John Smith</span>
                            <span class="user-id">ID: 23545</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <?php if(!empty($data['flash'])): ?>
                    <div class="flash-message">
                        <p><?= htmlspecialchars($data['flash']) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Pending Appointments Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Incoming Requests (Need Action)</h2>
                        </div>
                        <div class="section-content">
                            <?php if(empty($data['pending'])): ?>
                                <p class="no-data">No incoming requests.</p>
                            <?php else: ?>
                                <?php foreach($data['pending'] as $a): ?>
                                    <div class="appointment-item">
                                        <div class="appointment-info">
                                            <div class="doctor-name"><?= htmlspecialchars($a->patient_name) ?></div>
                                            <div class="appointment-date"><?= date('Y-m-d', strtotime($a->starts_at)) ?> at <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?></div>
                                        </div>
                                        <div class="appointment-status">
                                            <span class="status-badge pending"><?= htmlspecialchars($a->status) ?></span>
                                        </div>
                                        <div class="appointment-actions">
                                            <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/approved" class="action-button">Approve</a>
                                            <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/rejected" class="action-button secondary">Reject</a>
                                            <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled" class="action-button cancel">Cancel</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Approved Appointments Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Approved Appointments (Ready to Conduct)</h2>
                        </div>
                        <div class="section-content">
                            <?php if(empty($data['approved'])): ?>
                                <p class="no-data">No approved appointments yet.</p>
                            <?php else: ?>
                                <?php foreach($data['approved'] as $a): ?>
                                    <div class="appointment-item">
                                        <div class="appointment-info">
                                            <div class="doctor-name"><?= htmlspecialchars($a->patient_name) ?></div>
                                            <div class="appointment-date"><?= date('Y-m-d', strtotime($a->starts_at)) ?> at <?= date('H:i', strtotime($a->starts_at)) ?>–<?= date('H:i', strtotime($a->ends_at)) ?> <small>(15 min)</small></div>
                                        </div>
                                        <div class="appointment-status">
                                            <span class="status-badge confirmed"><?= htmlspecialchars($a->status) ?></span>
                                        </div>
                                        <div class="appointment-actions">
                                            <button type="button" class="action-button" disabled>Start</button>
                                            <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/cancelled" class="action-button cancel">Cancel</a>
                                            <a href="<?= URLROOT ?>/Appointments/setStatus/<?= $a->id ?>/completed" class="action-button">Complete</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>



        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
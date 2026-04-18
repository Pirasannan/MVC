<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminDashboard';
?>

<div class="dashboard-container">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Cards Row -->
            <div class="stats-row">
                <div class="stat-card primary">
                    <div class="stat-content">
                        <h3 class="stat-title">Registered Doctors</h3>
                        <div class="stat-number"><?php echo (int)($data['stats']['total_doctors'] ?? 0); ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Registered Patients</h3>
                        <div class="stat-number"><?php echo (int)($data['stats']['total_patients'] ?? 0); ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title no-margin">Consultations</h3>
                        <h4 class="stat-subtitle">Last Week</h4>
                        <div class="stat-number"><?php echo (int)($data['stats']['consultations_last_week'] ?? 0); ?></div>
                    </div>
                </div>
            </div>

            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Pending Doctor Verifications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Pending Doctor Verifications</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Rajesh Kumar</div>
                                <div class="appointment-date">Submitted: 2025-10-15 | Cardiology</div>
                                <div class="prescribed-by">NIC & Medical License uploaded</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Nisha Perera</div>
                                <div class="appointment-date">Submitted: 2025-10-16 | General Practice</div>
                                <div class="prescribed-by">NIC & Medical License uploaded</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Amara Fernando</div>
                                <div class="appointment-date">Submitted: 2025-10-17 | Pediatrics</div>
                                <div class="prescribed-by">NIC & Medical License uploaded</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminDoctorVerification"><button class="action-button">View All Verifications</button></a>
                    </div>
                </div>
            </div>
            
            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- System Activity Log Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent System Activity</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Doctor Account Approved</div>
                                <div class="medication-details">Dr. Sunil Jayawardena verified and activated</div>
                                <div class="prescribed-by">Action by: Admin User</div>
                            </div>
                            <div class="medication-date">5 hours ago</div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Patient Flagged Content</div>
                                <div class="medication-details">Inappropriate message reported in consultation chat</div>
                                <div class="prescribed-by">Status: Under Review</div>
                            </div>
                            <div class="medication-date">1 day ago</div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminSystemActivityLog">
                            <button class="action-button secondary">View Full Activity Log</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
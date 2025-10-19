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
                        <div class="stat-number">8</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Registered Patients</h3>
                        <div class="stat-number">156</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title no-margin">Consultations</h3>
                        <h4 class="stat-subtitle">Last Week</h4>
                        <div class="stat-number">156</div>
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
                        <button class="action-button">View All Verifications</button>
                    </div>
                </div>

                <!-- System Activity Log Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent System Activity</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">New Clinic Registration</div>
                                <div class="medication-details">City Care Medical Center - Colombo 7</div>
                                <div class="prescribed-by">Awaiting subscription activation</div>
                            </div>
                            <div class="medication-date">2 hours ago</div>
                        </div>
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
                        <button class="action-button secondary">View Full Activity Log</button>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Clinic Subscriptions Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Clinic Subscriptions</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Wellness Medical Clinic</div>
                                <div class="appointment-date">Plan: Premium | Expires: 2026-01-15</div>
                                <div class="prescribed-by">5 doctors, 78 patients</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">HealthFirst GP Center</div>
                                <div class="appointment-date">Plan: Standard | Expires: 2025-11-30</div>
                                <div class="prescribed-by">3 doctors, 42 patients</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Central Care Clinic</div>
                                <div class="appointment-date">Plan: Basic | Expires: 2025-10-25</div>
                                <div class="prescribed-by">2 doctors, 18 patients</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge scheduled">Expiring Soon</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Manage Subscriptions</button>
                    </div>
                </div>

                <!-- Patient Verifications & Support Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Pending Patient Verifications</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Kamal Wickramasinghe</div>
                                <div class="medication-details">Phone: +94 77 123 4567 | OTP Verified</div>
                                <div class="prescribed-by">NIC document uploaded for review</div>
                            </div>
                            <div class="medication-date">2025-10-18</div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Priya Mendis</div>
                                <div class="medication-details">Email: priya.m@email.com | OTP Verified</div>
                                <div class="prescribed-by">NIC document uploaded for review</div>
                            </div>
                            <div class="medication-date">2025-10-17</div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Saman De Silva</div>
                                <div class="medication-details">Phone: +94 71 987 6543 | OTP Verified</div>
                                <div class="prescribed-by">NIC document uploaded for review</div>
                            </div>
                            <div class="medication-date">2025-10-17</div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button secondary">Review All Patients</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
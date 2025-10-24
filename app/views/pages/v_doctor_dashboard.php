<?php 
    require APPROOT.'/views/inc/header.php';
    $current_page = 'doctorDashboard';
?>




<div class="dashboard-container doctor">        
        <!-- Sidebar Navigation -->        
        <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>
                
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card ">
                        <div class="stat-content">
                            <h3 class="stat-title">Today's Appointments</h3>
                            <div class="stat-number">5</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Prescribed Patients</h3>
                            <div class="stat-number">12</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number">3</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Pending Prescriptions</h3>
                            <div class="stat-number">2</div>
                        </div>
                    </div>
                </div>

                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Appointments Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Upcoming Appointments</h2>
                        </div>
                        <div class="section-content">
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="patient-name">John Smith</div>
                                    <div class="appointment-date">Today at 10:00 AM</div>
                                    <div class="appointment-type">Initial Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge scheduled">Scheduled</span>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="patient-name">Sarah Wilson</div>
                                    <div class="appointment-date">Today at 2:30 PM</div>
                                    <div class="appointment-type">Follow-up Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge confirmed">Confirmed</span>
                                    <a href="<?php echo URLROOT; ?>/Pages/doctorPrecall" class="start-consultation-btn">
                                        <i class="fas fa-video"></i> Start Consultation
                                    </a>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="patient-name">Michael Brown</div>
                                    <div class="appointment-date">Tomorrow at 11:15 AM</div>
                                    <div class="appointment-type">Initial Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="patient-name">Emily Davis</div>
                                    <div class="appointment-date">Tomorrow at 3:45 PM</div>
                                    <div class="appointment-type">Follow-up Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge scheduled">Confirmed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Notifications Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Notifications</h2>
                        </div>
                        <div class="section-content">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">New Patient Registration</div>
                                    <div class="notification-message">A new patient has registered and requires verification</div>
                                    <div class="notification-time">2 hours ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge pending">New</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">System Update</div>
                                    <div class="notification-message">New features added to prescription management</div>
                                    <div class="notification-time">3 days ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge confirmed">Read</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">Maintenance Notice</div>
                                    <div class="notification-message">Scheduled maintenance tonight from 2-4 AM</div>
                                    <div class="notification-time">5 days ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge confirmed">Read</span>
                                </div>
                            </div>
                        </div>
                        <div class="section-footer">
                            <button class="action-button secondary">View All Notifications</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
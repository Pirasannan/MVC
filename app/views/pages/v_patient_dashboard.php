<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientDashboard';
?>




<div class="dashboard-container patient">        
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Today's Appointments</h3>
                            <div class="stat-number">1</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Active Medications</h3>
                            <div class="stat-number">3</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number">2</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Recent Prescriptions</h3>
                            <div class="stat-number">5</div>
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
                                    <div class="doctor-name">Dr. John Smith</div>
                                    <div class="appointment-date">Today at 2:30 PM</div>
                                    <div class="appointment-type">Follow-up Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge confirmed">Confirmed</span>
                                    <a href="<?php echo URLROOT; ?>/Pages/patientPrecall" class="join-consultation-btn">
                                        <i class="fas fa-video"></i> Join Consultation
                                    </a>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">Dr. Sarah Johnson</div>
                                    <div class="appointment-date">Tomorrow at 10:00 AM</div>
                                    <div class="appointment-type">General Checkup</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge scheduled">Scheduled</span>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">Dr. Michael Chen</div>
                                    <div class="appointment-date">Jan 20 at 2:30 PM</div>
                                    <div class="appointment-type">Specialist Consultation</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                        </div>
                        <div class="section-footer">
                            <button class="action-button">Book New Appointment</button>
                        </div>
                    </div>

                    <!-- System Notifications Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">System Notifications</h2>
                        </div>
                        <div class="section-content">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">New Prescription Available</div>
                                    <div class="notification-message">Dr. John Smith has prescribed new medication for your condition</div>
                                    <div class="notification-time">2 hours ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge new">New</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">Appointment Reminder</div>
                                    <div class="notification-message">Your appointment with Dr. Sarah Johnson is tomorrow at 10:00 AM</div>
                                    <div class="notification-time">1 day ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge read">Read</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">Lab Results Ready</div>
                                    <div class="notification-message">Your recent blood test results are now available for review</div>
                                    <div class="notification-time">3 days ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge read">Read</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">Prescription Refill Due</div>
                                    <div class="notification-message">Your medication Lisinopril is due for refill in 3 days</div>
                                    <div class="notification-time">1 week ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge read">Read</span>
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
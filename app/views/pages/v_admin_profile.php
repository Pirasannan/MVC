<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminProfile';
?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>


        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Personal Information Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Personal Information</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Full Name</div>
                                <div class="appointment-date">
                                    <input type="text" value="John Smith" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Admin ID</div>
                                <div class="appointment-date">
                                    <input type="text" value="23545" disabled style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px; background: #f5f5f5;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Email Address</div>
                                <div class="appointment-date">
                                    <input type="email" value="john.smith@medilife.com" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Phone Number</div>
                                <div class="appointment-date">
                                    <input type="text" value="+94 77 123 4567" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Update Information</button>
                    </div>
                </div>

                <!-- Security Settings Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Security Settings</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Change Password</div>
                                <div class="medication-details">Last changed: 2025-09-18</div>
                                <div class="prescribed-by">Recommendation: Change password every 90 days</div>
                            </div>
                            <div class="medication-date">
                                <button class="action-button secondary" style="padding: 8px 16px; font-size: 14px;">Change</button>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Two-Factor Authentication</div>
                                <div class="medication-details">Status: Enabled</div>
                                <div class="prescribed-by">Method: SMS to +94 77 XXX 4567</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Active Sessions</div>
                                <div class="medication-details">Current Device: Chrome on Windows</div>
                                <div class="prescribed-by">Last Login: 2025-10-18 at 9:30 AM</div>
                            </div>
                            <div class="medication-date">
                                <button class="action-button secondary" style="padding: 8px 16px; font-size: 14px;">View All</button>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Security Overview</button>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Admin Activity Log Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Activity</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Doctor Verification Approved</div>
                                <div class="appointment-date">Approved: Dr. Sunil Jayawardena</div>
                                <div class="prescribed-by">Action performed: 2025-10-18 at 10:15 AM</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">System Notification Sent</div>
                                <div class="appointment-date">Recipients: All Users</div>
                                <div class="prescribed-by">Action performed: 2025-10-18 at 9:45 AM</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Patient Account Suspended</div>
                                <div class="appointment-date">Suspended: Yasmin Fonseka</div>
                                <div class="prescribed-by">Action performed: 2025-10-17 at 4:30 PM</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge scheduled">Completed</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View Full Activity Log</button>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Account Settings</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Email Notifications</div>
                                <div class="medication-details">Receive alerts for critical system events</div>
                                <div class="prescribed-by">Status: Enabled for urgent notifications only</div>
                            </div>
                            <div class="medication-date">
                                <button class="action-button secondary" style="padding: 8px 16px; font-size: 14px;">Manage</button>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Admin Privileges</div>
                                <div class="medication-details">Role: Super Admin</div>
                                <div class="prescribed-by">Full access to all system functions and user management</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Account Status</div>
                                <div class="medication-details">Account created: 2024-03-15</div>
                                <div class="prescribed-by">Last profile update: 2025-09-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button secondary">Advanced Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
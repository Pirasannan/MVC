<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'adminProfile';
?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT . '/views/inc/components/adminHeader.php'; ?>


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
                                    <input type="text" value="John Smith"
                                        style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Admin ID</div>
                                <div class="appointment-date">
                                    <input type="text" value="23545" disabled
                                        style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px; background: #f5f5f5;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Email Address</div>
                                <div class="appointment-date">
                                    <input type="email" value="john.smith@medilife.com"
                                        style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Phone Number</div>
                                <div class="appointment-date">
                                    <input type="text" value="+94 77 123 4567"
                                        style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminProfileUpdate"><button class="action-button">Update
                                Information</button></a>
                    </div>
                </div>

                <!-- Account Security Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Account Security</h2>
                    </div>
                    <div class="section-content"
                        style="display: flex; flex-direction: column; gap: 16px; padding: 24px;">

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; color: #1e293b; font-size: 15px; font-weight: 600;">Change
                                    Password</h4>
                            </div>
                            <button class="btn-profile-security"
                                onclick="location.href='<?php echo URLROOT; ?>/Users/requestProfileOtp?action=change_password'">Change Password</button>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background-color: #fff1f2; border-radius: 8px; border: 1px solid #fecdd3;">
                            <div>
                                <h4 style="margin: 0 0 4px 0; color: #9f1239; font-size: 15px; font-weight: 600;">
                                    Deactivate Account</h4>
                            </div>
                            <button class="btn-profile-deactivate"
                                onclick="if(confirm('Are you sure you want to deactivate your account?')) location.href='<?php echo URLROOT; ?>/Users/requestProfileOtp?action=deactivate'">Deactivate</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Admin Activity Log Section -->
                <div class="content-section full-width">
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
                        <a href="<?php echo URLROOT; ?>/Pages/adminActivityLog">
                            <button class="action-button">View Full Activity Log</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
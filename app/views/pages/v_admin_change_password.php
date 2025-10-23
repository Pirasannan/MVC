<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminChangePassword';
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
            <!-- Back Navigation -->
            <div class="back-navigation">
                <a href="<?php echo URLROOT; ?>/Pages/adminProfile" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Change Password Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Change Password</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Current Password</div>
                                <div class="appointment-date">
                                    <input type="password" id="currentPassword" placeholder="Enter current password" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">New Password</div>
                                <div class="appointment-date">
                                    <input type="password" id="newPassword" placeholder="Enter new password" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Confirm New Password</div>
                                <div class="appointment-date">
                                    <input type="password" id="confirmPassword" placeholder="Confirm new password" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button id="changePasswordBtn" class="action-button">Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

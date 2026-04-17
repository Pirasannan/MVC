<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'doctorProfile';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/profile/doctor_profile.css'>

<div class="dashboard-container doctor">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT . '/views/inc/components/doctorHeader.php'; ?>

        <!-- Profile Content -->
        <div class="dashboard-content">
            <div class="profile-container">

                <!-- Profile Information Section -->
                <div class="content-section profile-info">
                    <div class="section-header centered">
                        <h2 class="section-title">Profile Information</h2>
                    </div>
                    <div class="section-content">
                        <div class="profile-details">
                            <div class="profile-row">
                                <div class="profile-field">
                                    <label>Full Name:</label>
                                    <span><?php echo htmlspecialchars($data['user_name']); ?></span>
                                </div>
                                <div class="profile-field">
                                    <label>Email:</label>
                                    <span><?php echo htmlspecialchars($data['user_email']); ?></span>
                                </div>
                            </div>
                            <div class="profile-row">
                                <div class="profile-field">
                                    <label>User ID:</label>
                                    <span><?php echo $data['user_id']; ?></span>
                                </div>
                                <div class="profile-field">
                                    <label>Role:</label>
                                    <span class="role-badge doctor">Doctor</span>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Button Row -->
                        <div class="profile-edit-row">
                            <button class="action-button primary edit-profile-btn">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Profile Verification Section -->
                <div class="content-section verification-section">
                    <div class="section-header centered">
                        <h2 class="section-title">
                            <i class="fas fa-shield-alt"></i> Profile Verification
                        </h2>
                    </div>
                    <div class="section-content">
                        <?php if (!$data['verification']): ?>
                            <!-- No verification -->
                            <div class="verification-status no-verification">
                                <div class="status-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="status-content">
                                    <h4>Profile Not Verified</h4>
                                    <p>Complete your profile verification to gain full access to the platform.</p>
                                    <div class="verification-actions">
                                        <a href="<?php echo URLROOT; ?>/Verification/index" class="action-button primary">
                                            <i class="fas fa-upload"></i> Start Verification
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Has verification -->
                            <?php
                            $status = $data['verification']->verification_status;
                            $statusClass = '';
                            $statusIcon = '';
                            $statusText = '';
                            $statusMessage = '';

                            switch ($status) {
                                case 'pending':
                                    $statusClass = 'pending';
                                    $statusIcon = 'clock';
                                    $statusText = 'Verification Pending';
                                    $statusMessage = 'Your verification is under review. You will be notified once processed.';
                                    break;
                                case 'verified':
                                    $statusClass = 'verified';
                                    $statusIcon = 'check-circle';
                                    $statusText = 'Profile Verified';
                                    $statusMessage = 'Your profile has been successfully verified!';
                                    break;
                                case 'rejected':
                                    $statusClass = 'rejected';
                                    $statusIcon = 'times-circle';
                                    $statusText = 'Verification Rejected';
                                    $statusMessage = 'Your verification was rejected. Please upload a new document.';
                                    break;
                            }
                            ?>

                            <div class="verification-status <?php echo $statusClass; ?>">
                                <div class="status-icon">
                                    <i class="fas fa-<?php echo $statusIcon; ?>"></i>
                                </div>
                                <div class="status-content">
                                    <h4><?php echo $statusText; ?></h4>
                                    <p><?php echo $statusMessage; ?></p>

                                    <div class="verification-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Uploaded:</span>
                                            <span
                                                class="detail-value"><?php echo date('M d, Y H:i', strtotime($data['verification']->uploaded_at)); ?></span>
                                        </div>

                                        <?php if ($data['verification']->verified_at): ?>
                                            <div class="detail-item">
                                                <span class="detail-label">Verified:</span>
                                                <span
                                                    class="detail-value"><?php echo date('M d, Y H:i', strtotime($data['verification']->verified_at)); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($data['verification']->rejection_reason): ?>
                                            <div class="detail-item rejection-reason">
                                                <span class="detail-label">Reason:</span>
                                                <span
                                                    class="detail-value"><?php echo htmlspecialchars($data['verification']->rejection_reason); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="verification-actions">
                                        <a href="<?php echo URLROOT; ?>/Verification/viewPhoto"
                                            class="action-button secondary">
                                            <i class="fas fa-eye"></i> View Photo
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/Verification/index" class="action-button">
                                            <i class="fas fa-cog"></i> Manage
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Account Security Section -->
            <div class="content-section">
                <div class="section-header centered">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i> Account Security
                    </h2>
                </div>
                <div class="section-content" style="display: flex; flex-direction: column; gap: 16px; padding: 24px;">

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
                            <h4 style="margin: 0 0 4px 0; color: #9f1239; font-size: 15px; font-weight: 600;">Deactivate
                                Account</h4>
                        </div>
                        <button class="btn-profile-deactivate"
                            onclick="if(confirm('Are you sure you want to deactivate your account?')) location.href='<?php echo URLROOT; ?>/Users/requestProfileOtp?action=deactivate'">Deactivate</button>
                    </div>

                </div>
            </div>

        </div>
</div>
</main>
</div>




<?php require APPROOT . '/views/inc/footer.php'; ?>
<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'doctorProfile';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/profile/doctor_profile.css'>

<div class="dashboard-container doctor">
    <?php require APPROOT . '/views/inc/components/doctorSidebar.php'; ?>

    <main class="main-content">
        <?php require APPROOT . '/views/inc/components/doctorHeader.php'; ?>

        <div class="dashboard-content">
            <?php if (!empty($data['profile_success'])): ?>
                <div class="profile-message success"><?php echo htmlspecialchars($data['profile_success']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['profile_image_success'])): ?>
                <div class="profile-message success"><?php echo htmlspecialchars($data['profile_image_success']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['profile_image_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['profile_image_err']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['user_name_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['user_name_err']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['user_email_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['user_email_err']); ?></div>
            <?php endif; ?>

            <div class="doctor-profile-layout">
                <div class="doctor-profile-card">
                    <div class="profile-avatar-wrap">
                        <?php if (!empty($data['profile_image'])): ?>
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($data['profile_image']); ?>"
                                alt="Profile picture" class="profile-avatar-image">
                        <?php else: ?>
                            <div class="profile-avatar-circle">
                                <?php echo strtoupper(substr(trim($data['user_name']), 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <h3 class="doctor-name"><?php echo htmlspecialchars($data['user_name']); ?></h3>
                    <p class="doctor-role">Doctor</p>
                    <button type="button" class="action-button primary profile-edit-btn"
                        id="open-doctor-profile-edit-modal-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>

                <div class="doctor-details-card">
                    <h3 class="details-title">Doctor Information</h3>
                    <div class="details-table">
                        <div class="details-row">
                            <span>Email</span><span><?php echo htmlspecialchars($data['user_email']); ?></span></div>
                        <div class="details-row"><span>Doctor
                                ID</span><span>#<?php echo htmlspecialchars($data['user_id']); ?></span></div>
                        <div class="details-row"><span>Role</span><span><span
                                    class="status-badge doctor">Doctor</span></span></div>
                        <div class="details-row"><span>Member Since</span><span><?php echo date('M Y'); ?></span></div>
                    </div>
                </div>
            </div>

            <div class="doctor-details-card security-card full-width">
                <h3 class="details-title"><i class="fas fa-shield-alt"></i> Security & Account Management</h3>
                <div class="security-info-grid">
                    <div class="security-item">
                        <div class="security-icon-circle">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="security-text">
                            <h4>Change Account Password</h4>
                            <p>Regularly updating your password ensures the security of your professional account and
                                patient data confidentiality.</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Users/securityOtp?action=password"
                            class="action-button secondary">
                            <i class="fas fa-lock"></i> Update Password
                        </a>
                    </div>
                    <div class="security-item">
                        <div class="security-icon-circle danger">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="security-text">
                            <h4>Deactivate Professional Profile</h4>
                            <p>Deactivating your profile will suspend your professional services on the platform. This
                                action requires secure verification.</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Users/securityOtp?action=deactivate"
                            class="action-button danger">
                            <i class="fas fa-trash-alt"></i> Deactivate Account
                        </a>
                    </div>
                </div>
            </div>

            <div class="content-section verification-section">
                <div class="section-header centered">
                    <h2 class="section-title">
                        <i class="fas fa-shield-alt"></i> Profile Verification
                    </h2>
                </div>
                <div class="section-content">
                    <?php if (!$data['verification']): ?>
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
                                    <a href="<?php echo URLROOT; ?>/Verification/viewPhoto" class="action-button secondary">
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
    </main>

    <div class="modal-overlay <?php echo (!empty($data['profile_image_err']) || !empty($data['user_name_err']) || !empty($data['user_email_err'])) ? 'active' : ''; ?>"
        id="doctor-profile-edit-modal-overlay">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="doctor-profile-edit-modal-title">
            <div class="modal-header">
                <h3 id="doctor-profile-edit-modal-title">Edit Profile</h3>
                <button type="button" class="modal-close" id="close-doctor-profile-edit-modal-btn"
                    aria-label="Close">&times;</button>
            </div>

            <form action="<?php echo URLROOT; ?>/Pages/doctorprofile" method="POST" enctype="multipart/form-data"
                class="modal-form upload-form">
                <input type="hidden" name="form_type" value="update_profile_image">

                <div class="modal-field">
                    <label for="doctor_profile_image">Profile Image (JPG/PNG)</label>
                    <input type="file" id="doctor_profile_image" name="profile_image" class="profile-input"
                        accept="image/jpeg,image/jpg,image/png" required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="action-button secondary">Upload Picture</button>
                </div>
            </form>

            <form action="<?php echo URLROOT; ?>/Pages/doctorprofile" method="POST" class="modal-form">
                <input type="hidden" name="form_type" value="update_profile_details">

                <div class="modal-field">
                    <label for="doctor_user_name">Change Name</label>
                    <input type="text" id="doctor_user_name" name="user_name"
                        value="<?php echo htmlspecialchars($data['user_name']); ?>"
                        class="profile-input <?php echo !empty($data['user_name_err']) ? 'is-invalid' : ''; ?>"
                        required>
                </div>

                <div class="modal-field">
                    <label for="doctor_user_email">Change Email</label>
                    <input type="email" id="doctor_user_email" name="user_email"
                        value="<?php echo htmlspecialchars($data['user_email']); ?>"
                        class="profile-input <?php echo !empty($data['user_email_err']) ? 'is-invalid' : ''; ?>"
                        required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="action-button primary">Save Changes</button>
                    <button type="button" class="action-button"
                        id="cancel-doctor-profile-edit-modal-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openButton = document.getElementById('open-doctor-profile-edit-modal-btn');
        const closeButton = document.getElementById('close-doctor-profile-edit-modal-btn');
        const cancelButton = document.getElementById('cancel-doctor-profile-edit-modal-btn');
        const modal = document.getElementById('doctor-profile-edit-modal-overlay');

        if (!modal || !openButton) {
            return;
        }

        const openModal = function () {
            modal.classList.add('active');
        };

        const closeModal = function () {
            modal.classList.remove('active');
        };

        openButton.addEventListener('click', openModal);

        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', closeModal);
        }

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'adminProfile';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/profile/admin_profile.css'>

<?php
$profileImagePath = trim((string)($data['profile_image'] ?? ($_SESSION['user_profile_image'] ?? '')));
$profileImagePath = str_replace('\\', '/', $profileImagePath);
$profileImagePath = preg_replace('#/+#', '/', $profileImagePath);
$profileImagePath = ltrim($profileImagePath, '/');
$profileImageUrl = $profileImagePath !== '' ? URLROOT . '/' . $profileImagePath : '';
?>

<div class="dashboard-container admin">
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <main class="main-content">
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

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

            <?php if (!empty($data['name_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['name_err']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['email_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['email_err']); ?></div>
            <?php endif; ?>

            <div class="admin-profile-layout">
                <div class="admin-profile-card">
                    <div class="profile-avatar-wrap">
                        <img
                            src="<?php echo htmlspecialchars($profileImageUrl); ?>"
                            alt="Profile picture"
                            class="profile-avatar-image <?php echo $profileImageUrl !== '' ? '' : 'is-hidden'; ?>"
                            onerror="this.classList.add('is-hidden'); if(this.nextElementSibling){ this.nextElementSibling.classList.remove('is-hidden'); }"
                        >
                        <div class="profile-avatar-circle <?php echo $profileImageUrl !== '' ? 'is-hidden' : ''; ?>"><?php echo strtoupper(substr(trim($data['admin_name']), 0, 1)); ?></div>
                    </div>
                    <h3 class="admin-name"><?php echo htmlspecialchars($data['admin_name']); ?></h3>
                    <p class="admin-role"><?php echo htmlspecialchars($data['role']); ?></p>
                    <button type="button" class="action-button primary profile-edit-btn" id="open-admin-profile-edit-modal-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>

                <div class="admin-details-card">
                    <h3 class="details-title">Personal Information</h3>
                    <div class="details-table">
                        <div class="details-row"><span>Email</span><span><?php echo htmlspecialchars($data['admin_email']); ?></span></div>
                        <div class="details-row"><span>Admin ID</span><span>#<?php echo htmlspecialchars($data['admin_id']); ?></span></div>
                        <div class="details-row"><span>Role</span><span><span class="status-badge admin"><?php echo htmlspecialchars($data['role']); ?></span></span></div>
                        <div class="details-row"><span>Account Status</span><span><span class="status-badge active"><?php echo htmlspecialchars(ucfirst($data['status'])); ?></span></span></div>
                    </div>
                </div>
            </div>

            <div class="admin-details-card security-card full-width">
                <h3 class="details-title"><i class="fas fa-user-lock"></i> Security & Credential Management</h3>
                <div class="security-info-grid">
                    <div class="security-item">
                        <div class="security-icon-circle">
                            <i class="fas fa-password"></i>
                        </div>
                        <div class="security-text">
                            <h4>Administrative Password</h4>
                            <p>Update your administrative password to maintain system integrity. A strong password is essential for high-level access nodes.</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Users/securityOtp?action=password" class="action-button secondary">
                            <i class="fas fa-shield-alt"></i> Change Password
                        </a>
                    </div>
                    <div class="security-item">
                        <div class="security-icon-circle danger">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="security-text">
                            <h4>System Deactivation</h4>
                            <p>Deactivating your administrative account will revoke all system privileges. This is a critical action requiring OTP verification.</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Users/securityOtp?action=deactivate" class="action-button danger">
                            <i class="fas fa-exclamation-triangle"></i> Deactivate Admin
                        </a>
                    </div>
                </div>
            </div>

            <div class="content-section admin-activity-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Activities</h2>
                </div>
                <div class="section-content activity-list">
                    <?php if (!empty($data['recent_activity'])): ?>
                        <?php foreach ($data['recent_activity'] as $activity): ?>
                            <?php
                                $status = strtolower($activity->status ?? 'completed');
                                $timeValue = $activity->timestamp ?? null;
                                $formattedTime = $timeValue ? date('M d, Y h:i A', strtotime($timeValue)) : '-';
                            ?>
                            <div class="activity-item">
                                <div class="activity-info">
                                    <div class="activity-title"><?php echo htmlspecialchars($activity->action ?? 'Activity'); ?></div>
                                    <div class="activity-details"><?php echo htmlspecialchars($activity->details ?? ''); ?></div>
                                    <div class="activity-date"><?php echo htmlspecialchars($formattedTime); ?></div>
                                </div>
                                <div class="activity-status-wrap">
                                    <span class="status-badge <?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars(ucfirst($status)); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-activity">No recent activity available.</div>
                    <?php endif; ?>
                </div>
                <div class="section-footer">
                    <a href="<?php echo URLROOT; ?>/Pages/adminActivityLog">
                        <button class="action-button secondary">View Full Activity Log</button>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div class="modal-overlay <?php echo (!empty($data['name_err']) || !empty($data['email_err']) || !empty($data['profile_image_err'])) ? 'active' : ''; ?>" id="admin-profile-edit-modal-overlay">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="admin-profile-edit-modal-title">
            <div class="modal-header">
                <h3 id="admin-profile-edit-modal-title">Edit Personal Information</h3>
                <button type="button" class="modal-close" id="close-admin-profile-edit-modal-btn" aria-label="Close">&times;</button>
            </div>

            <form action="<?php echo URLROOT; ?>/Pages/adminProfile" method="POST" enctype="multipart/form-data" class="modal-form upload-form">
                <input type="hidden" name="form_type" value="update_profile_image">

                <div class="modal-field">
                    <label for="admin_profile_image">Profile Image (JPG/PNG)</label>
                    <input type="file" id="admin_profile_image" name="profile_image" class="profile-input" accept="image/jpeg,image/jpg,image/png" required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="action-button secondary">Upload Picture</button>
                </div>
            </form>

            <form action="<?php echo URLROOT; ?>/Pages/adminProfile" method="POST" class="modal-form">
                <input type="hidden" name="form_type" value="update_profile_details">

                <div class="modal-field">
                    <label for="admin_user_name">Full Name</label>
                    <input
                        type="text"
                        id="admin_user_name"
                        name="user_name"
                        value="<?php echo htmlspecialchars($data['admin_name']); ?>"
                        class="profile-input <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>"
                        required
                    >
                </div>

                <div class="modal-field">
                    <label for="admin_user_email">Email Address</label>
                    <input
                        type="email"
                        id="admin_user_email"
                        name="user_email"
                        value="<?php echo htmlspecialchars($data['admin_email']); ?>"
                        class="profile-input <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>"
                        required
                    >
                </div>

                <div class="modal-actions">
                    <button type="submit" class="action-button primary">Save Changes</button>
                    <button type="button" class="action-button" id="cancel-admin-profile-edit-modal-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const openButton = document.getElementById('open-admin-profile-edit-modal-btn');
    const closeButton = document.getElementById('close-admin-profile-edit-modal-btn');
    const cancelButton = document.getElementById('cancel-admin-profile-edit-modal-btn');
    const modal = document.getElementById('admin-profile-edit-modal-overlay');

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

<?php require APPROOT.'/views/inc/footer.php'; ?>

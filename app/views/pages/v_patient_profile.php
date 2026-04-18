<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientProfile';

$profileImagePath = trim((string)($data['profile_image'] ?? ($_SESSION['user_profile_image'] ?? '')));
$profileImagePath = str_replace('\\', '/', $profileImagePath);
$profileImagePath = preg_replace('#/+#', '/', $profileImagePath);
$profileImagePath = ltrim($profileImagePath, '/');
$profileImageUrl = $profileImagePath !== '' ? URLROOT . '/' . $profileImagePath : '';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/profile/patient_profile.css'>

<div class="dashboard-container patient">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <?php if (!empty($data['name_success'])): ?>
                <div class="profile-message success"><?php echo htmlspecialchars($data['name_success']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['profile_image_success'])): ?>
                <div class="profile-message success"><?php echo htmlspecialchars($data['profile_image_success']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['medical_success'])): ?>
                <div class="profile-message success"><?php echo htmlspecialchars($data['medical_success']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['profile_image_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['profile_image_err']); ?></div>
            <?php endif; ?>

            <?php if (!empty($data['name_err'])): ?>
                <div class="profile-message error"><?php echo htmlspecialchars($data['name_err']); ?></div>
            <?php endif; ?>

            <div class="patient-profile-layout">
                <div class="patient-profile-card">
                    <div class="profile-avatar-wrap">
                        <img
                            src="<?php echo htmlspecialchars($profileImageUrl); ?>"
                            alt="Profile picture"
                            class="profile-avatar-image <?php echo $profileImageUrl !== '' ? '' : 'is-hidden'; ?>"
                            onerror="this.classList.add('is-hidden'); if(this.nextElementSibling){ this.nextElementSibling.classList.remove('is-hidden'); }"
                        >
                        <div class="profile-avatar-circle <?php echo $profileImageUrl !== '' ? 'is-hidden' : ''; ?>"><?php echo strtoupper(substr(trim($data['user_name']), 0, 1)); ?></div>
                    </div>
                    <h3 class="patient-name"><?php echo htmlspecialchars($data['user_name']); ?></h3>
                    <p class="patient-role">Patient</p>
                    <button type="button" class="action-button primary profile-edit-btn" id="open-profile-edit-modal-btn">Edit Profile</button>
                </div>

                <div class="patient-details-card">
                    <h3 class="details-title">Patient Information</h3>
                    <div class="details-table">
                        <div class="details-row"><span>Email</span><span><?php echo htmlspecialchars($data['user_email']); ?></span></div>
                        <div class="details-row"><span>Patient ID</span><span>#<?php echo htmlspecialchars($data['user_id']); ?></span></div>
                        <div class="details-row"><span>Account Status</span><span><span class="status-badge active">Active</span></span></div>
                        <div class="details-row"><span>Member Since</span><span><?php echo date('M Y'); ?></span></div>
                        <div class="details-row"><span>Blood Type</span><span><?php echo !empty($data['medical_info']->blood_type) ? htmlspecialchars($data['medical_info']->blood_type) : '-'; ?></span></div>
                        <div class="details-row"><span>Date of Birth</span><span><?php echo !empty($data['medical_info']->date_of_birth) ? htmlspecialchars($data['medical_info']->date_of_birth) : '-'; ?></span></div>
                        <div class="details-row"><span>Emergency Contact</span><span><?php echo !empty($data['medical_info']->emergency_contact) ? htmlspecialchars($data['medical_info']->emergency_contact) : '-'; ?></span></div>
                        <div class="details-row"><span>Insurance Provider</span><span><?php echo !empty($data['medical_info']->insurance_provider) ? htmlspecialchars($data['medical_info']->insurance_provider) : '-'; ?></span></div>
                        <div class="details-row"><span>Allergies</span><span><?php echo !empty($data['medical_info']->allergies) ? nl2br(htmlspecialchars($data['medical_info']->allergies)) : '-'; ?></span></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>


<div class="modal-overlay" id="profile-edit-modal-overlay">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="profile-edit-modal-title">
        <div class="modal-header">
            <h3 id="profile-edit-modal-title">Edit Profile</h3>
            <button type="button" class="modal-close" id="close-profile-edit-modal-btn" aria-label="Close">&times;</button>
        </div>

        <form action="<?php echo URLROOT; ?>/Pages/patientProfile" method="POST" enctype="multipart/form-data" class="modal-form upload-form">
            <input type="hidden" name="form_type" value="update_profile_image">

            <div class="modal-field">
                <label for="profile_image">Profile Image (JPG/PNG)</label>
                <input type="file" id="profile_image" name="profile_image" class="profile-input" accept="image/jpeg,image/jpg,image/png" required>
            </div>

            <div class="modal-actions">
                <button type="submit" class="action-button secondary">Upload Picture</button>
            </div>
        </form>

        <form action="<?php echo URLROOT; ?>/Pages/patientProfile" method="POST" class="modal-form">
            <input type="hidden" name="form_type" value="update_name">

            <div class="modal-field">
                <label for="patient_user_name">Change Name</label>
                <input
                    type="text"
                    id="patient_user_name"
                    name="user_name"
                    value="<?php echo htmlspecialchars($data['user_name']); ?>"
                    class="profile-input <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>"
                    required
                >
            </div>

            <div class="modal-actions">
                <button type="submit" class="action-button primary">Save Name</button>
            </div>
        </form>

        <form action="<?php echo URLROOT; ?>/Pages/patientProfile" method="POST" class="modal-form" id="medical-info-form">
            <input type="hidden" name="form_type" value="update_medical">

            <div class="modal-field-row">
                <div class="modal-field">
                    <label for="blood_type">Blood Type</label>
                    <select id="blood_type" name="blood_type" class="profile-input">
                        <?php $bloodType = $data['medical_form']['blood_type'] ?? ''; ?>
                        <option value="">Select blood type</option>
                        <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo $bloodType === $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="modal-field">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="profile-input" value="<?php echo htmlspecialchars($data['medical_form']['date_of_birth'] ?? ''); ?>">
                </div>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label for="emergency_contact">Emergency Contact</label>
                    <input type="text" id="emergency_contact" name="emergency_contact" class="profile-input" value="<?php echo htmlspecialchars($data['medical_form']['emergency_contact'] ?? ''); ?>" placeholder="0771234567">
                </div>

                <div class="modal-field">
                    <label for="insurance_provider">Insurance Provider</label>
                    <input type="text" id="insurance_provider" name="insurance_provider" class="profile-input" value="<?php echo htmlspecialchars($data['medical_form']['insurance_provider'] ?? ''); ?>" placeholder="Company name">
                </div>
            </div>

            <div class="modal-field">
                <label for="allergies">Allergies</label>
                <textarea id="allergies" name="allergies" class="profile-input textarea-input" rows="3" placeholder="Enter allergies if any"><?php echo htmlspecialchars($data['medical_form']['allergies'] ?? ''); ?></textarea>
            </div>

            <div class="modal-actions">
                <button type="submit" class="action-button primary">Save Medical Info</button>
                <button type="button" class="action-button" id="cancel-profile-edit-modal-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const openButton = document.getElementById('open-profile-edit-modal-btn');
    const closeButton = document.getElementById('close-profile-edit-modal-btn');
    const cancelButton = document.getElementById('cancel-profile-edit-modal-btn');
    const modal = document.getElementById('profile-edit-modal-overlay');

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
<?php 
    $current_page = 'adminSettings';
    require APPROOT.'/views/inc/header.php'; 
?>

<style>
/* Override button width for settings page */
.settings-grid .action-button {
    width: auto;
    min-width: 120px;
}
</style>




<div class="dashboard-container patient">
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

            <<!-- Account Settings Section -->
        <div class="content-section account-settings">
            <div class="section-header">
                <h2 class="section-title">Account Settings</h2>
            </div>
            <div class="section-content">
                <div class="settings-grid">
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Change Password</h4>
                            <p>Update your account password for security</p>
                        </div>
                        <button class="action-button secondary">Change</button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Email Notifications</h4>
                            <p>Manage your email notification preferences</p>
                        </div>
                        <button class="action-button secondary">Configure</button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Privacy Settings</h4>
                            <p>Control your profile visibility and data sharing</p>
                        </div>
                        <button class="action-button secondary">Manage</button>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Account Deactivation</h4>
                            <p>Temporarily deactivate your account</p>
                        </div>
                        <button class="action-button danger">Deactivate</button>
                    </div>
                </div>
            </div>
        </div>
        </main>
    </div>



<?php require APPROOT.'/views/inc/footer.php'; ?>
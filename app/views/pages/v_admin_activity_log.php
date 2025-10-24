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
            <!-- Back Navigation -->
            <div class="back-navigation">
                <a href="<?php echo URLROOT; ?>/Pages/adminProfile" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Activity Log Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Admin Activity Log</h2>
                    </div>
                    <div class="section-content">
                        <?php $activityLog = $data['activityLog'] ?? []; ?>
                        
                        <?php if (!empty($activityLog)): ?>
                            <?php foreach ($activityLog as $activity): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($activity->action); ?></div>
                                        <div class="appointment-date">
                                            Details: <?php echo htmlspecialchars($activity->details); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Timestamp: <?php echo htmlspecialchars($activity->timestamp); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge confirmed"><?php echo ucfirst($activity->status); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No activity found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

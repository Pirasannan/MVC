<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminActiveSessions';
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
                <!-- Active Sessions Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Active Sessions</h2>
                    </div>
                    <div class="section-content">
                        <?php $activeSessions = $data['activeSessions'] ?? []; ?>
                        
                        <?php if (!empty($activeSessions)): ?>
                            <?php foreach ($activeSessions as $session): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($session->device); ?></div>
                                        <div class="appointment-date">
                                            IP Address: <?php echo htmlspecialchars($session->ip_address); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Last Activity: <?php echo htmlspecialchars($session->last_activity); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <?php if ($session->is_current): ?>
                                            <span class="status-badge confirmed">Current Session</span>
                                        <?php else: ?>
                                            <span class="status-badge pending">Active</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No active sessions found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminSystemActivityLog';
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
                <a href="<?php echo URLROOT; ?>/Pages/adminDashboard" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- System Activity Log Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">System Activity Log</h2>
                    </div>
                    <div class="section-content">
                        <?php $systemActivity = $data['systemActivity'] ?? []; ?>
                        
                        <?php if (!empty($systemActivity)): ?>
                            <?php foreach ($systemActivity as $activity): ?>
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
                                        <?php 
                                        $statusClass = 'pending';
                                        if ($activity->status === 'completed') $statusClass = 'confirmed';
                                        if ($activity->status === 'under_review') $statusClass = 'scheduled';
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($activity->status); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No system activity found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

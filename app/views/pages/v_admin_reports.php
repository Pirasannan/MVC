<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminReports';
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
                <a href="<?php echo URLROOT; ?>/Pages/adminNotifications" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Notifications
                </a>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Reported Messages Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">All Reported Messages & Complaints</h2>
                    </div>
                    <div class="section-content">
                        <?php $reportedMessages = $data['reportedMessages'] ?? []; ?>
                        
                        <?php if (!empty($reportedMessages)): ?>
                            <?php foreach ($reportedMessages as $report): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($report->report_type ?? 'Report'); ?></div>
                                        <div class="appointment-date">
                                            Reported by: <?php echo htmlspecialchars($report->reporter_name ?? 'Unknown'); ?> (<?php echo ucfirst($report->reporter_type ?? 'user'); ?>)
                                        </div>
                                        <div class="prescribed-by">
                                            Against: <?php echo htmlspecialchars($report->reported_name ?? 'Unknown'); ?> | 
                                            Reason: <?php echo htmlspecialchars($report->reason ?? 'No reason provided'); ?>
                                        </div>
                                        <div class="appointment-date">
                                            Reported: <?php echo htmlspecialchars($report->created_at ?? ''); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending"><?php echo ucfirst($report->status ?? 'pending'); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No reported messages found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

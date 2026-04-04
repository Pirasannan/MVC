<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminResolvedReports';
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
                <!-- Resolved Reports Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Resolved Complaints & Reports</h2>
                    </div>
                    <div class="section-content">
                        <?php $resolvedReports = $data['resolvedReports'] ?? []; ?>
                        
                        <?php if (!empty($resolvedReports)): ?>
                            <?php foreach ($resolvedReports as $report): ?>
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
                                            Resolved: <?php echo htmlspecialchars($report->resolved_at ?? ''); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Resolution: <?php echo htmlspecialchars($report->resolution ?? 'No resolution details'); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge confirmed">Resolved</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No resolved reports found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

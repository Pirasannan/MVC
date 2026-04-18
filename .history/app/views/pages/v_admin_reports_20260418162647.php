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
            <?php if (!empty($data['flash'])): ?>
                <div class="back-navigation">
                    <p style="margin:0;color:#0f766e;font-weight:600;"><?php echo htmlspecialchars($data['flash']); ?></p>
                </div>
            <?php endif; ?>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Call Reports Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Call Reports</h2>
                    </div>
                    <div class="section-content">
                        <?php $callReports = $data['callReports'] ?? []; ?>
                        
                        <?php if (!empty($callReports)): ?>
                            <?php foreach ($callReports as $report): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name">Call Report</div>
                                        <div class="appointment-date">
                                            Reported by: <?php echo htmlspecialchars($report->reporter_name ?? 'Unknown'); ?> (<?php echo ucfirst($report->reporter_type ?? 'user'); ?>)
                                        </div>
                                        <div class="prescribed-by">
                                            Against: <?php echo htmlspecialchars($report->reported_name ?? 'System'); ?> | 
                                            Reason: <?php echo htmlspecialchars($report->reason ?? 'No reason provided'); ?>
                                        </div>
                                        <?php if (!empty($report->description)): ?>
                                            <div class="appointment-date">Details: <?php echo htmlspecialchars($report->description); ?></div>
                                        <?php endif; ?>
                                        <div class="appointment-date">
                                            Reported: <?php echo htmlspecialchars($report->created_at ?? ''); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending"><?php echo ucfirst($report->status ?? 'pending'); ?></span>
                                        <form method="post" action="<?php echo URLROOT; ?>/Pages/resolveReport/<?php echo (int)$report->id; ?>" onsubmit="return askResolution(this);" style="margin-top:10px;">
                                            <input type="hidden" name="resolution" value="">
                                            <button type="submit" class="action-button secondary">Mark Resolved</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No pending call reports.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- User Reports Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">User Reports</h2>
                    </div>
                    <div class="section-content">
                        <?php $userReports = $data['userReports'] ?? []; ?>
                        
                        <?php if (!empty($userReports)): ?>
                            <?php foreach ($userReports as $report): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name">User Report</div>
                                        <div class="appointment-date">
                                            Reported by: <?php echo htmlspecialchars($report->reporter_name ?? 'Unknown'); ?> (<?php echo ucfirst($report->reporter_type ?? 'user'); ?>)
                                        </div>
                                        <div class="prescribed-by">
                                            Against: <?php echo htmlspecialchars($report->reported_name ?? 'Unknown'); ?> | 
                                            Reason: <?php echo htmlspecialchars($report->reason ?? 'No reason provided'); ?>
                                        </div>
                                        <?php if (!empty($report->description)): ?>
                                            <div class="appointment-date">Details: <?php echo htmlspecialchars($report->description); ?></div>
                                        <?php endif; ?>
                                        <div class="appointment-date">
                                            Reported: <?php echo htmlspecialchars($report->created_at ?? ''); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending"><?php echo ucfirst($report->status ?? 'pending'); ?></span>
                                        <form method="post" action="<?php echo URLROOT; ?>/Pages/resolveReport/<?php echo (int)$report->id; ?>" onsubmit="return askResolution(this);" style="margin-top:10px;">
                                            <input type="hidden" name="resolution" value="">
                                            <button type="submit" class="action-button secondary">Mark Resolved</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No pending user reports.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Resolved Reports Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Resolved Reports</h2>
                    </div>
                    <div class="section-content">
                        <?php $resolvedReports = $data['resolvedReports'] ?? []; ?>
                        
                        <?php if (!empty($resolvedReports)): ?>
                            <?php foreach ($resolvedReports as $report): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($report->report_type ?? 'Report'); ?></div>
                                        <div class="appointment-date">
                                            Reported by: <?php echo htmlspecialchars($report->reporter_name ?? 'Unknown'); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Against: <?php echo htmlspecialchars($report->reported_name ?? 'System'); ?> | 
                                            Reason: <?php echo htmlspecialchars($report->reason ?? 'No reason provided'); ?>
                                        </div>
                                        <div class="appointment-date">
                                            Resolution: <?php echo htmlspecialchars($report->resolution ?? 'No resolution details'); ?>
                                        </div>
                                        <div class="appointment-date">
                                            Resolved by: <?php echo htmlspecialchars($report->resolver_name ?? 'Admin'); ?> | 
                                            Resolved at: <?php echo htmlspecialchars($report->resolved_at ?? ''); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge confirmed">Resolved</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No resolved reports yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function askResolution(form) {
    const resolution = prompt('Add a resolution note for this report:');
    if (!resolution || !resolution.trim()) {
        return false;
    }

    form.querySelector('input[name="resolution"]').value = resolution.trim();
    return true;
}
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

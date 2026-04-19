<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminLoginLogs';
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

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Login Logs Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Login Logs</h2>
                    </div>
                    <div class="section-content">
                        <?php $loginLogs = $data['loginLogs'] ?? []; ?>

                        <?php if (!empty($loginLogs)): ?>
                            <?php foreach ($loginLogs as $log): ?>
                                <?php
                                    $statusClass = $log->action === 'login_success' ? 'confirmed' : 'rejected';
                                    $statusText = $log->action === 'login_success' ? 'Success' : 'Failed';
                                    $userName = $log->user_name ?? 'Unknown';
                                    $userEmail = $log->user_email ?? 'unknown';
                                    $userRole = $log->user_role ?? 'unknown';
                                ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name">Login <?php echo htmlspecialchars($statusText); ?></div>
                                        <div class="appointment-date">
                                            User: <?php echo htmlspecialchars($userName); ?> (<?php echo htmlspecialchars($userEmail); ?>)
                                        </div>
                                        <div class="appointment-date">
                                            Role: <?php echo htmlspecialchars($userRole); ?>
                                        </div>
                                        <div class="appointment-date">
                                            IP: <?php echo htmlspecialchars($log->ip_address ?? 'unknown'); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Agent: <?php echo htmlspecialchars($log->user_agent ?? 'unknown'); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Timestamp: <?php echo htmlspecialchars($log->created_at ?? ''); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No login activity found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

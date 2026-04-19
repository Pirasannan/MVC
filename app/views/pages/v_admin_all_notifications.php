<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminAllNotifications';
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
                    Back to Notifications
                </a>
            </div>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- All Notifications Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">All System Notifications</h2>
                    </div>
                    <div class="section-content">
                        <?php $allNotifications = $data['allNotifications'] ?? []; ?>
                        
                        <?php if (!empty($allNotifications)): ?>
                            <?php foreach ($allNotifications as $notification): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($notification->title); ?></div>
                                        <div class="appointment-date">
                                            Recipients: <?php echo ucfirst($notification->recipient_type); ?><?php echo $notification->recipient_id ? ' (ID: ' . $notification->recipient_id . ')' : ''; ?>
                                        </div>
                                        <div class="prescribed-by">Message: <?php echo htmlspecialchars($notification->message); ?></div>
                                        <div class="appointment-date">
                                            Sent: <?php echo htmlspecialchars($notification->created_at); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge confirmed"><?php echo ucfirst($notification->status ?? 'sent'); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No notifications found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

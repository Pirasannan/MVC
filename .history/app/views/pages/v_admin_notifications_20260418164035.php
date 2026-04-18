<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'adminNotifications';
?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT . '/views/inc/components/adminHeader.php'; ?>


        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Send System Notification Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Send System-Wide Notification</h2>
                    </div>
                    
                    <?php if (isset($_GET['sent']) && $_GET['sent'] == '1'): ?>
                        <div style="padding: 12px; margin-bottom: 16px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                            ✓ Notification sent successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div style="padding: 12px; margin-bottom: 16px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                            ✗ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form id="sysNotification" method="post" action="<?= URLROOT ?>/Pages/sendNotification">
                        <div class="p-form" style="border-top: none;">
                            <div class="form-grid" style="grid-template-columns: 1fr;">
                                <div class="field">
                                    <label for="userType" class="label">Notification Type</label>
                                    <select id="userType" name="recipient_type" class="input" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="all">All Users</option>
                                        <option value="doctor">Doctors</option>
                                        <option value="patient">Patients</option>
                                        <option value="admin">Admins</option>
                                    </select>
                                </div>

                                <div class="field">
                                    <label for="title" class="label">Notification Title</label>
                                    <input type="text" class="input" id="title" name="title" placeholder="Enter notification title" required>
                                </div>

                                <div class="field">
                                    <label for="message" class="label">Message Content</label>
                                    <textarea class="input" id="message" name="message" placeholder="Enter notification message" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="submit" class="action-button">Send Notification</button>
                        </div>
                    </form>
                </div>

                <!-- Reported Messages/Complaints Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Reported Messages & Complaints</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Inappropriate Content Report</div>
                                <div class="medication-details">Reported by: Anura Bandara (Patient)</div>
                                <div class="prescribed-by">Against: Dr. Kasun Silva | Reason: Unprofessional language</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Spam Messages Report</div>
                                <div class="medication-details">Reported by: Dr. Sarah Johnson (Doctor)</div>
                                <div class="prescribed-by">Against: Yasmin Fonseka | Reason: Repeated spam messages</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Harassment Complaint</div>
                                <div class="medication-details">Reported by: Nethmi Silva (Patient)</div>
                                <div class="prescribed-by">Against: Dr. Anil Rodrigo | Reason: Inappropriate consultation behavior</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">Under Review</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Fraudulent Activity Report</div>
                                <div class="medication-details">Reported by: Dr. Michael Chen (Doctor)</div>
                                <div class="prescribed-by">Against: Chaminda Gunasekara | Reason: Fake medical documents</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminReports">
                            <button class="action-button">Review All Reports</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Recent System Notifications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent System Notifications</h2>
                    </div>
                    <div class="section-content">
                        <?php $recentNotifications = $data['recentNotifications'] ?? []; ?>

                        <?php if (!empty($recentNotifications)): ?>
                            <?php foreach ($recentNotifications as $notification): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($notification->title); ?></div>
                                        <div class="appointment-date">Recipients: <?php echo ucfirst($notification->recipient_type); ?><?php echo $notification->recipient_id ? ' (ID: ' . $notification->recipient_id . ')' : ''; ?></div>
                                        <div class="prescribed-by">Message: <?php echo htmlspecialchars($notification->message); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge confirmed">Sent</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No notifications sent yet.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminAllNotifications">
                            <button class="action-button secondary">View All Notifications</button>
                        </a>
                    </div>
                </div>

                <!-- Resolved Complaints Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Resolved Complaints</h2>
                    </div>
                    <div class="section-content">
                        <?php $resolvedReports = $data['resolvedReports'] ?? []; ?>
                        <?php if (!empty($resolvedReports)): ?>
                            <?php foreach (array_slice($resolvedReports, 0, 3) as $report): ?>
                                <div class="medication-item">
                                    <div class="medication-info">
                                        <div class="medication-name"><?php echo htmlspecialchars($report->report_type ?? 'Report'); ?></div>
                                        <div class="medication-details">
                                            Reported by: <?php echo htmlspecialchars($report->reporter_name ?? 'Unknown'); ?>
                                        </div>
                                        <div class="prescribed-by">
                                            Against: <?php echo htmlspecialchars($report->reported_name ?? 'System'); ?> | 
                                            Resolution: <?php echo htmlspecialchars($report->resolution ?? 'No resolution details'); ?>
                                        </div>
                                    </div>
                                    <div class="medication-date"><?php echo htmlspecialchars(substr((string)($report->resolved_at ?? ''), 0, 10)); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No resolved reports yet.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminResolvedReports">
                            <button class="action-button secondary">View Resolution History</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
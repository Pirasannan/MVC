<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminNotifications';
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
            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Send System Notification Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Send System-Wide Notification</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Notification Type</div>
                                <div class="appointment-date">
                                    <select id="recipientType" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                        <option value="all">All Users</option>
                                        <option value="doctor">All Doctors</option>
                                        <option value="patient">All Patients</option>
                                        <option value="admin">All Admins</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Notification Title</div>
                                <div class="appointment-date">
                                    <input type="text" id="notificationTitle" placeholder="Enter notification title" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Message Content</div>
                                <div class="appointment-date">
                                    <textarea id="notificationMessage" placeholder="Enter notification message" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px; min-height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button id="sendNotificationBtn" class="action-button">Send Notification</button>
                    </div>
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
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Delayed Consultation Complaint</div>
                                <div class="medication-details">Reported by: Roshan Fernando (Patient)</div>
                                <div class="prescribed-by">Against: Dr. Emily Davis | Resolution: Doctor warned, no-show policy updated</div>
                            </div>
                            <div class="medication-date">2025-10-15</div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Payment Dispute</div>
                                <div class="medication-details">Reported by: Dr. Sunil Jayawardena (Doctor)</div>
                                <div class="prescribed-by">Against: Kumar Dissanayake | Resolution: Payment refunded, account suspended</div>
                            </div>
                            <div class="medication-date">2025-10-12</div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Technical Issue Report</div>
                                <div class="medication-details">Reported by: Dilani Perera (Patient)</div>
                                <div class="prescribed-by">Against: System | Resolution: Video call connectivity issue fixed</div>
                            </div>
                            <div class="medication-date">2025-10-10</div>
                        </div>
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

<?php require APPROOT.'/views/inc/footer.php'; ?>
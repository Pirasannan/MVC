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
                                    <select style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                        <option>Select recipient group</option>
                                        <option>All Users</option>
                                        <option>All Doctors</option>
                                        <option>All Patients</option>
                                        <option>All Clinics</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Notification Title</div>
                                <div class="appointment-date">
                                    <input type="text" placeholder="Enter notification title" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                </div>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Message Content</div>
                                <div class="appointment-date">
                                    <textarea placeholder="Enter notification message" style="width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #e0e0e0; border-radius: 4px; min-height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Send Notification</button>
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
                        <button class="action-button">Review All Reports</button>
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
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Scheduled Maintenance Notice</div>
                                <div class="appointment-date">Recipients: All Users</div>
                                <div class="prescribed-by">Message: System will be under maintenance on Oct 20, 2025 from 2:00 AM - 4:00 AM</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Sent</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">New Feature Announcement</div>
                                <div class="appointment-date">Recipients: All Doctors</div>
                                <div class="prescribed-by">Message: New prescription template feature now available in your dashboard</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Sent</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Security Update Alert</div>
                                <div class="appointment-date">Recipients: All Users</div>
                                <div class="prescribed-by">Message: Please update your password for enhanced security</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Sent</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button secondary">View All Notifications</button>
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
                        <button class="action-button secondary">View Resolution History</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
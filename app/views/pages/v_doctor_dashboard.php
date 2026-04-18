<?php 
    require APPROOT.'/views/inc/header.php';
    $current_page = 'doctorDashboard';
?>




<div class="dashboard-container doctor">        
        <!-- Sidebar Navigation -->        
        <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>
                
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card ">
                        <div class="stat-content">
                            <h3 class="stat-title">Today's Appointments</h3>
                            <div class="stat-number">5</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Prescribed Patients</h3>
                            <div class="stat-number">12</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number">3</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Pending Prescriptions</h3>
                            <div class="stat-number">2</div>
                        </div>
                    </div>
                </div>

                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Appointments Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Upcoming Appointments</h2>
                        </div>
                        <div class="section-content">
                            <?php if (empty($data['upcoming_appointments'])): ?>
                                <div class="p-empty" style="padding: 20px; text-align: center; color: #6b7280;">No upcoming appointments.</div>
                            <?php else: ?>
                                <?php foreach ($data['upcoming_appointments'] as $a): ?>
                                    <div class="appointment-item">
                                        <div class="appointment-info">
                                            <div class="patient-name"><?php echo htmlspecialchars($a->patient_name); ?></div>
                                            <div class="appointment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($a->starts_at)); ?></div>
                                            <div class="appointment-type"><?php echo htmlspecialchars($a->reason ?? 'Consultation'); ?></div>
                                        </div>
                                        <div class="appointment-status-actions" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                                            <?php
                                                $st = strtolower($a->status);
                                                $cls = in_array($st, ['approved', 'pending', 'rejected', 'cancelled', 'completed']) ? $st : 'pending';
                                                
                                                // Map status for badge
                                                $badgeClass = $cls;
                                                if ($cls === 'approved') $badgeClass = 'confirmed';
                                                if ($cls === 'completed') $badgeClass = 'confirmed'; // Reuse green for completed
                                            ?>
                                            <span class="status-badge <?php echo $badgeClass; ?>"><?php echo ucfirst($st); ?></span>
                                            
                                            
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- System Notifications Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Notifications</h2>
                        </div>
                        <div class="section-content">
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">New Patient Registration</div>
                                    <div class="notification-message">A new patient has registered and requires verification</div>
                                    <div class="notification-time">2 hours ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge pending">New</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">System Update</div>
                                    <div class="notification-message">New features added to prescription management</div>
                                    <div class="notification-time">3 days ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge confirmed">Read</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <div class="notification-title">Maintenance Notice</div>
                                    <div class="notification-message">Scheduled maintenance tonight from 2-4 AM</div>
                                    <div class="notification-time">5 days ago</div>
                                </div>
                                <div class="notification-status">
                                    <span class="status-badge confirmed">Read</span>
                                </div>
                            </div>
                        </div>
                        <div class="section-footer">
                            <button class="action-button secondary">View All Notifications</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

        <link rel="stylesheet" href="<?= URLROOT ?>/public/css/components/reschedule-modal.css">

        <!-- Reschedule Modal -->
        <div id="resModal" class="res-modal" style="display:none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
            <div class="res-modal__content" style="background-color: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                <h3 class="res-modal__title" style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #1f2937;">Propose a new time</h3>

                <form id="resForm" method="POST" action="">
                    <div class="res-modal__row" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #4b5563;">New date & time</label>
                        <input type="datetime-local" name="new_datetime" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div class="res-modal__row" style="margin-bottom: 24px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; color: #4b5563;">Note to patient (optional)</label>
                        <input type="text" name="message" placeholder="e.g., I have a ward round..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div class="res-modal__actions" style="display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="submit" class="btn btn-warning" style="background: #f59e0b; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 500;">Send Proposal</button>
                        <button type="button" class="btn btn-light" onclick="closeResModal()" style="background: #f3f4f6; color: #4b5563; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="<?= URLROOT ?>/public/js/modal-manager.js"></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
<?php
require APPROOT . '/views/inc/header.php';
$current_page = 'patientDashboard';
?>




<div class="dashboard-container patient">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT . '/views/inc/components/patientHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Cards Row -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Today's Appointments</h3>
                        <div class="stat-number"><?php echo $data['todays_appointments_count']; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Unread Messages</h3>
                        <div class="stat-number">2</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Recent Prescriptions</h3>
                        <div class="stat-number"><?php echo $data['recent_prescriptions_count']; ?></div>
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
                            <div class="p-empty" style="padding: 20px; text-align: center; color: #6b7280;">No upcoming
                                appointments.</div>
                        <?php else: ?>
                            <?php foreach ($data['upcoming_appointments'] as $appointment): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($appointment->doctor_name); ?>
                                        </div>
                                        <div class="appointment-date">
                                            <?php echo date('M j, Y \a\t g:i A', strtotime($appointment->starts_at)); ?></div>
                                        <div class="appointment-type">
                                            <?php echo htmlspecialchars($appointment->reason ?? 'Consultation'); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <?php
                                        $st = strtolower($appointment->status);
                                        $rescheduleStatus = $appointment->reschedule_status ?? 'none';
                                        $proposedTime = $appointment->proposed_datetime ?? null;

                                        $badgeClass = '';
                                        switch ($st) {
                                            case 'approved':
                                                $badgeClass = 'confirmed';
                                                $displayText = 'Confirmed';
                                                break;
                                            case 'pending':
                                                $badgeClass = 'pending';
                                                $displayText = 'Pending';
                                                break;
                                            case 'rejected':
                                                $badgeClass = 'rejected';
                                                $displayText = 'Rejected';
                                                break;
                                            case 'cancelled':
                                                $badgeClass = 'rejected';
                                                $displayText = 'Cancelled';
                                                break;
                                            default:
                                                $badgeClass = 'scheduled';
                                                $displayText = ucfirst($st);
                                                break;
                                        }
                                        ?>
                                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                                            <span
                                                class="status-badge <?php echo $badgeClass; ?>"><?php echo $displayText; ?></span>

                                            <?php if ($rescheduleStatus === 'pending_patient' && $proposedTime): ?>
                                            <?php elseif ($st === 'approved'): ?>
                                                <a href="<?= URLROOT ?>/VideoCall/precall/<?= $appointment->id ?>"
                                                    class="join-consultation-btn"
                                                    style="background: #2563eb; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-video"></i> Join
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/patientBookAppointment"><button
                                class="action-button primary">Book New Appointment</button></a>
                    </div>
                </div>

                <!-- System Notifications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">System Notifications</h2>
                    </div>
                    <div class="section-content">
                        <div class="notification-item">
                            <div class="notification-info">
                                <div class="notification-title">New Prescription Available</div>
                                <div class="notification-message">Dr. John Smith has prescribed new medication for your
                                    condition</div>
                                <div class="notification-time">2 hours ago</div>
                            </div>
                            <div class="notification-status">
                                <span class="status-badge new">New</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-info">
                                <div class="notification-title">Appointment Reminder</div>
                                <div class="notification-message">Your appointment with Dr. Sarah Johnson is tomorrow at
                                    10:00 AM</div>
                                <div class="notification-time">1 day ago</div>
                            </div>
                            <div class="notification-status">
                                <span class="status-badge read">Read</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-info">
                                <div class="notification-title">Lab Results Ready</div>
                                <div class="notification-message">Your recent blood test results are now available for
                                    review</div>
                                <div class="notification-time">3 days ago</div>
                            </div>
                            <div class="notification-status">
                                <span class="status-badge read">Read</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-info">
                                <div class="notification-title">Prescription Refill Due</div>
                                <div class="notification-message">Your medication Lisinopril is due for refill in 3 days
                                </div>
                                <div class="notification-time">1 week ago</div>
                            </div>
                            <div class="notification-status">
                                <span class="status-badge read">Read</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button primary">View All Notifications</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
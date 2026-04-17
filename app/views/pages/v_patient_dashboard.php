<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientDashboard';
$dashboard = $data ?? [];
$upcomingAppointments = $dashboard['upcomingAppointments'] ?? [];
$recentPrescriptions = $dashboard['recentPrescriptions'] ?? [];
?>




<div class="dashboard-container patient">        
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Today's Appointments</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['todayAppointmentsCount'] ?? 0)); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Active Medications</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['activeMedicationsCount'] ?? 0)); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['unreadMessagesCount'] ?? 0)); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Recent Prescriptions</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['recentPrescriptionsCount'] ?? 0)); ?></div>
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
                            <?php if (!empty($upcomingAppointments)): ?>
                                <?php foreach ($upcomingAppointments as $appointment): ?>
                                    <?php
                                        $appointmentStartsAt = !empty($appointment->starts_at) ? new DateTimeImmutable($appointment->starts_at) : null;
                                        $appointmentDate = $appointmentStartsAt ? $appointmentStartsAt->format('M j, Y g:i A') : 'TBA';
                                        $appointmentStatus = strtolower((string)($appointment->status ?? 'scheduled'));
                                        $statusClasses = [
                                            'approved' => 'confirmed',
                                            'pending' => 'pending',
                                            'completed' => 'scheduled',
                                        ];
                                        $statusLabels = [
                                            'approved' => 'Confirmed',
                                            'pending' => 'Scheduled',
                                            'completed' => 'Completed',
                                        ];
                                        $statusClass = $statusClasses[$appointmentStatus] ?? 'scheduled';
                                        $statusLabel = $statusLabels[$appointmentStatus] ?? ucfirst($appointmentStatus ?: 'scheduled');
                                        $appointmentType = !empty($appointment->reason) ? $appointment->reason : 'Consultation';
                                    ?>
                                    <div class="appointment-item">
                                        <div class="appointment-info">
                                            <div class="doctor-name"><?php echo htmlspecialchars($appointment->doctor_name ?? 'Doctor'); ?></div>
                                            <div class="appointment-date"><?php echo htmlspecialchars($appointmentDate); ?></div>
                                            <div class="appointment-type"><?php echo htmlspecialchars($appointmentType); ?></div>
                                        </div>
                                        <div class="appointment-status">
                                            <span class="status-badge <?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <p>No upcoming appointments found.</p>
                                    <p style="font-size: 14px; margin-top: 8px;">Your scheduled visits will appear here once they are approved.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="section-footer">
                            <a href="<?php echo URLROOT; ?>/Pages/patientBookAppointment"><button class="action-button primary">Book New Appointment</button></a>
                        </div>
                    </div>

                    <!-- Recent Prescriptions Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Recent Prescriptions</h2>
                        </div>
                        <div class="section-content">
                            <?php if (!empty($recentPrescriptions)): ?>
                                <?php foreach ($recentPrescriptions as $prescription): ?>
                                    <?php
                                        $prescribedDate = !empty($prescription->created_at) ? date('M j, Y', strtotime($prescription->created_at)) : 'Today';
                                        $doseText = trim((string)($prescription->dose_amount ?? '') . ' ' . (string)($prescription->dose_unit ?? ''));
                                        $medicationDetails = trim(($doseText !== '' ? $doseText : 'No dose specified') . ', ' . ($prescription->frequency ?? 'As directed'));
                                    ?>
                                    <div class="medication-item">
                                        <div class="medication-info">
                                            <div class="medication-name"><?php echo htmlspecialchars($prescription->drug_name ?? 'Prescription'); ?></div>
                                            <div class="medication-details"><?php echo htmlspecialchars($medicationDetails); ?></div>
                                            <div class="prescribed-by">Prescribed by Dr. <?php echo htmlspecialchars($prescription->doctor_name ?? 'Unknown'); ?></div>
                                        </div>
                                        <div class="medication-date">
                                            <div class="prescription-date"><?php echo htmlspecialchars($prescribedDate); ?></div>
                                            <?php if (!empty($prescription->valid_until)): ?>
                                                <div class="updated-date">Valid until: <?php echo htmlspecialchars(date('M j, Y', strtotime($prescription->valid_until))); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <p>No recent prescriptions found.</p>
                                    <p style="font-size: 14px; margin-top: 8px;">New prescriptions from your doctor will appear here.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="section-footer">
                            <a href="<?php echo URLROOT; ?>/Pages/patientPrescriptions"><button class="action-button primary">View All Prescriptions</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
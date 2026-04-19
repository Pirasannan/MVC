<?php 
    require APPROOT.'/views/inc/header.php';
    $current_page = 'doctorDashboard';
    $dashboard = $data ?? [];
    $upcomingAppointments = $dashboard['upcomingAppointments'] ?? [];
    $recentPrescriptions = $dashboard['recentPrescriptions'] ?? [];
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
                <div class="stats-row doctor-stats-row">
                    <div class="stat-card ">
                        <div class="stat-content">
                            <h3 class="stat-title">Today's Appointments</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['todayAppointmentsCount'] ?? 0)); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Prescribed Patients</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['prescribedPatientsCount'] ?? 0)); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number"><?php echo htmlspecialchars((string)($dashboard['unreadMessagesCount'] ?? 0)); ?></div>
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
                                <?php foreach (array_slice($upcomingAppointments, 0, 4) as $appointment): ?>
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
                                    ?>
                                    <div class="appointment-item">
                                        <div class="appointment-info">
                                            <div class="patient-name"><?php echo htmlspecialchars($appointment->patient_name ?? 'Patient'); ?></div>
                                            <div class="appointment-date"><?php echo htmlspecialchars($appointmentDate); ?></div>
                                            <div class="appointment-type"><?php echo htmlspecialchars(!empty($appointment->reason) ? $appointment->reason : 'Consultation'); ?></div>
                                        </div>
                                        <div class="appointment-status">
                                            <span class="status-badge <?php echo htmlspecialchars($statusClass); ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <p>No upcoming appointments found.</p>
                                    <p style="font-size: 14px; margin-top: 8px;">Appointments assigned to you will appear here.</p>
                                </div>
                            <?php endif; ?>
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
                                        $createdAt = !empty($prescription->created_at) ? date('M j, Y', strtotime($prescription->created_at)) : 'Today';
                                        $doseText = trim((string)($prescription->dose_amount ?? '') . ' ' . (string)($prescription->dose_unit ?? ''));
                                        $details = trim(($doseText !== '' ? $doseText : 'No dose specified') . ', ' . ($prescription->frequency ?? 'As directed'));
                                    ?>
                                    <div class="medication-item">
                                        <div class="medication-info">
                                            <div class="medication-name"><?php echo htmlspecialchars($prescription->drug_name ?? 'Prescription'); ?></div>
                                            <div class="medication-details"><?php echo htmlspecialchars($details); ?></div>
                                            <div class="prescribed-by">For <?php echo htmlspecialchars($prescription->patient_name ?? 'Patient'); ?></div>
                                        </div>
                                        <div class="medication-date"><?php echo htmlspecialchars($createdAt); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <p>No recent prescriptions found.</p>
                                    <p style="font-size: 14px; margin-top: 8px;">Your issued prescriptions will appear here.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="section-footer">
                            <a href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions"><button class="action-button secondary">View All Prescriptions</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
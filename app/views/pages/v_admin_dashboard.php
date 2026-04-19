<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminDashboard';
?>

<div class="dashboard-container">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Cards Row -->
            <div class="stats-row">
                <div class="stat-card primary">
                    <div class="stat-content">
                        <h3 class="stat-title">Registered Doctors</h3>
                        <div class="stat-number"><?php echo (int)($data['stats']['total_doctors'] ?? 0); ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Registered Patients</h3>
                        <div class="stat-number"><?php echo (int)($data['stats']['total_patients'] ?? 0); ?></div>
                    </div>
                </div>
            </div>

            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Pending Verifications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Pending Verifications</h2>
                    </div>
                    <div class="section-content">
                        <?php $pendingDoctors = $data['pendingDoctors'] ?? []; ?>
                        <?php if (!empty($pendingDoctors)): ?>
                            <?php foreach ($pendingDoctors as $doctor): ?>
                                <?php
                                    $doctorName = $doctor->user_name ?? $doctor->name ?? '';
                                    $doctorEmail = $doctor->user_email ?? $doctor->email ?? '';
                                    $submittedAt = $doctor->uploaded_at ?? $doctor->created_at ?? '';
                                    $hasDocument = !empty($doctor->photo_path);
                                ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($doctorName); ?></div>
                                        <div class="appointment-date">Email: <?php echo htmlspecialchars($doctorEmail); ?></div>
                                        <div class="prescribed-by">Submitted: <?php echo htmlspecialchars($submittedAt); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending"><?php echo $hasDocument ? 'Document Uploaded' : 'Pending Review'; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No pending verifications.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminDoctorVerification"><button class="action-button">Review Documents</button></a>
                    </div>
                </div>
            </div>

            <!-- Login Logs Row -->
            <div class="content-sections">
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Recent Login Activity</h2>
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
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminLoginLogs"><button class="action-button secondary">View All Login Logs</button></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
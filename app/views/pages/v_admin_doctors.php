<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminDoctors';
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

                <!-- Verified Doctors Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Verified Doctors</h2>
                    </div>
                    <div class="section-content">
                        <?php $verifiedDoctors = $data['verifiedDoctors'] ?? []; ?>
                        <?php if (!empty($verifiedDoctors)): ?>
                            <?php foreach ($verifiedDoctors as $doctor): ?>
                                <div class="medication-item">
                                    <div class="medication-info">
                                        <div class="medication-name"><?php echo htmlspecialchars($doctor->name ?? ''); ?></div>
                                        <div class="medication-details">Role: Doctor</div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($doctor->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="medication-date">
                                        <span class="status-badge confirmed">Active</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No verified doctors.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                    <a href="<?php echo URLROOT; ?>/Pages/adminAllDoctors"><button class="action-button">View All Doctors</button></a>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Rejected Applications Section -->
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Applications</h2>
                    </div>
                    <div class="section-content">
                        <?php $rejectedDoctors = $data['rejectedDoctors'] ?? []; ?>
                        <?php if (!empty($rejectedDoctors)): ?>
                            <?php foreach ($rejectedDoctors as $doctor): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($doctor->name ?? ''); ?></div>
                                        <div class="appointment-date">Updated: <?php echo htmlspecialchars($doctor->updated_at ?? ''); ?></div>
                                        <div class="prescribed-by">Status: Suspended</div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge rejected">Rejected</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No rejected applications.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminRejectedDoctors"><button class="action-button">View Rejection Details</button></a>                    
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
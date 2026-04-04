<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminPatients';
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
                        <?php $pendingPatients = $data['pendingPatients'] ?? []; ?>
                        <?php if (!empty($pendingPatients)): ?>
                            <?php foreach ($pendingPatients as $patient): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($patient->name ?? ''); ?></div>
                                        <div class="appointment-date">Email: <?php echo htmlspecialchars($patient->email ?? ''); ?></div>
                                        <div class="prescribed-by">Joined: <?php echo htmlspecialchars($patient->created_at ?? ''); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending">Pending Review</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No pending patient verifications.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminPatientVerification"><button class="action-button">Review Documents</button></a>
                    </div>
                </div>

                <!-- Verified Patients Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Verified Patients</h2>
                    </div>
                    <div class="section-content">
                        <?php $verifiedPatients = $data['verifiedPatients'] ?? []; ?>
                        <?php if (!empty($verifiedPatients)): ?>
                            <?php foreach ($verifiedPatients as $patient): ?>
                                <div class="medication-item">
                                    <div class="medication-info">
                                        <div class="medication-name"><?php echo htmlspecialchars($patient->name ?? ''); ?></div>
                                        <div class="medication-details">Email: <?php echo htmlspecialchars($patient->email ?? ''); ?></div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($patient->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="medication-date">
                                        <span class="status-badge confirmed">Active</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No verified patients.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminAllPatients"><button class="action-button">View All Patients</button></a>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Rejected Applications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Applications</h2>
                    </div>
                    <div class="section-content">
                        <?php $rejectedPatients = $data['rejectedPatients'] ?? []; ?>
                        <?php if (!empty($rejectedPatients)): ?>
                            <?php foreach ($rejectedPatients as $patient): ?>
                                <div class="appointment-item">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($patient->name ?? ''); ?></div>
                                        <div class="appointment-date">Email: <?php echo htmlspecialchars($patient->email ?? ''); ?></div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($patient->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge rejected">Rejected</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No rejected patient applications.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminRejectedPatients"><button class="action-button">View Rejection Details</button></a>
                    </div>
                </div>

                <!-- Inactive/Suspended Patients Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Inactive/Suspended Accounts</h2>
                    </div>
                    <div class="section-content">
                        <?php $inactivePatients = $data['inactivePatients'] ?? []; ?>
                        <?php if (!empty($inactivePatients)): ?>
                            <?php foreach ($inactivePatients as $patient): ?>
                                <div class="medication-item">
                                    <div class="medication-info">
                                        <div class="medication-name"><?php echo htmlspecialchars($patient->name ?? ''); ?></div>
                                        <div class="medication-details">Email: <?php echo htmlspecialchars($patient->email ?? ''); ?></div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($patient->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="medication-date">
                                        <span class="status-badge <?php echo $patient->status === 'suspended' ? 'scheduled' : 'pending'; ?>"><?php echo ucfirst($patient->status ?? 'inactive'); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No inactive or suspended patients.</p>
                        <?php endif; ?>
                    </div>
                    <div class="section-footer">
                        <a href="<?php echo URLROOT; ?>/Pages/adminInactivePatients"><button class="action-button">Manage Accounts</button></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminDoctors';
?>

<div class="dashboard-container">
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <main class="main-content">
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

        <div class="dashboard-content">
            <!-- Back Navigation -->
            <div style="margin-bottom: 20px;">
                <a href="<?php echo URLROOT; ?>/Pages/adminDoctors" class="action-button secondary" style="width: auto; display: inline-flex; align-items: center; gap: 8px;">
                    ← Back to Doctor Management
                </a>
            </div>
            
            <div class="content-sections">
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Doctor Applications</h2>
                    </div>

                    <div class="section-content">
                        <?php
                            $rejectedDoctors = $data['rejectedDoctors'] ?? []; 
                            $inactiveDoctors = $data['inactiveDoctors'] ?? [];
                            $doctorApplicationsMap = [];

                            $tagDoctor = function ($doctor, $status) use (&$doctorApplicationsMap) {
                                $doctorKey = $doctor->id ?? $doctor->email ?? null;
                                if ($doctorKey === null) {
                                    return;
                                }

                                $existing = $doctorApplicationsMap[$doctorKey] ?? null;
                                if ($existing && $existing->__application_status === 'inactive' && $status !== 'inactive') {
                                    return;
                                }

                                $doctor->__application_status = $status;
                                $doctorApplicationsMap[$doctorKey] = $doctor;
                            };

                            foreach ($rejectedDoctors as $doctor) {
                                $tagDoctor($doctor, 'suspended');
                            }

                            foreach ($inactiveDoctors as $doctor) {
                                $tagDoctor($doctor, 'inactive');
                            }

                            $doctorApplications = array_values($doctorApplicationsMap);
                        ?>
                        <?php if (!empty($doctorApplications)): ?>
                            <?php foreach ($doctorApplications as $doctor): ?>
                                <?php
                                    $applicationStatus = $doctor->__application_status ?? 'suspended';
                                    $statusLabel = $applicationStatus === 'inactive' ? 'Deactivated' : 'Rejected';
                                    $statusText = $applicationStatus === 'inactive' ? 'Inactive' : 'Suspended';
                                ?>
                                <div class="appointment-item" data-doctor-id="<?php echo htmlspecialchars($doctor->id ?? ''); ?>" data-doctor-name="<?php echo htmlspecialchars($doctor->name ?? ''); ?>" data-doctor-email="<?php echo htmlspecialchars($doctor->email ?? ''); ?>" data-doctor-created="<?php echo htmlspecialchars($doctor->updated_at ?? ''); ?>" data-doctor-status="<?php echo htmlspecialchars($applicationStatus); ?>">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($doctor->name ?? ''); ?></div>
                                        <div class="appointment-date">
                                            Email: <?php echo htmlspecialchars($doctor->email ?? ''); ?>
                                        </div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($doctor->updated_at ?? ''); ?></div>
                                        <div class="prescribed-by">Status: <?php echo htmlspecialchars($statusText); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge rejected"><?php echo htmlspecialchars($statusLabel); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No rejected doctor applications found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Doctor Details Modal -->
<div id="doctorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Rejected Doctor Details</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="doctor-details">
                <div class="detail-row">
                    <label>Name:</label>
                    <span id="modal-doctor-name"></span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span id="modal-doctor-email"></span>
                </div>
                <div class="detail-row">
                    <label>Updated:</label>
                    <span id="modal-doctor-created"></span>
                </div>
                <div class="detail-row">
                    <label>Status:</label>
                    <span id="modal-doctor-status" class="status-badge rejected">Rejected</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="approveBtn" class="action-button">Approve</button>
            <button id="reactivateBtn" class="action-button secondary">Reactivate</button>
        </div>
    </div>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

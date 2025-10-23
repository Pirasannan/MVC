<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminPatients';
?>

<div class="dashboard-container">
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <main class="main-content">
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>

        <div class="dashboard-content">
            <!-- Back Navigation -->
            <div style="margin-bottom: 20px;">
                <a href="<?php echo URLROOT; ?>/Pages/adminPatients" class="action-button secondary" style="width: auto; display: inline-flex; align-items: center; gap: 8px;">
                    ← Back to Patient Management
                </a>
            </div>
            
            <div class="content-sections">
                <div class="content-section full-width">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Patient Applications</h2>
                    </div>

                    <div class="section-content">
                        <?php $rejectedPatients = $data['rejectedPatients'] ?? []; ?>
                        
                        <?php if (!empty($rejectedPatients)): ?>
                            <?php foreach ($rejectedPatients as $patient): ?>
                                <div class="appointment-item" data-patient-id="<?php echo $patient->id; ?>" data-patient-name="<?php echo htmlspecialchars($patient->name ?? ''); ?>" data-patient-email="<?php echo htmlspecialchars($patient->email ?? ''); ?>" data-patient-created="<?php echo htmlspecialchars($patient->created_at ?? ''); ?>">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($patient->name ?? ''); ?></div>
                                        <div class="appointment-date">
                                            Email: <?php echo htmlspecialchars($patient->email ?? ''); ?>
                                        </div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($patient->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge rejected">Rejected</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No rejected patient applications found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Patient Details Modal -->
<div id="patientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Rejected Patient Details</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="doctor-details">
                <div class="detail-row">
                    <label>Name:</label>
                    <span id="modal-patient-name"></span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span id="modal-patient-email"></span>
                </div>
                <div class="detail-row">
                    <label>Updated:</label>
                    <span id="modal-patient-created"></span>
                </div>
                <div class="detail-row">
                    <label>Status:</label>
                    <span class="status-badge rejected">Rejected</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="approveBtn" class="action-button">Approve</button>
            <button id="deleteBtn" class="action-button secondary">Delete</button>
        </div>
    </div>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

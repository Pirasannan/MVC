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
                        <?php $rejectedDoctors = $data['rejectedDoctors'] ?? []; ?>
                        
                        <?php if (!empty($rejectedDoctors)): ?>
                            <?php foreach ($rejectedDoctors as $doctor): ?>
                                <div class="appointment-item" data-doctor-id="<?php echo $doctor->id; ?>" data-doctor-name="<?php echo htmlspecialchars($doctor->name ?? ''); ?>" data-doctor-email="<?php echo htmlspecialchars($doctor->email ?? ''); ?>" data-doctor-created="<?php echo htmlspecialchars($doctor->created_at ?? ''); ?>">
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($doctor->name ?? ''); ?></div>
                                        <div class="appointment-date">
                                            Email: <?php echo htmlspecialchars($doctor->email ?? ''); ?>
                                        </div>
                                        <div class="prescribed-by">Updated: <?php echo htmlspecialchars($doctor->updated_at ?? ''); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge rejected">Rejected</span>
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

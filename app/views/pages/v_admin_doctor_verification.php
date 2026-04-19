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
                        <h2 class="section-title">Pending Doctor Verifications</h2>
                    </div>

                    <div class="section-content">
                        <?php $pendingVerifications = $data['pendingVerifications'] ?? []; ?>

                        <?php if (!empty($pendingVerifications)): ?>
                            <?php foreach ($pendingVerifications as $verification): ?>
                                <?php
                                    $doctorId = (int)($verification->user_id ?? $verification->id ?? 0);
                                    $doctorName = $verification->user_name ?? $verification->name ?? '';
                                    $doctorEmail = $verification->user_email ?? $verification->email ?? '';
                                    $uploadedAt = $verification->uploaded_at ?? $verification->created_at ?? '';
                                    $documentPath = $verification->photo_path ?? '';
                                    $hasDocument = !empty($documentPath);
                                    $doctorStatus = $verification->verification_status ?? $verification->status ?? 'pending';
                                    $statusLabel = $hasDocument ? 'Document Uploaded' : 'Awaiting Upload';
                                ?>
                                <div
                                    class="appointment-item"
                                    data-doctor-id="<?php echo $doctorId; ?>"
                                    data-doctor-name="<?php echo htmlspecialchars($doctorName); ?>"
                                    data-doctor-email="<?php echo htmlspecialchars($doctorEmail); ?>"
                                    data-doctor-created="<?php echo htmlspecialchars($uploadedAt); ?>"
                                    data-doctor-document="<?php echo htmlspecialchars($documentPath); ?>"
                                    data-doctor-status="<?php echo htmlspecialchars($doctorStatus); ?>"
                                >
                                    <div class="appointment-info">
                                        <div class="doctor-name"><?php echo htmlspecialchars($doctorName); ?></div>
                                        <div class="appointment-date">
                                            Submitted: <?php echo htmlspecialchars($uploadedAt); ?>
                                        </div>
                                        <div class="prescribed-by">Email: <?php echo htmlspecialchars($doctorEmail); ?></div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge pending"><?php echo htmlspecialchars($statusLabel); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No pending doctor verification documents found.</p>
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
            <h3>Doctor Verification Details</h3>
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
                    <label>Submitted:</label>
                    <span id="modal-doctor-created"></span>
                </div>
                <div class="detail-row">
                    <label>Status:</label>
                    <span class="status-badge pending">Pending Review</span>
                </div>
                <div class="detail-row">
                    <label>Document:</label>
                    <span>
                        <a href="#" id="modal-doctor-document-link" target="_blank" rel="noopener noreferrer">View Uploaded Document</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="rejectBtn" class="action-button secondary">Reject</button>
            <button id="approveBtn" class="action-button">Approve</button>
        </div>
    </div>
</div>


<?php require APPROOT.'/views/inc/footer.php'; ?>



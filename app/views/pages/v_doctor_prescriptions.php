<?php 
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorPrescriptions';
?>




<div class="dashboard-container">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>
            <!-- Dashboard Content -->
            
            <?php if (isset($_GET['created']) && $_GET['created'] == '1'): ?>
                <div class="success-message" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    ✅ Prescription created successfully<?php if (!empty($data['patient_name'])): ?> for <?php echo htmlspecialchars($data['patient_name']); ?><?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
                <div class="success-message" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    ✅ Prescription updated successfully<?php if (!empty($data['patient_name'])): ?> for <?php echo htmlspecialchars($data['patient_name']); ?><?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
                <div class="success-message" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    ✅ Prescription deleted successfully
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    ❌ Error deleting prescription. Please try again.
                </div>
            <?php endif; ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Prescriptions Section -->
                    <div class="content-section full-width">
                        <div class="prescription-header">
                            <div class="section-header-content">
                                <h2 class="section-title">Issued Prescriptions</h2>
                                <div class="header-actions">
                                    <a class="create-prescription-btn" href="<?php echo URLROOT; ?>/Pages/createprescription" >
                                        <i class="fas fa-plus"></i> Create ePrescription
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="section-content" id="doctor-prescription-list">
                            <?php if (!empty($data['prescriptions'])): ?>
                                <?php foreach ($data['prescriptions'] as $p): ?>
                                    <div class="medication-item <?= ($p->is_deleted === 'deleted') ? 'deleted-item' : '' ?>" onclick="openPrescriptionModal(event, <?= $p->id ?>)">
                                        <div class="medication-info">
                                            <div class="medication-name"><?= htmlspecialchars($p->diagnosis) ?></div>
                                            <div class="medication-details">
                                                <?= htmlspecialchars($p->drug_name) ?> - 
                                                <?= htmlspecialchars($p->dose_amount) . htmlspecialchars($p->dose_unit) ?>, 
                                                <?= htmlspecialchars($p->frequency) ?>
                                            </div>
                                            <div class="prescribed-by">Prescribed to <?= htmlspecialchars($p->patient_name ?? ('Patient #' . $p->patient_id)) ?></div>
                                            <?php if ($p->is_deleted === 'deleted'): ?>
                                                <div class="deleted-status" style="color: #dc3545; font-size: 12px; margin-top: 4px;">
                                                    <strong>DELETED</strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="medication-date">
                                            <div class="prescription-date">
                                                <?= htmlspecialchars(date('Y-m-d', strtotime($p->created_at))) ?>
                                            </div>
                                            <?php if (!empty($p->updated_at)): ?>
                                                <div class="updated-date">
                                                    Updated: <?= htmlspecialchars(date('Y-m-d', strtotime($p->updated_at))) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($p->is_deleted === 'deleted'): ?>
                                                <div class="deleted-date">
                                                    Deleted: <?= htmlspecialchars(date('Y-m-d H:i', strtotime($p->updated_at ?: $p->created_at))) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="prescription-actions">
                                                <?php if ($p->is_deleted !== 'deleted'): ?>
                                                    <a class="action-button-edit" href="<?= URLROOT ?>/Doctor/editPrescription/<?= (int)$p->id ?>" onclick="event.stopPropagation()">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="action-button-delete" onclick="confirmDeletePrescription(event, <?= (int)$p->id ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-prescription-bottle-alt" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                                    <p>No issued prescriptions found.</p>
                                    <p style="font-size: 14px; margin-top: 8px;">Create your first ePrescription to get started.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- View All Button -->
                        <?php if (!empty($data['prescriptions']) && count($data['prescriptions']) > 3): ?>
                            <div class="section-footer">
                                <button class="action-button secondary" id="view-all-btn" onclick="toggleAllPrescriptions()">View All Prescriptions</button>
                            </div>
                        <?php endif; ?>
                
                    </div>
                </div>
            </div>
        </main>
    </div>

<!-- Include Prescription Modal -->
<?php require APPROOT.'/views/pages/prescriptions/view_prescription.php'; ?> 

<!-- Delete confirmation popup-->
<div id="deletePrescriptionPopup" class="popup-overlay" style="display: none;">
  <div class="popup-box">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this prescription? This action cannot be undone.</p>
    <div class="popup-actions">
      <button id="confirmDelete" class="confirm-btn">Yes, Delete</button>
      <button id="cancelDelete" class="cancel-btn">Cancel</button>
    </div>
  </div>
</div>


<!-- Modal functionality is handled by modal-manager.js -->

<?php require APPROOT.'/views/inc/footer.php'; ?>
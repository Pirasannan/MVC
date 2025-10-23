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

                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Appointments Section -->
                    <div class="content-section">


                    <!-- Medical Status Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Issued Prescriptions</h2>
                            <a class="create_eprescription action-button" href="<?php echo URLROOT; ?>/Pages/createprescription">Create ePrescription</a>
                        </div>
                        <div class="section-content" id="doctor-prescription-list">
                            <?php if (!empty($data['prescriptions'])): ?>
                                <?php foreach ($data['prescriptions'] as $p): ?>
                                    <div class="medication-item <?= ($p->is_deleted === 'deleted') ? 'deleted-item' : '' ?>" onclick="openPrescriptionModal(event)">
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
                                            <?= htmlspecialchars(date('Y-m-d', strtotime($p->created_at))) ?><br>
                                            <?php if (!empty($p->updated_at)): ?>
                                                <small>Updated: <?= htmlspecialchars(date('Y-m-d', strtotime($p->updated_at))) ?></small>
                                            <?php endif; ?>
                                            <div style="margin-top:8px;">
                                                <a class="action-button-edit" href="<?= URLROOT ?>/Doctor/editPrescription/<?= (int)$p->id ?>" onclick="event.stopPropagation()">Edit</a>
                                                <?php if ($p->is_deleted !== 'deleted'): ?>
                                                    <button class="action-button-delete" onclick="confirmDeletePrescription(event, <?= (int)$p->id ?>)">Delete</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p align="center" >No issued prescriptions found.</p>
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

<!-- Delete confirmation popup -->
<div id="deletePrescriptionPopup" class="popup-overlay">
  <div class="popup-box">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this prescription? This action cannot be undone.</p>
    <div class="popup-actions">
      <button id="confirmDelete" class="confirm-btn">Yes, Delete</button>
      <button id="cancelDelete" class="cancel-btn">Cancel</button>
    </div>
  </div>
</div>

<script>
function openPrescriptionModal(event) {
    // Only open modal if the click is not on a button or link
    if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON' || event.target.closest('a') || event.target.closest('button')) {
        return;
    }
    
    const modal = document.getElementById('prescriptionPopup');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Show more prescriptions
function toggleAllPrescriptions() {
    const list = document.getElementById('doctor-prescription-list');
    const btn = document.getElementById('view-all-btn');
    if (list && btn) {
        list.classList.toggle('expanded');
        btn.textContent = list.classList.contains('expanded') 
            ? 'Show Less' 
            : 'View All Prescriptions';
    }
}

// Delete prescription confirmation
function confirmDeletePrescription(event, prescriptionId) {
    event.stopPropagation();
    document.getElementById('deletePrescriptionPopup').style.display = 'flex';
    
    // Store the prescription ID for deletion
    document.getElementById('confirmDelete').setAttribute('data-prescription-id', prescriptionId);
}

// Handle delete confirmation popup
document.getElementById("cancelDelete").addEventListener("click", function() {
    document.getElementById("deletePrescriptionPopup").style.display = "none";
});

document.getElementById("confirmDelete").addEventListener("click", function() {
    const prescriptionId = this.getAttribute('data-prescription-id');
    if (prescriptionId) {
        window.location.href = "<?php echo URLROOT; ?>/Doctor/deletePrescription/" + prescriptionId;
    }
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
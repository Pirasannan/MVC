<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientPrescriptions';
?>


<div class="dashboard-container">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Issued Prescriptions</h2>
                </div>

                <div class="section-content" id="prescription-list">
                    <?php if (!empty($data['prescriptions'])): ?>
                        <?php foreach ($data['prescriptions'] as $p): ?>
                            <div class="medication-item" onclick="openPrescriptionModal(event)">
                                <div class="medication-info">
                                    <div class="medication-name"><?= htmlspecialchars($p->diagnosis) ?></div>
                                    <div class="medication-details">
                                        <?= htmlspecialchars($p->drug_name) ?> - 
                                        <?= htmlspecialchars($p->dose_amount) . htmlspecialchars($p->dose_unit) ?>, 
                                        <?= htmlspecialchars($p->frequency) ?>
                                    </div>
                                    <div class="prescribed-by">
                                        Prescribed by Dr. <?= htmlspecialchars($p->doctor_name) ?>
                                    </div>
                                </div>
                                <div class="medication-date">
                                    <?= htmlspecialchars(date('Y-m-d', strtotime($p->created_at))) ?><br>
                                    <?php if (!empty($p->updated_at)): ?>
                                        <small>Updated: <?= htmlspecialchars(date('Y-m-d', strtotime($p->updated_at))) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p align="center">No prescriptions found.</p>
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
    </main>
</div>

<!-- Include Prescription Modal -->
<?php /*require APPROOT.'/views/pages/prescriptions/view_prescription.php'; */?> 

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
    const list = document.getElementById('prescription-list');
    const btn = document.getElementById('view-all-btn');
    if (list && btn) {
        list.classList.toggle('expanded');
        btn.textContent = list.classList.contains('expanded') 
            ? 'Show Less' 
            : 'View All Prescriptions';
    }
}
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
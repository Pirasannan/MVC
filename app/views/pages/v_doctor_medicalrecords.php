<?php
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorMedicalrecords';
$records = $data['records'] ?? [];

$grouped_records = [
    'all' => $records,
    'lab' => [],
    'scan' => [],
    'prescription' => [],
    'hospital' => [],
    'vaccination' => []
];

foreach ($records as $record) {
    if(isset($grouped_records[$record->record_type])) {
        $grouped_records[$record->record_type][] = $record;
    }
}

function renderRecordCard($record) {
    $badges = [
        'lab' => ['class' => 'badge-lab', 'label' => 'Lab Report', 'iconBg' => '#dbeafe', 'iconColor' => '#1d4ed8', 'svg' => '<path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>'],
        'scan' => ['class' => 'badge-scan', 'label' => 'Scan', 'iconBg' => '#fce7f3', 'iconColor' => '#9f1239', 'svg' => '<path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>'],
        'prescription' => ['class' => 'badge-prescription', 'label' => 'Prescription', 'iconBg' => '#dcfce7', 'iconColor' => '#16a34a', 'svg' => '<path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>'],
        'hospital' => ['class' => 'badge-note', 'label' => 'Hospital Visit', 'iconBg' => '#fef3c7', 'iconColor' => '#d97706', 'svg' => '<path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>'],
        'vaccination' => ['class' => 'badge-prescription', 'label' => 'Vaccination', 'iconBg' => '#f3e8ff', 'iconColor' => '#a855f7', 'svg' => '<path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>']
    ];
    $type = $record->record_type ?? 'lab';
    $b = $badges[$type] ?? $badges['lab'];
    $date = date('M d, Y', strtotime($record->uploaded_at));
    $id = $record->id;
?>
<div class="record-card" id="record-card-<?= $id ?>">
    <div class="record-header">
        <div class="record-title-group">
            <div class="record-icon" style="background: <?= $b['iconBg'] ?>; color: <?= $b['iconColor'] ?>;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <?= $b['svg'] ?>
                </svg>
            </div>
            <div>
                <div class="record-title"><?= htmlspecialchars($record->record_name) ?></div>
                <div class="record-date"><?= $date ?></div>
            </div>
        </div>
        <span class="record-badge <?= $b['class'] ?>"><?= $b['label'] ?></span>
    </div>
    <div class="record-meta">
        <span>Patient: <?= htmlspecialchars($record->patient_name ?? 'Unknown') ?></span>
        <span>Type: <?= htmlspecialchars($b['label']) ?></span>
    </div>
    <?php if(!empty($record->description)): ?>
    <div class="record-description" style="font-size: 13px; color: #64748b; margin-bottom: 12px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($record->description) ?>">
        <?= htmlspecialchars($record->description) ?>
    </div>
    <?php endif; ?>
    <div class="record-actions">
        <button class="action-btn primary" onclick="window.open('<?= URLROOT ?>/MedicalRecords/view_file/<?= $id ?>/view', '_blank')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
            </svg>
            View
        </button>
        <button class="action-btn" onclick="window.location.href='<?= URLROOT ?>/MedicalRecords/download/<?= $id ?>'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
            </svg>
            Download
        </button>
    </div>
</div>
<?php } ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/medical_records.css"> 

<div class="dashboard-container">
    <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

    <main class="main-content">
        <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>

        <div class="dashboard-content">

            <!-- Shared Records Stats (Simplified) -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11.5H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= count($records) ?></div>
                    <div class="stat-label">Total Shared Records</div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filter-row">
                    <div class="search-box">
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                            </svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Search by patient name, report name or type...">
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs-container">
                <div class="tabs-group">
                    <button class="tab-button active">All Records</button>
                    <button class="tab-button">Lab Reports</button>
                    <button class="tab-button">Scans & Imaging</button>
                    <button class="tab-button">Physical Prescriptions</button>
                    <button class="tab-button">Hospital Visits</button>
                    <button class="tab-button">Vaccinations</button>
                </div>
            </div>

            <!-- Content Section -->
            <div class="content-section">
                <div class="section-content" id="medical-records-list">
                    
                    <div class="records-grid" id="records-container">
                        <div class="tab-content" id="all-records">
                            <?php foreach($grouped_records['all'] as $record) renderRecordCard($record); ?>
                        </div>

                        <div class="tab-content" id="lab-reports" style="display: none;">
                            <?php foreach($grouped_records['lab'] as $record) renderRecordCard($record); ?>
                        </div>

                        <div class="tab-content" id="scans-imaging" style="display: none;">
                           <?php foreach($grouped_records['scan'] as $record) renderRecordCard($record); ?>
                        </div>

                        <div class="tab-content" id="physical-prescriptions" style="display: none;">
                           <?php foreach($grouped_records['prescription'] as $record) renderRecordCard($record); ?>
                        </div>

                        <div class="tab-content" id="hospital-visits" style="display: none;">
                            <?php foreach($grouped_records['hospital'] as $record) renderRecordCard($record); ?>
                        </div>

                        <div class="tab-content" id="vaccinations" style="display: none;">
                            <?php foreach($grouped_records['vaccination'] as $record) renderRecordCard($record); ?>
                        </div>
                    </div>
                    
                    <?php if (empty($records)): ?>
                    <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; padding: 40px;">
                        <div class="empty-icon" style="color: #cbd5e1; margin-bottom: 20px;">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                        </div>
                        <h3 style="color: #475569;">No Shared Records Found</h3>
                        <p style="color: #64748b;">Records shared with you by patients will appear here.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="security-notice">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11.5H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                        </svg>
                    </span>
                    <span>All records are securely encrypted and private.</span>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Search and Filter specific tabs functionality
const searchInput = document.getElementById('searchInput');

function filterCards() {
    const term = searchInput.value.toLowerCase();
    
    document.querySelectorAll('.tab-content[style="display: block;"], .tab-content:not([style*="display: none"])').forEach(tab => {
        tab.querySelectorAll('.record-card').forEach(card => {
            const title = card.querySelector('.record-title').textContent.toLowerCase();
            const patientName = card.querySelector('.record-meta span:first-child').textContent.toLowerCase();

            if (title.includes(term) || patientName.includes(term)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

if(searchInput) searchInput.addEventListener('input', filterCards);

// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });
        
        const tabText = this.textContent.trim();
        let contentId = '';
        
        switch(tabText) {
            case 'All Records': contentId = 'all-records'; break;
            case 'Lab Reports': contentId = 'lab-reports'; break;
            case 'Scans & Imaging': contentId = 'scans-imaging'; break;
            case 'Physical Prescriptions': contentId = 'physical-prescriptions'; break;
            case 'Hospital Visits': contentId = 'hospital-visits'; break;
            case 'Vaccinations': contentId = 'vaccinations'; break;
        }
        
        if (contentId) {
            const targetContent = document.getElementById(contentId);
            if (targetContent) targetContent.style.display = 'block';
        }
        
        filterCards();
    });
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
<?php
require APPROOT . '/views/inc/header.php';
$current_page = $data['current_page'] ?? 'patientMedicalrecords';
$stats = $data['stats'] ?? [];
$records = $data['records'] ?? [];
$doctors = $data['doctors'] ?? [];

$grouped_records = [
    'all' => $records,
    'lab' => [],
    'scan' => [],
    'prescription' => [],
    'hospital' => [],
    'vaccination' => []
];

foreach ($records as $record) {
    if (isset($grouped_records[$record->record_type])) {
        $grouped_records[$record->record_type][] = $record;
    }
}

function renderRecordCard($record)
{
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
            <span>Type: <?= htmlspecialchars($b['label']) ?></span>
            <span>Doctor: <?= htmlspecialchars($record->doctor_name ?: 'N/A') ?></span>
        </div>
        <?php if (!empty($record->description)): ?>
            <div class="record-description"
                style="font-size: 13px; color: #64748b; margin-bottom: 12px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"
                title="<?= htmlspecialchars($record->description) ?>">
                <?= htmlspecialchars($record->description) ?>
            </div>
        <?php endif; ?>
        <div class="record-actions">
            <button class="action-btn primary"
                onclick="window.open('<?= URLROOT ?>/MedicalRecords/view_file/<?= $id ?>/view', '_blank')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                </svg>
                View
            </button>
            <button class="action-btn" onclick="window.location.href='<?= URLROOT ?>/MedicalRecords/download/<?= $id ?>'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z" />
                </svg>
                Download
            </button>
            <button class="action-btn"
                onclick="editRecord(<?= $id ?>, '<?= htmlspecialchars($type) ?>', '<?= htmlspecialchars(addslashes($record->doctor_name ?? '')) ?>', '<?= htmlspecialchars(addslashes($record->description ?? '')) ?>')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                </svg>
                Edit
            </button>
            <button class="action-btn" onclick="openShareModal(<?= $id ?>)"
                style="color: #6366f1; border-color: #c7d2fe; background: #eef2ff;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.92 18,21.92C19.61,21.92 20.92,20.61 20.92,19C20.92,17.39 19.61,16.08 18,16.08Z" />
                </svg>
                Share
            </button>
            <button class="action-btn" onclick="deleteRecord(<?= $id ?>)"
                style="color: #ef4444; border-color: #fecaca; background: #fff1f2;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z" />
                </svg>
                Delete
            </button>
        </div>
    </div>
<?php } ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/medical_records.css">

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT . '/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT . '/views/inc/components/patientHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">

            <!-- Report Type Stats -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['lab'] ?? 0 ?></div>
                    <div class="stat-label">Lab Reports</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon pink">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['scan'] ?? 0 ?></div>
                    <div class="stat-label">Scans & Imaging</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon green">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['prescription'] ?? 0 ?></div>
                    <div class="stat-label">Prescriptions</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon orange">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['hospital'] ?? 0 ?></div>
                    <div class="stat-label">Hospital Visits</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon purple">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11.5H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $stats['vaccination'] ?? 0 ?></div>
                    <div class="stat-label">Vaccinations</div>
                </div>

            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filter-row">
                    <div class="search-box">
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                            </svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Search by report name or type...">
                    </div>
                    <div class="filter-group">
                        <select class="filter-select" id="sortByType">
                            <option value="">All Types</option>
                            <option value="Lab Report">Lab Report</option>
                            <option value="Scan">Scan</option>
                            <option value="Prescription">Prescription</option>
                            <option value="Hospital Visit">Hospital Visit</option>
                            <option value="Vaccination">Vaccination</option>
                        </select>
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
                <button class="btn-upload" onclick="uploadReport()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                        <path d="M12,11L8,15H11V19H13V15H16L12,11Z" />
                    </svg>
                    Upload Report
                </button>
            </div>

            <!-- Content Section -->
            <div class="content-section">
                <div class="section-content" id="medical-records-list">

                    <div class="records-grid" id="records-container">
                        <!-- All Records Tab Content -->
                        <div class="tab-content" id="all-records">
                            <?php foreach ($grouped_records['all'] as $record)
                                renderRecordCard($record); ?>
                        </div>

                        <!-- Lab Reports Tab Content -->
                        <div class="tab-content" id="lab-reports" style="display: none;">
                            <?php foreach ($grouped_records['lab'] as $record)
                                renderRecordCard($record); ?>
                        </div>

                        <!-- Scans & Imaging Tab Content -->
                        <div class="tab-content" id="scans-imaging" style="display: none;">
                            <?php foreach ($grouped_records['scan'] as $record)
                                renderRecordCard($record); ?>
                        </div>

                        <!-- Physical Prescriptions Tab Content -->
                        <div class="tab-content" id="physical-prescriptions" style="display: none;">
                            <?php foreach ($grouped_records['prescription'] as $record)
                                renderRecordCard($record); ?>
                        </div>

                        <!-- Hospital Visits Tab Content -->
                        <div class="tab-content" id="hospital-visits" style="display: none;">
                            <?php foreach ($grouped_records['hospital'] as $record)
                                renderRecordCard($record); ?>
                        </div>

                        <!-- Vaccinations Tab Content -->
                        <div class="tab-content" id="vaccinations" style="display: none;">
                            <?php foreach ($grouped_records['vaccination'] as $record)
                                renderRecordCard($record); ?>
                        </div>
                    </div>

                    <!-- Show empty state if no records anywhere -->
                    <?php if (empty($records)): ?>
                        <div class="empty-state"
                            style="display: flex; flex-direction: column; align-items: center; padding: 40px;">
                            <div class="empty-icon" style="color: #cbd5e1; margin-bottom: 20px;">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                </svg>
                            </div>
                            <h3 style="color: #475569;">No Medical Records Yet</h3>
                            <p style="color: #64748b;">Upload your first report to securely store and view it here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Upload Report Modal -->
<div id="uploadModal" class="medical-upload-overlay" style="display: none;">
    <div class="medical-upload-container">
        <div class="medical-upload-header">
            <h3>Upload Medical Report</h3>
            <button class="medical-upload-close" onclick="closeUploadModal()">&times;</button>
        </div>

        <div class="medical-upload-body">
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" id="editRecordId" name="editRecordId" value="">
                <!-- Document Upload -->
                <div class="medical-upload-form-group">
                    <label for="document">Upload Document</label>
                    <div class="medical-upload-file-container">
                        <input type="file" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            required>
                        <div class="medical-upload-drop-area" onclick="document.getElementById('document').click()">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                <path d="M12,11L8,15H11V19H13V15H16L12,11Z" />
                            </svg>
                            <p>Click to upload or drag and drop</p>
                            <small>PDF, JPG, PNG, DOC, DOCX (Max 10MB)</small>
                        </div>
                    </div>
                </div>

                <!-- Doctor Name -->
                <div class="medical-upload-form-group">
                    <label for="doctorName">Doctor Name</label>
                    <input type="text" id="doctorName" name="doctorName" placeholder="Enter doctor's name">
                </div>

                <!-- Report Type -->
                <div class="medical-upload-form-group">
                    <label for="reportType">Report Type</label>
                    <select id="reportType" name="reportType" required onchange="updateDescription()">
                        <option value="">Select report type</option>
                        <option value="lab">Lab Report</option>
                        <option value="scan">Scan</option>
                        <option value="prescription">Physical Prescription</option>
                        <option value="hospital">Hospital Visit</option>
                        <option value="vaccination">Vaccination</option>
                    </select>
                </div>

                <!-- Description based on type -->
                <div class="medical-upload-form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description of the report"
                        rows="3"></textarea>
                    <div id="typeDescription" class="medical-upload-type-description"></div>
                </div>
            </form>
        </div>

        <div class="medical-upload-footer">
            <button type="button" class="medical-upload-cancel-btn" onclick="closeUploadModal()">Cancel</button>
            <button type="button" class="medical-upload-submit-btn" onclick="submitUpload()">Upload Report</button>
        </div>
    </div>
</div>

<!-- Share Report Modal -->
<div id="shareModal" class="medical-upload-overlay" style="display: none;">
    <div class="medical-upload-container">
        <div class="medical-upload-header">
            <h3>Share Medical Report</h3>
            <button class="medical-upload-close" onclick="closeShareModal()">&times;</button>
        </div>

        <div class="medical-upload-body">
            <form id="shareForm">
                <input type="hidden" id="shareRecordId" name="shareRecordId" value="">

                <div class="medical-upload-form-group">
                    <label for="doctorSelect">Share With Doctor</label>
                    <input type="text" id="doctorSearch" placeholder="Search doctor by name..."
                        style="margin-bottom: 8px; width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <select id="doctorSelect" name="doctor_id" required size="5"
                        style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="" disabled selected>Select a doctor below...</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?= $doc->id ?>"><?= htmlspecialchars($doc->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="medical-upload-footer">
            <button type="button" class="medical-upload-cancel-btn" onclick="closeShareModal()">Cancel</button>
            <button type="button" class="medical-upload-submit-btn" onclick="submitShare()">Share Report</button>
        </div>
    </div>
</div>

<style>
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #4a90e2;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        font-weight: 500;
        font-size: 14px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast-notification.error {
        background: #ef4444;
    }
</style>

<div id="toast-notification" class="toast-notification">
    <span id="toast-message"></span>
</div>

<script>
    function showToast(message, isError = false) {
        const toast = document.getElementById('toast-notification');
        const msg = document.getElementById('toast-message');
        msg.textContent = message;

        if (isError) toast.classList.add('error');
        else toast.classList.remove('error');

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function uploadReport() {
        document.getElementById('uploadModal').style.display = 'flex';
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').style.display = 'none';
        document.getElementById('uploadForm').reset();
        document.getElementById('typeDescription').style.display = 'none';
        document.getElementById('editRecordId').value = '';
        document.querySelector('.medical-upload-header h3').textContent = 'Upload Medical Report';
        document.querySelector('.medical-upload-submit-btn').textContent = 'Upload Report';
        document.getElementById('document').required = true;
        const uploadArea = document.querySelector('.medical-upload-drop-area p');
        uploadArea.textContent = 'Click to upload or drag and drop';
    }

    function editRecord(id, type, doctor, description) {
        document.getElementById('uploadModal').style.display = 'flex';
        document.querySelector('.medical-upload-header h3').textContent = 'Edit Medical Report';
        document.querySelector('.medical-upload-submit-btn').textContent = 'Save Changes';

        document.getElementById('editRecordId').value = id;
        document.getElementById('reportType').value = type;
        document.getElementById('doctorName').value = doctor;
        document.getElementById('description').value = description;

        document.getElementById('document').required = false;
        document.querySelector('.medical-upload-drop-area p').textContent = 'Click to replace file (optional)';

        updateDescription();
    }

    function updateDescription() {
        const reportType = document.getElementById('reportType').value;
        const typeDescription = document.getElementById('typeDescription');

        const descriptions = {
            'lab': 'Lab reports include blood tests, urine tests, and other laboratory results.',
            'scan': 'Scans include X-rays, MRI, CT scans, and other imaging studies.',
            'prescription': 'Physical prescriptions from doctors with medication details.',
            'hospital': 'Hospital visit records, discharge summaries, and admission documents.',
            'vaccination': 'Vaccination records and immunization certificates.'
        };

        if (reportType && descriptions[reportType]) {
            typeDescription.textContent = descriptions[reportType];
            typeDescription.style.display = 'block';
        } else {
            typeDescription.style.display = 'none';
        }
    }

    function submitUpload() {
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);

        // Basic validation
        const reportType = document.getElementById('reportType').value;
        const fileInput = document.getElementById('document');
        const editId = document.getElementById('editRecordId').value;
        const isEdit = editId !== '';

        if (!reportType) {
            showToast('Please select report type', true);
            return;
        }

        if (!isEdit && !fileInput.files[0]) {
            showToast('Please select a file to upload', true);
            return;
        }

        // Check file size (10MB limit)
        if (fileInput.files[0] && fileInput.files[0].size > 10 * 1024 * 1024) {
            showToast('File size must be less than 10MB', true);
            return;
        }

        const submitBtn = document.querySelector('.medical-upload-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = isEdit ? 'Saving...' : 'Uploading...';

        const targetUrl = isEdit
            ? '<?= URLROOT ?>/MedicalRecords/edit/' + editId
            : '<?= URLROOT ?>/MedicalRecords/upload';

        fetch(targetUrl, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || (isEdit ? 'Report updated successfully!' : 'Report uploaded successfully!'));
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Error: ' + data.message, true);
                    submitBtn.disabled = false;
                    submitBtn.textContent = isEdit ? 'Save Changes' : 'Upload Report';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred.', true);
                submitBtn.disabled = false;
                submitBtn.textContent = isEdit ? 'Save Changes' : 'Upload Report';
            });
    }

    function deleteRecord(id) {
        if (!confirm('Are you sure you want to delete this record?')) return;

        fetch('<?= URLROOT ?>/MedicalRecords/delete/' + id, {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('record-card-' + id).remove();
                    showToast('Record deleted.');
                } else {
                    showToast('Error: ' + data.message, true);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function openShareModal(id) {
        document.getElementById('shareRecordId').value = id;
        document.getElementById('shareModal').style.display = 'flex';
        document.getElementById('doctorSearch').value = '';

        // Reset options
        const select = document.getElementById('doctorSelect');
        select.value = "";
        Array.from(select.options).forEach(opt => {
            opt.style.display = '';
        });
    }

    function closeShareModal() {
        document.getElementById('shareModal').style.display = 'none';
        document.getElementById('shareForm').reset();
    }

    // Simple search for doctor dropdown
    document.getElementById('doctorSearch').addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        const select = document.getElementById('doctorSelect');
        Array.from(select.options).forEach(opt => {
            if (opt.value === "") return; // Skip placeholder

            const text = opt.textContent.toLowerCase();
            if (text.includes(term)) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
    });

    function submitShare() {
        const recordId = document.getElementById('shareRecordId').value;
        const doctorId = document.getElementById('doctorSelect').value;

        if (!doctorId) {
            showToast('Please select a doctor', true);
            return;
        }

        const formData = new FormData();
        formData.append('doctor_id', doctorId);

        const submitBtn = document.querySelector('#shareModal .medical-upload-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sharing...';

        fetch('<?= URLROOT ?>/MedicalRecords/share/' + recordId, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Report shared successfully!');
                    closeShareModal();
                } else {
                    showToast('Error: ' + data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred.', true);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Share Report';
            });
    }

    // Close modal when clicking outside
    document.getElementById('uploadModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeUploadModal();
        }
    });

    // File input change handler
    document.getElementById('document').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const uploadArea = document.querySelector('.medical-upload-drop-area p');
            uploadArea.textContent = `Selected: ${file.name}`;
        }
    });

    // Search and Filter specific tabs functionality
    const searchInput = document.getElementById('searchInput');
    const sortByType = document.getElementById('sortByType');

    function filterCards() {
        const term = searchInput.value.toLowerCase();
        const typeFilter = sortByType.value.toLowerCase();

        // We only filter visible tab content
        document.querySelectorAll('.tab-content[style="display: block;"], .tab-content:not([style*="display: none"])').forEach(tab => {
            tab.querySelectorAll('.record-card').forEach(card => {
                const title = card.querySelector('.record-title').textContent.toLowerCase();
                const typeText = card.querySelector('.record-meta span:first-child').textContent.toLowerCase();
                const doctorText = card.querySelector('.record-meta span:last-child').textContent.toLowerCase();

                const matchesSearch = title.includes(term) || doctorText.includes(term);
                const matchesType = typeFilter === "" || typeText.includes(typeFilter);

                if (matchesSearch && matchesType) {
                    card.style.display = ''; // Reset display so it falls back to its natural CSS (block/grid-item)
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterCards);
    if (sortByType) sortByType.addEventListener('change', filterCards);

    // Tab switching functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Show the corresponding tab content
            const tabText = this.textContent.trim();
            let contentId = '';

            switch (tabText) {
                case 'All Records':
                    contentId = 'all-records';
                    break;
                case 'Lab Reports':
                    contentId = 'lab-reports';
                    break;
                case 'Scans & Imaging':
                    contentId = 'scans-imaging';
                    break;
                case 'Physical Prescriptions':
                    contentId = 'physical-prescriptions';
                    break;
                case 'Hospital Visits':
                    contentId = 'hospital-visits';
                    break;
                case 'Vaccinations':
                    contentId = 'vaccinations';
                    break;
            }

            if (contentId) {
                const targetContent = document.getElementById(contentId);
                if (targetContent) {
                    targetContent.style.display = 'block';
                }
            }

            // Re-apply filters to newly visible tab
            filterCards();
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
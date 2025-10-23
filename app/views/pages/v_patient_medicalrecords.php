<?php

require APPROOT.'/views/inc/header.php';
$current_page = 'patientMedicalrecords';

?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/medical_records.css"> 


<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">

            <!-- Report Type Stats -->
            <div class="stats-grid">
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">2</div>
                    <div class="stat-label">Lab Reports</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon pink">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">2</div>
                    <div class="stat-label">Scans & Imaging</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon green">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">1</div>
                    <div class="stat-label">Prescriptions</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon orange">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">1</div>
                    <div class="stat-label">Hospital Visits</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon purple">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11.5H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">1</div>
                    <div class="stat-label">Vaccinations</div>
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
                        <input type="text" placeholder="Search by report name or type...">
                    </div>
                    <div class="filter-group">
                        <select class="filter-select">
                            <option>All Types</option>
                            <option>Lab Report</option>
                            <option>Scan</option>
                            <option>Doctor Note</option>
                        </select>
                        <select class="filter-select">
                            <option>All Time</option>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                        </select>
                        <select class="filter-select">
                            <option>Sort: Newest</option>
                            <option>Sort: Oldest</option>
                            <option>Sort: Type</option>
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
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        <path d="M12,11L8,15H11V19H13V15H16L12,11Z"/>
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
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Blood Test Results</div>
                                            <div class="record-date">Dec 15, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-lab">Lab Report</span>
                                </div>
                                <div class="record-meta">
                                    <span>Lab: City Medical Lab</span>
                                    <span>Doctor: Dr. Sarah Johnson</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                        </svg>
                                        View
                                    </button>
                                    <button class="action-btn">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                        </svg>
                                        Download
                                    </button>
                                    <button class="action-btn">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.91 18,21.91C19.61,21.91 20.92,20.61 20.92,19A2.92,2.92 0 0,0 18,16.08Z"/>
                                        </svg>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lab Reports Tab Content -->
                        <div class="tab-content" id="lab-reports" style="display: none;">
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Complete Blood Count (CBC)</div>
                                            <div class="record-date">Dec 10, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-lab">Lab Report</span>
                                </div>
                                <div class="record-meta">
                                    <span>Lab: Central Diagnostic Lab</span>
                                    <span>Doctor: Dr. Michael Chen</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Lipid Profile Test</div>
                                            <div class="record-date">Nov 28, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-lab">Lab Report</span>
                                </div>
                                <div class="record-meta">
                                    <span>Lab: Health Plus Laboratory</span>
                                    <span>Doctor: Dr. Sarah Johnson</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                        </div>

                        <!-- Scans & Imaging Tab Content -->
                        <div class="tab-content" id="scans-imaging" style="display: none;">
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #fce7f3; color: #9f1239;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Chest X-Ray</div>
                                            <div class="record-date">Dec 12, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-scan">Scan</span>
                                </div>
                                <div class="record-meta">
                                    <span>Imaging Center: Metro Radiology</span>
                                    <span>Doctor: Dr. Lisa Wang</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #fce7f3; color: #9f1239;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">MRI Brain Scan</div>
                                            <div class="record-date">Nov 20, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-scan">Scan</span>
                                </div>
                                <div class="record-meta">
                                    <span>Imaging Center: Advanced MRI Center</span>
                                    <span>Doctor: Dr. Robert Kim</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Prescriptions Tab Content -->
                        <div class="tab-content" id="physical-prescriptions" style="display: none;">
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #dcfce7; color: #16a34a;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Prescription - Antibiotics</div>
                                            <div class="record-date">Dec 8, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-prescription">Prescription</span>
                                </div>
                                <div class="record-meta">
                                    <span>Doctor: Dr. Sarah Johnson</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                        </div>

                        <!-- Hospital Visits Tab Content -->
                        <div class="tab-content" id="hospital-visits" style="display: none;">
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #fef3c7; color: #d97706;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">Emergency Room Visit</div>
                                            <div class="record-date">Dec 5, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-note">Hospital Visit</span>
                                </div>
                                <div class="record-meta">
                                    <span>Hospital: City General Hospital</span>
                                    <span>Doctor: Dr. Emily Davis</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                        </div>

                        <!-- Vaccinations Tab Content -->
                        <div class="tab-content" id="vaccinations" style="display: none;">
                            <div class="record-card">
                                <div class="record-header">
                                    <div class="record-title-group">
                                        <div class="record-icon" style="background: #f3e8ff; color: #a855f7;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="record-title">COVID-19 Booster Shot</div>
                                            <div class="record-date">Nov 15, 2024</div>
                                        </div>
                                    </div>
                                    <span class="record-badge badge-prescription">Vaccination</span>
                                </div>
                                <div class="record-meta">
                                    <span>Vaccine: Pfizer-BioNTech</span>
                                    <span>Doctor: Dr. Maria Rodriguez</span>
                                </div>
                                <div class="record-actions">
                                    <button class="action-btn primary">View</button>
                                    <button class="action-btn">Download</button>
                                    <button class="action-btn">Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="empty-state" style="display: none;">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                        </div>
                        <h3>No Medical Records Yet</h3>
                    </div>
                </div>

                <!-- Security Notice -->
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

<!-- Upload Report Modal -->
<div id="uploadModal" class="medical-upload-overlay" style="display: none;">
    <div class="medical-upload-container">
        <div class="medical-upload-header">
            <h3>Upload Medical Report</h3>
            <button class="medical-upload-close" onclick="closeUploadModal()">&times;</button>
        </div>
        
        <div class="medical-upload-body">
            <form id="uploadForm" enctype="multipart/form-data">
                <!-- Document Upload -->
                <div class="medical-upload-form-group">
                    <label for="document">Upload Document</label>
                    <div class="medical-upload-file-container">
                        <input type="file" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <div class="medical-upload-drop-area" onclick="document.getElementById('document').click()">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                <path d="M12,11L8,15H11V19H13V15H16L12,11Z"/>
                            </svg>
                            <p>Click to upload or drag and drop</p>
                            <small>PDF, JPG, PNG, DOC, DOCX (Max 10MB)</small>
                        </div>
                    </div>
                </div>

                <!-- Doctor Name -->
                <div class="medical-upload-form-group">
                    <label for="doctorName">Doctor Name</label>
                    <input type="text" id="doctorName" name="doctorName" placeholder="Enter doctor's name" required>
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
                    <textarea id="description" name="description" placeholder="Brief description of the report" rows="3"></textarea>
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

<script>
function uploadReport() {
    document.getElementById('uploadModal').style.display = 'flex';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.getElementById('uploadForm').reset();
    document.getElementById('typeDescription').style.display = 'none';
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
    const doctorName = document.getElementById('doctorName').value.trim();
    const reportType = document.getElementById('reportType').value;
    const fileInput = document.getElementById('document');
    
    if (!doctorName) {
        alert('Please enter doctor name');
        return;
    }
    
    if (!reportType) {
        alert('Please select report type');
        return;
    }
    
    if (!fileInput.files[0]) {
        alert('Please select a file to upload');
        return;
    }
    
    // Check file size (10MB limit)
    if (fileInput.files[0].size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        return;
    }
    
    // Simulate upload process
    const submitBtn = document.querySelector('.medical-upload-submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Uploading...';
    
    // Simulate API call
    setTimeout(() => {
        alert('Report uploaded successfully!');
        closeUploadModal();
        submitBtn.disabled = false;
        submitBtn.textContent = 'Upload Report';
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

// File input change handler
document.getElementById('document').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const uploadArea = document.querySelector('.medical-upload-drop-area p');
        uploadArea.textContent = `Selected: ${file.name}`;
    }
});

// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
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
        
        switch(tabText) {
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
    });
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
<?php
require APPROOT.'/views/inc/header.php';
$current_page = 'doctorMedicalrecords';

?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/medical_records.css"> 


<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>
<div>
        <div class="page-content">
            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="search-box">
                        <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                        </svg>
                        <input type="text" placeholder="Search patient name or report type..." class="search-input">
                    </div>
                    <div class="filter-group">
                        <select class="filter-select">
                            <option value="">All Types</option>
                            <option value="lab">Lab Report</option>
                            <option value="scan">Scans & Imaging</option>
                            <option value="prescription">Physical Prescription</option>
                            <option value="hospital">Hospital Visit</option>
                            <option value="vaccination">Vaccination</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Patients</option>
                            <option value="john">John Smith</option>
                            <option value="sarah">Sarah Johnson</option>
                            <option value="michael">Michael Chen</option>
                            <option value="emily">Emily Davis</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                        <select class="filter-select">
                            <option value="">Sort</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="patient">Patient Name</option>
                            <option value="type">Report Type</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Patient Search Panel -->
            <div class="patient-search-panel">
                <div class="search-container">
                    <div class="search-input-group">
                        <select id="patientSelect" class="patient-select" onchange="filterByPatient()">
                            <option value="">Select Patient</option>
                            <option value="john">John Smith</option>
                            <option value="sarah">Sarah Johnson</option>
                            <option value="michael">Michael Chen</option>
                            <option value="emily">Emily Davis</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="content-section">
                <div class="section-content" id="medical-records-list">
                    <div class="records-grid" id="records-container">
                        <!-- John Smith's Records -->
                        <div class="record-card" data-patient="john">
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
                                <span>Patient: John Smith</span>
                                <span>Lab: City Medical Lab</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <div class="record-card" data-patient="john">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9,2V7.62L6.5,10.12L9,12.62V14.5C9,15.05 8.55,15.5 8,15.5H7V17.5C7,18.05 6.55,18.5 6,18.5H4V20.5H6C6.55,20.5 7,20.05 7,19.5V17.5H8C8.55,17.5 9,17.05 9,16.5V14.5L6.5,12L9,9.5V2H9Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">MRI Brain Scan</div>
                                        <div class="record-date">Dec 10, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-scan">Scan</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: John Smith</span>
                                <span>Imaging: Metro Imaging Center</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <!-- Sarah Johnson's Records -->
                        <div class="record-card" data-patient="sarah">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9,2V7.62L6.5,10.12L9,12.62V14.5C9,15.05 8.55,15.5 8,15.5H7V17.5C7,18.05 6.55,18.5 6,18.5H4V20.5H6C6.55,20.5 7,20.05 7,19.5V17.5H8C8.55,17.5 9,17.05 9,16.5V14.5L6.5,12L9,9.5V2H9Z"/>
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
                                <span>Patient: Sarah Johnson</span>
                                <span>Imaging: Regional Radiology</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <div class="record-card" data-patient="sarah">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #dcfce7; color: #16a34a;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">Prescription - Hypertension Meds</div>
                                        <div class="record-date">Dec 8, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-prescription">Prescription</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: Sarah Johnson</span>
                                <span>Doctor: Dr. Smith</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <!-- Michael Chen's Records -->
                        <div class="record-card" data-patient="michael">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #dcfce7; color: #16a34a;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">Prescription - Antibiotics</div>
                                        <div class="record-date">Dec 5, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-prescription">Prescription</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: Michael Chen</span>
                                <span>Doctor: Dr. Johnson</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <div class="record-card" data-patient="michael">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #fef3c7; color: #d97706;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">Emergency Room Visit</div>
                                        <div class="record-date">Dec 3, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-hospital">Hospital Visit</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: Michael Chen</span>
                                <span>Hospital: City General Hospital</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <!-- Emily Davis's Records -->
                        <div class="record-card" data-patient="emily">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #dbeafe; color: #1d4ed8;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V7H5V5H19M5,19V9H19V19H5M7,11H9V17H7V11M11,11H13V17H11V11M15,11H17V17H15V11Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">Complete Blood Count</div>
                                        <div class="record-date">Dec 1, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-lab">Lab Report</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: Emily Davis</span>
                                <span>Lab: Central Lab Services</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <div class="record-card" data-patient="emily">
                            <div class="record-header">
                                <div class="record-title-group">
                                    <div class="record-icon" style="background: #fce7f3; color: #9f1239;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.4 16,13V16C16,16.6 15.6,17 15,17H9C8.4,17 8,16.6 8,16V13C8,12.4 8.4,11.5 9,11.5V10C9,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.2,9.2 10.2,10V11.5H13.8V10C13.8,9.2 12.8,8.2 12,8.2Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="record-title">COVID-19 Booster Shot</div>
                                        <div class="record-date">Nov 28, 2024</div>
                                    </div>
                                </div>
                                <span class="record-badge badge-vaccination">Vaccination</span>
                            </div>
                            <div class="record-meta">
                                <span>Patient: Emily Davis</span>
                                <span>Vaccine: Pfizer-BioNTech</span>
                            </div>
                            <div class="record-actions">
                                <button class="action-btn primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                                    </svg>
                                    View Details
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    Write Notes
                                </button>
                                <button class="action-btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5,20H19V18H5M19,9H15V13H19V9M5,9V13H9V9H5M11,11H13V17H11V11M5,15H9V17H5V15M15,15H19V17H15V15Z"/>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="empty-state" style="display: none;">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                            </svg>
                        </div>
                        <h3>No Medical Records Found</h3>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="security-notice">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11.5H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                        </svg>
                    </span>
                    <span>All patient records are securely encrypted and private.</span>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Patient filtering functionality
function filterByPatient() {
    const patientSelect = document.getElementById('patientSelect');
    const selectedPatient = patientSelect.value;
    const recordCards = document.querySelectorAll('.record-card');
    
    recordCards.forEach(card => {
        if (selectedPatient === '' || selectedPatient === 'all') {
            card.style.display = 'block';
        } else {
            const patientData = card.getAttribute('data-patient');
            if (patientData === selectedPatient) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}

// Search functionality
document.querySelector('.search-input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const recordCards = document.querySelectorAll('.record-card');
    
    recordCards.forEach(card => {
        const title = card.querySelector('.record-title').textContent.toLowerCase();
        const patient = card.querySelector('.record-meta span').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || patient.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filter functionality
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        // Add filter logic here based on the select value
        console.log('Filter changed:', this.value);
    });
});
</script>

<?php require APPROOT.'/views/inc/footer.php'; ?>
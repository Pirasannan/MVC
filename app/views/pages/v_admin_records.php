<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminRecords';
?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/adminSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/adminHeader.php'; ?>


        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Recent Consultations Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Consultations</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Anura Bandara - Dr. Sarah Johnson</div>
                                <div class="appointment-date">Date: 2025-10-18 | Duration: 25 mins</div>
                                <div class="prescribed-by">Diagnosis: Hypertension follow-up | Prescription issued</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Nethmi Silva - Dr. Michael Chen</div>
                                <div class="appointment-date">Date: 2025-10-18 | Duration: 30 mins</div>
                                <div class="prescribed-by">Diagnosis: Diabetes management | Test referral issued</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Roshan Fernando - Dr. Emily Davis</div>
                                <div class="appointment-date">Date: 2025-10-17 | Duration: 20 mins</div>
                                <div class="prescribed-by">Diagnosis: Respiratory infection | Prescription issued</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dilani Perera - Dr. Sunil Jayawardena</div>
                                <div class="appointment-date">Date: 2025-10-17 | Duration: 35 mins</div>
                                <div class="prescribed-by">Diagnosis: Allergic reaction | Prescription and lifestyle advice issued</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View All Consultations</button>
                    </div>
                </div>

                <!-- Prescription Records Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Prescriptions</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Anura Bandara - Hypertension Treatment</div>
                                <div class="medication-details">Lisinopril 10mg - Once daily for 30 days</div>
                                <div class="prescribed-by">Prescribed by: Dr. Sarah Johnson | Date: 2025-10-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Nethmi Silva - Diabetes Management</div>
                                <div class="medication-details">Metformin 500mg - Twice daily for 60 days</div>
                                <div class="prescribed-by">Prescribed by: Dr. Michael Chen | Date: 2025-10-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Roshan Fernando - Respiratory Infection</div>
                                <div class="medication-details">Amoxicillin 500mg - Three times daily for 7 days</div>
                                <div class="prescribed-by">Prescribed by: Dr. Emily Davis | Date: 2025-10-17</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dilani Perera - Allergy Treatment</div>
                                <div class="medication-details">Cetirizine 10mg - Once daily for 14 days</div>
                                <div class="prescribed-by">Prescribed by: Dr. Sunil Jayawardena | Date: 2025-10-17</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View All Prescriptions</button>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Test Referrals & Reports Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Test Referrals & Lab Reports</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Nethmi Silva - Blood Sugar Test</div>
                                <div class="appointment-date">Referred by: Dr. Michael Chen | Date: 2025-10-18</div>
                                <div class="prescribed-by">Lab: Asiri Laboratory Services | Report uploaded</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Roshan Fernando - Chest X-Ray</div>
                                <div class="appointment-date">Referred by: Dr. Emily Davis | Date: 2025-10-17</div>
                                <div class="prescribed-by">Lab: Nawaloka Radiology | Report pending</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Anura Bandara - Lipid Profile Test</div>
                                <div class="appointment-date">Referred by: Dr. Sarah Johnson | Date: 2025-10-15</div>
                                <div class="prescribed-by">Lab: Durdans Laboratory | Report uploaded</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge confirmed">Completed</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View All Test Records</button>
                    </div>
                </div>

                <!-- Medical History Archive Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Patient Medical History</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Anura Bandara - Chronic Conditions</div>
                                <div class="medication-details">Hypertension (Diagnosed: 2023), High Cholesterol (Diagnosed: 2022)</div>
                                <div class="prescribed-by">Total Consultations: 12 | Last Updated: 2025-10-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">View</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Nethmi Silva - Chronic Conditions</div>
                                <div class="medication-details">Type 2 Diabetes (Diagnosed: 2024), Thyroid disorder (Diagnosed: 2023)</div>
                                <div class="prescribed-by">Total Consultations: 8 | Last Updated: 2025-10-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">View</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Roshan Fernando - Medical History</div>
                                <div class="medication-details">Asthma (Diagnosed: 2020), No chronic conditions</div>
                                <div class="prescribed-by">Total Consultations: 6 | Last Updated: 2025-10-17</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">View</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button secondary">Access Full Archives</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
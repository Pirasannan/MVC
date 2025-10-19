<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminDoctors';
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
                <!-- Pending Verifications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Pending Verifications</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Rajesh Kumar</div>
                                <div class="appointment-date">Cardiology | SLMC Reg: 12345</div>
                                <div class="prescribed-by">Submitted: 2025-10-15 | Documents: NIC, Medical License</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Nisha Perera</div>
                                <div class="appointment-date">General Practice | SLMC Reg: 23456</div>
                                <div class="prescribed-by">Submitted: 2025-10-16 | Documents: NIC, Medical License</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Amara Fernando</div>
                                <div class="appointment-date">Pediatrics | SLMC Reg: 34567</div>
                                <div class="prescribed-by">Submitted: 2025-10-17 | Documents: NIC, Medical License</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Kasun Silva</div>
                                <div class="appointment-date">Internal Medicine | SLMC Reg: 45678</div>
                                <div class="prescribed-by">Submitted: 2025-10-17 | Documents: NIC, Medical License</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Review Documents</button>
                    </div>
                </div>

                <!-- Verified Doctors Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Verified Doctors</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Sarah Johnson</div>
                                <div class="medication-details">Cardiology | City Care Medical Center</div>
                                <div class="prescribed-by">SLMC Reg: 11223 | Verified: 2025-09-20</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Michael Chen</div>
                                <div class="medication-details">General Practice | Wellness Medical Clinic</div>
                                <div class="prescribed-by">SLMC Reg: 22334 | Verified: 2025-09-18</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Emily Davis</div>
                                <div class="medication-details">Pediatrics | HealthFirst GP Center</div>
                                <div class="prescribed-by">SLMC Reg: 33445 | Verified: 2025-09-15</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Sunil Jayawardena</div>
                                <div class="medication-details">Internal Medicine | Central Care Clinic</div>
                                <div class="prescribed-by">SLMC Reg: 44556 | Verified: 2025-10-10</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View All Doctors</button>
                    </div>
                </div>
            </div>

            <!-- Additional Content Sections Row -->
            <div class="content-sections">
                <!-- Rejected Applications Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Applications</h2>
                    </div>
                    <div class="section-content">
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Anil Rodrigo</div>
                                <div class="appointment-date">General Practice | SLMC Reg: 56789</div>
                                <div class="prescribed-by">Reason: Incomplete documentation | Rejected: 2025-10-12</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge rejected">Rejected</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Priya Gunawardena</div>
                                <div class="appointment-date">Dermatology | SLMC Reg: 67890</div>
                                <div class="prescribed-by">Reason: License verification failed | Rejected: 2025-10-08</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge rejected">Rejected</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Dr. Rohan Wijesinghe</div>
                                <div class="appointment-date">Orthopedics | SLMC Reg: 78901</div>
                                <div class="prescribed-by">Reason: Invalid SLMC registration | Rejected: 2025-10-05</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge rejected">Rejected</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View Rejection Details</button>
                    </div>
                </div>

                <!-- Inactive/Suspended Doctors Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Inactive/Suspended Accounts</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Chaminda Perera</div>
                                <div class="medication-details">General Practice | Wellness Medical Clinic</div>
                                <div class="prescribed-by">Reason: Account deactivated by user | Date: 2025-09-25</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Inactive</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Lakshmi Fernando</div>
                                <div class="medication-details">Pediatrics | Central Care Clinic</div>
                                <div class="prescribed-by">Reason: Policy violation - inappropriate conduct | Date: 2025-10-01</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">Suspended</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dr. Dinesh Rathnayake</div>
                                <div class="medication-details">Internal Medicine | HealthFirst GP Center</div>
                                <div class="prescribed-by">Reason: No activity for 90 days | Date: 2025-09-10</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Inactive</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Manage Accounts</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
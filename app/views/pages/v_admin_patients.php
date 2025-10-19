<?php 
    require APPROOT.'/views/inc/header.php'; 
    $current_page = 'adminPatients';
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
                                <div class="doctor-name">Kamal Wickramasinghe</div>
                                <div class="appointment-date">Phone: +94 77 123 4567 | OTP Verified</div>
                                <div class="prescribed-by">Submitted: 2025-10-18 | Documents: NIC</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Priya Mendis</div>
                                <div class="appointment-date">Email: priya.m@email.com | OTP Verified</div>
                                <div class="prescribed-by">Submitted: 2025-10-17 | Documents: NIC</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Saman De Silva</div>
                                <div class="appointment-date">Phone: +94 71 987 6543 | OTP Verified</div>
                                <div class="prescribed-by">Submitted: 2025-10-17 | Documents: NIC</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge pending">Pending Review</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Nimal Jayasuriya</div>
                                <div class="appointment-date">Email: nimal.j@email.com | OTP Verified</div>
                                <div class="prescribed-by">Submitted: 2025-10-16 | Documents: NIC</div>
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

                <!-- Verified Patients Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Verified Patients</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Anura Bandara</div>
                                <div class="medication-details">Phone: +94 77 234 5678 | Age: 45</div>
                                <div class="prescribed-by">Registered: 2025-09-20 | Last Visit: 2025-10-15</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Nethmi Silva</div>
                                <div class="medication-details">Email: nethmi.s@email.com | Age: 32</div>
                                <div class="prescribed-by">Registered: 2025-09-18 | Last Visit: 2025-10-12</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Roshan Fernando</div>
                                <div class="medication-details">Phone: +94 71 345 6789 | Age: 58</div>
                                <div class="prescribed-by">Registered: 2025-09-15 | Last Visit: 2025-10-10</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Dilani Perera</div>
                                <div class="medication-details">Email: dilani.p@email.com | Age: 29</div>
                                <div class="prescribed-by">Registered: 2025-09-10 | Last Visit: 2025-10-08</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge confirmed">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">View All Patients</button>
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
                                <div class="doctor-name">Malini Rajapaksa</div>
                                <div class="appointment-date">Email: malini.r@email.com</div>
                                <div class="prescribed-by">Reason: Invalid NIC document | Rejected: 2025-10-12</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge rejected">Rejected</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Chaminda Gunasekara</div>
                                <div class="appointment-date">Phone: +94 76 456 7890</div>
                                <div class="prescribed-by">Reason: Document verification failed | Rejected: 2025-10-08</div>
                            </div>
                            <div class="appointment-status">
                                <span class="status-badge rejected">Rejected</span>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="appointment-info">
                                <div class="doctor-name">Tharaka Amarasinghe</div>
                                <div class="appointment-date">Email: tharaka.a@email.com</div>
                                <div class="prescribed-by">Reason: Incomplete information | Rejected: 2025-10-05</div>
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

                <!-- Inactive/Suspended Patients Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Inactive/Suspended Accounts</h2>
                    </div>
                    <div class="section-content">
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Sunil Wijeratne</div>
                                <div class="medication-details">Phone: +94 77 567 8901 | Age: 51</div>
                                <div class="prescribed-by">Reason: Account deactivated by user | Date: 2025-09-25</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge pending">Inactive</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Yasmin Fonseka</div>
                                <div class="medication-details">Email: yasmin.f@email.com | Age: 38</div>
                                <div class="prescribed-by">Reason: Policy violation - spam messaging | Date: 2025-10-01</div>
                            </div>
                            <div class="medication-date">
                                <span class="status-badge scheduled">Suspended</span>
                            </div>
                        </div>
                        <div class="medication-item">
                            <div class="medication-info">
                                <div class="medication-name">Kumar Dissanayake</div>
                                <div class="medication-details">Phone: +94 71 678 9012 | Age: 62</div>
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
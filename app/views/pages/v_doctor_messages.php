<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'doctorMessages';
?>



<div class="dashboard-container">        <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/doctorSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/doctorHeader.php'; ?>

                <!-- Content Sections Row -->
                <div class="content-sections">
                    <!-- Appointments Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Upcoming Appointments</h2>
                        </div>
                        <div class="section-content">
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">Dr. Sarah Johnson</div>
                                    <div class="appointment-date">2024-01-15 at 10:00 AM</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge scheduled">Scheduled</span>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">Dr. Michael Chen</div>
                                    <div class="appointment-date">2024-01-20 at 2:30 PM</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge confirmed">Confirmed</span>
                                </div>
                            </div>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">Dr. Emily Davis</div>
                                    <div class="appointment-date">2024-01-25 at 11:15 AM</div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                        </div>
                        <div class="section-footer">
                            <button class="action-button">Book New Appointment</button>
                        </div>
                    </div>

                    <!-- Medical Status Section -->
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Current Medications</h2>
                        </div>
                        <div class="section-content">
                            <div class="medication-item">
                                <div class="medication-info">
                                    <div class="medication-name">Hypertension</div>
                                    <div class="medication-details">Lisinopril - 10mg, Once daily</div>
                                    <div class="prescribed-by">Prescribed by Dr. Sarah Johnson</div>
                                </div>
                                <div class="medication-date">2024-01-10</div>
                            </div>
                            <div class="medication-item">
                                <div class="medication-info">
                                    <div class="medication-name">Diabetes</div>
                                    <div class="medication-details">Metformin - 500mg, Twice daily</div>
                                    <div class="prescribed-by">Prescribed by Dr. Michael Chen</div>
                                </div>
                                <div class="medication-date">2024-01-05</div>
                            </div>
                            <div class="medication-item">
                                <div class="medication-info">
                                    <div class="medication-name">Cholesterol</div>
                                    <div class="medication-details">Atorvastatin - 20mg, Once daily</div>
                                    <div class="prescribed-by">Prescribed by Dr. Sarah Johnson</div>
                                </div>
                                <div class="medication-date">2023-12-28</div>
                            </div>
                        </div>
                        <div class="section-footer">
                            <button class="action-button secondary">View All Prescriptions</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>
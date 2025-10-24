<?php 
require APPROOT.'/views/inc/header.php'; 
$current_page = 'patientProfile';
?>
<link rel='stylesheet' href='<?php echo URLROOT; ?>/css/components/profile/patient_profile.css'>

<div class="dashboard-container patient">
    <!-- Sidebar Navigation -->
    <?php require APPROOT.'/views/inc/components/patientSidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Header -->
        <?php require APPROOT.'/views/inc/components/patientHeader.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Cards Row -->
            <div class="stats-row">
                <div class="stat-card primary">
                    <div class="stat-content">
                        <h3 class="stat-title">Total Appointments</h3>
                        <div class="stat-number">24</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Active Prescriptions</h3>
                        <div class="stat-number">3</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Medical Reports</h3>
                        <div class="stat-number">8</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">Doctors Consulted</h3>
                        <div class="stat-number">5</div>
                    </div>
                </div>
            </div>

            <!-- Content Sections Row -->
            <div class="content-sections">
                <!-- Profile Information Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Profile Information</h2>
                    </div>
                    <div class="section-content">
                        <div class="profile-info-item">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value"><?php echo htmlspecialchars($data['user_name']); ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($data['user_email']); ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Patient ID:</div>
                            <div class="info-value"><?php echo $data['user_id']; ?></div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Account Status:</div>
                            <div class="info-value">
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Member Since:</div>
                            <div class="info-value"><?php echo date('M Y'); ?></div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button">Edit Profile</button>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Medical Information</h2>
                    </div>
                    <div class="section-content">
                        <div class="profile-info-item">
                            <div class="info-label">Blood Type:</div>
                            <div class="info-value">
                                <span class="blood-type-badge">O+</span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Date of Birth:</div>
                            <div class="info-value">January 15, 1990</div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Emergency Contact:</div>
                            <div class="info-value">+1 (555) 123-4567</div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Insurance Provider:</div>
                            <div class="info-value">HealthCare Plus</div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-label">Allergies:</div>
                            <div class="info-value">
                                <span class="allergy-badge">Penicillin</span>
                                <span class="allergy-badge">Shellfish</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button primary">Update Medical Info</button>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="content-sections">
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Recent Activity</h2>
                    </div>
                    <div class="section-content">
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-title">Appointment Completed</div>
                                <div class="activity-details">Consultation with Dr. Sarah Johnson</div>
                                <div class="activity-date">2 days ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-title">New Prescription</div>
                                <div class="activity-details">Lisinopril 10mg prescribed by Dr. Johnson</div>
                                <div class="activity-date">3 days ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-info">
                                <div class="activity-title">Lab Results Available</div>
                                <div class="activity-details">Blood work results from General Hospital</div>
                                <div class="activity-date">1 week ago</div>
                            </div>
                        </div>
                    </div>
                    <div class="section-footer">
                        <button class="action-button primary">View All Records</button>
                    </div>
                </div>

                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Add Payment Method</h2>
                    </div>

                    <div class="section-content payment-section-content">
                        <div class="payment-card-icons">
                            <div class="payment-icon"><img src="<?php echo URLROOT; ?>/img/visa.png" alt="Visa"></div>
                            <div class="payment-icon"><img src="<?php echo URLROOT; ?>/img/mastercard.png" alt="MasterCard"></div>
                        </div>

                        <div class="payment-field">
                            <label class="payment-label">Cardholder Name</label>
                            <input type="text" class="payment-input" placeholder="John Doe">
                        </div>

                        <div class="payment-field">
                            <label class="payment-label">Card Number</label>
                            <input type="text" class="payment-input" placeholder="1234 5678 9012 3456">
                        </div>

                        <div class="payment-row">
                            <div class="payment-field">
                                <label class="payment-label">Expiry Date</label>
                                <input type="text" class="payment-input" placeholder="MM/YY">
                            </div>
                            <div class="payment-field">
                                <label class="payment-label">CVV</label>
                                <input type="text" class="payment-input" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <div class="section-footer">
                        <button class="action-button">Add Card</button>
                    </div>
                </div>


            </div>
        </div>
    </main>
</div>



<?php require APPROOT.'/views/inc/footer.php'; ?>
<?php require APPROOT.'/views/inc/header.php'; ?>




<div class="dashboard-container admin">        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <span class="logo-text">MEDILINK</span>
                </div>
            </div>
            
            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    Dashboard
                </a>
                <a href="#" class="nav-item">
                    Appointments
                </a>
                <a href="#" class="nav-item">
                    Prescriptions
                </a>
                <a href="#" class="nav-item">
                    Messages
                </a>
                <a href="#" class="nav-item">
                    Medical Records
                </a>
                <a href="#" class="nav-item">
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <h1 class="page-title">Admin</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <span class="avatar-icon">👤</span>
                        </div>
                        <div class="user-details">
                            <span class="user-name">John Smith</span>
                            <span class="user-id">ID: 23545</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card primary">
                        <div class="stat-content">
                            <h3 class="stat-title">Upcoming Appointments</h3>
                            <div class="stat-number">1</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Active Medications</h3>
                            <div class="stat-number">3</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Unread Messages</h3>
                            <div class="stat-number">$56456.00</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3 class="stat-title">Medical Reports</h3>
                            <div class="stat-number">$26456.00</div>
                        </div>
                    </div>
                </div>

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
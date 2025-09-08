<?php require APPROOT.'/views/inc/header.php'; ?>
<!-- TOP NAVIGATION -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<!-- <h1>Index Page</h1>
<h1>Welcome <?php echo $_SESSION['user_name'];?></h1> -->

<!-- Homepage specific CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/homepage/homepage_style.css">

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-brand">
                <svg class="header-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="header-brand-name">MediLink</span>
            </div>
            <nav class="header-navigation">
                <a href="index.html">Home</a>
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
                <a href="#docs" class="active">For Providers</a>
                <a href="#support">Support</a>
            </nav>
            <div class="header-actions">
                <button class="btn btn-secondary" id="login-btn">Sign In</button>
                <button class="btn btn-primary" id="register-btn">Get Started</button>
            </div>
        </div>
    </header>

    <div class="docs-layout">
        <!-- Sidebar -->
        <aside class="docs-sidebar">
            <div class="sidebar-content">
                <div class="search-container">
                    <input type="text" placeholder="Search for providers..." class="search-input" id="doc-search">
                </div>
                <nav class="sidebar-nav">
                    <div class="doc-section">
                        <h4 class="section-title">Getting Started</h4>
                        <ul class="nav-list">
                            <li><a href="#introduction" class="doc-link active">Platform Overview</a></li>
                            <li><a href="#setup" class="doc-link">Setup Your Practice</a></li>
                            <li><a href="#verification" class="doc-link">Provider Verification</a></li>
                        </ul>
                    </div>
                    
                    <div class="doc-section">
                        <h4 class="section-title">Core Features</h4>
                        <ul class="nav-list">
                            <li><a href="#video-consultations" class="doc-link">Video Consultations</a></li>
                            <li><a href="#e-prescriptions" class="doc-link">E-Prescriptions</a></li>
                            <li><a href="#messaging" class="doc-link">Patient Messaging</a></li>
                            <li><a href="#patient-management" class="doc-link">Patient Management</a></li>
                        </ul>
                    </div>

                    <div class="doc-section">
                        <h4 class="section-title">Practice Management</h4>
                        <ul class="nav-list">
                            <li><a href="#appointments" class="doc-link">Appointment Scheduling</a></li>
                            <li><a href="#billing" class="doc-link">Billing & Payments</a></li>
                            <li><a href="#records" class="doc-link">Medical Records</a></li>
                            <li><a href="#reports" class="doc-link">Reports & Analytics</a></li>
                        </ul>
                    </div>

                    <div class="doc-section">
                        <h4 class="section-title">Support</h4>
                        <ul class="nav-list">
                            <li><a href="#training" class="doc-link">Training Resources</a></li>
                            <li><a href="#troubleshooting" class="doc-link">Troubleshooting</a></li>
                            <li><a href="#compliance" class="doc-link">HIPAA Compliance</a></li>
                            <li><a href="#contact" class="doc-link">Contact Support</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="docs-main" id="doc-content">
            <!-- Introduction Section -->
            <section id="introduction" class="doc-section-content">
                <h1 class="docs-title">MediLink Platform for Healthcare Providers</h1>
                <p class="docs-subtitle">
                    Transform your practice with our comprehensive virtual healthcare platform. Connect with patients, manage consultations, and deliver care beyond distance.
                </p>

                <div class="docs-cards">
                    <div class="docs-card">
                        <div class="card-header">
                            <h3 class="card-title">Setup Your Practice</h3>
                            <p class="card-description">Get started in minutes</p>
                        </div>
                        <div class="card-content">
                            <p class="card-text">
                                Complete setup guide to configure your practice profile and start seeing patients.
                            </p>
                            <a href="#setup" class="btn btn-primary">Begin Setup</a>
                        </div>
                    </div>

                    <div class="docs-card">
                        <div class="card-header">
                            <h3 class="card-title">Feature Guide</h3>
                            <p class="card-description">Master all platform features</p>
                        </div>
                        <div class="card-content">
                            <p class="card-text">
                                Learn how to use video consultations, e-prescriptions, and patient messaging effectively.
                            </p>
                            <a href="#video-consultations" class="btn btn-secondary">Explore Features</a>
                        </div>
                    </div>
                </div>

                <h2 class="section-heading">Platform Capabilities</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="feature-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Video Consultations</h3>
                        <p class="feature-description">High-quality video calls with patients, screen sharing for medical images, and secure recording.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="feature-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">E-Prescriptions</h3>
                        <p class="feature-description">Digital prescription management with drug interaction checks and pharmacy integration.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="feature-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 8H7"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 12H7"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Patient Messaging</h3>
                        <p class="feature-description">HIPAA-compliant messaging system for secure patient communication and follow-up care.</p>
                    </div>
                </div>
            </section>


        </main>
    </div>
    <script src="<?php echo URLROOT; ?>/public/js/main.js"></script>
    <script src="<?php echo URLROOT; ?>/public/js/docs.js"></script>
    <script src="<?php echo URLROOT; ?>/public/js/complete-functionality.js"></script>


<?php require APPROOT.'/views/inc/footer.php'; ?>

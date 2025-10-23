<?php require APPROOT.'/views/inc/header.php'; ?>


<!-- TOP NAVIGATION
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?> -->


<!-- <h1>Index Page</h1>
<h1>Welcome <?php echo $_SESSION['user_name'];?></h1> -->

<!-- Homepage specific CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a class="logo-text">MEDILINK</a>
                </div>
                
                <nav class="nav" id="mainNav">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#how-it-works" class="nav-link">How It Works</a>
                    <a href="#for-providers" class="nav-link">For Providers</a>
                    <a href="#resources" class="nav-link">Resources</a>
                </nav>

                <div class="header-actions">
                    <a class="btn btn-primary" href ="<?php echo URLROOT; ?>/Users/register" >Get Started</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Connecting You with Healthcare Anytime, Anywhere</h1>
                <p class="hero-description">
                    Experience seamless virtual consultations with trusted doctors. Quality healthcare made simple, accessible, and secure for everyone.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">76%</div>
                    <p class="stat-label">Patients prefer virtual care</p>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <p class="stat-label">Consultations completed</p>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <p class="stat-label">Patient satisfaction rate</p>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <p class="stat-label">Healthcare access</p>
                </div>
                <div class="stat-item">
                    <div class="stat-number">20+</div>
                    <p class="stat-label">Registered GP clinis</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Comprehensive Healthcare at Your Fingertips</h2>
                <p class="section-description">
                    Our platform is designed with both patients and providers in mind, offering the perfect balance of security, performance, and ease of use.
                </p>
            </div>

            <div class="features-grid">
                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 7l-7 5 7 5V7z"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Video Consultations</h3>
                    <p class="card-description">Face-to-face consultations with certified doctors from the comfort of your home</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            HD video quality
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Screen sharing for medical images
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                    <h3 class="card-title">E-Prescriptions</h3>
                    <p class="card-description">Digital prescriptions sent instantly to your preferred pharmacy</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Instant delivery
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Drug interaction checks
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Appointment Booking</h3>
                    <p class="card-description">Schedule appointments 24/7 with real-time availability updates</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Flexible scheduling
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Easy rescheduling
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Consultation Reminders</h3>
                    <p class="card-description">Never miss an appointment with automated reminders and prescription alerts</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            SMS & email notifications
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Medication reminders
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Secure Messaging</h3>
                    <p class="card-description">Private, encrypted communication with your healthcare providers</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            End-to-end encryption
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Quick responses
                        </li>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Privacy Compliant</h3>
                    <p class="card-description">HIPAA compliant with bank-level encryption protecting your medical data</p>
                    <ul class="feature-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            HIPAA certified
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Secure data storage
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get to Doctor in 3 clicks</h2>
                <p class="section-description">Follow these steps to start integrating with MEDILINK</p>
            </div>

            <div class="steps-grid">
                <div class="card">
                    <div class="step-number">1</div>
                    <h3 class="card-title">Register</h3>
                    <p class="card-description">Create your account in minutes</p>
                    <p class="card-text">
                        Sign up for a free account to access our platform, schedule appointments, and connect with healthcare providers.
                    </p>
                </div>

                <div class="card">
                    <div class="step-number">2</div>
                    <h3 class="card-title">Choose Your Doctor</h3>
                    <p class="card-description">Browse profiles and select your preferred healthcare provider</p>
                    <p class="card-text">
                        Explore our comprehensive directory of verified doctors and specialists. Read reviews and check availability.
                    </p>
                </div>

                <div class="card">
                    <div class="step-number">3</div>
                    <h3 class="card-title">Start Consultation</h3>
                    <p class="card-description">Connect via video call and receive care instantly</p>
                    <p class="card-text">
                        Use our secure video platform to consult with your doctor. Get prescriptions and follow-up care immediately.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Serve Section -->
    <section id="for-providers" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Healthcare Solutions for Everyone</h2>
                <p class="section-description">
                    Whether you're a patient, doctor, or clinic, MEDILINK has the right solution for you
                </p>
            </div>

            <div class="audience-grid">
                <div class="card card-centered">
                    <div class="audience-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="card-title">For Patients</h3>
                    <ul class="audience-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Easy access to qualified doctors</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>No waiting rooms or travel time</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Affordable consultations</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Secure medical records</span>
                        </li>
                    </ul>
                </div>

                <div class="card card-centered">
                    <div class="audience-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3 class="card-title">For Doctors</h3>
                    <ul class="audience-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Efficient workflow management</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Flexible scheduling</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Secure patient records</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Integrated e-prescriptions</span>
                        </li>
                    </ul>
                </div>

                <div class="card card-centered">
                    <div class="audience-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <h3 class="card-title">For Clinics</h3>
                    <ul class="audience-list">
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Complete practice management</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Multi-doctor coordination</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Integrated patient care</span>
                        </li>
                        <li>
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Analytics and reporting</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Section -->
    <section id="resources" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Helpful Resources to Get You Started</h2>
                <p class="section-description">Everything you need to know about using MEDILINK effectively</p>
            </div>

            <div class="resources-grid">
                <div class="card">
                    <h3 class="card-title">Documentation</h3>
                    <p class="card-description">Comprehensive guides and references</p>
                    <p class="card-text">
                        Explore our detailed documentation, including guides, best practices, and FAQs for patients and providers.
                    </p>
                    <button class="btn btn-outline btn-full">
                        View Documentation
                        <svg class="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <div class="card">
                    <h3 class="card-title">Training & Tutorials</h3>
                    <p class="card-description">Learn how to use the platform</p>
                    <p class="card-text">
                        Watch video tutorials and access training resources to get the most out of MEDILINK's features.
                    </p>
                    <button class="btn btn-outline btn-full">
                        Access Training
                        <svg class="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <div class="card">
                    <h3 class="card-title">Community & Support</h3>
                    <p class="card-description">Get help when you need it</p>
                    <p class="card-text">
                        Join our community forum, access support resources, and connect with our dedicated support team.
                    </p>
                    <button class="btn btn-outline btn-full">
                        Visit Support
                        <svg class="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-background"></div>
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Experience Better Healthcare?</h2>
                <p class="cta-description">Join thousands who've discovered convenient, quality medical care</p>
                <a href ="<?php echo URLROOT; ?>/Users/register"><button class="btn btn-large btn-cta">
                    Get Started
                    </button></a>
                </div>
            </div>
        </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-column">
                    <h4 class="footer-title">Platform</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#for-providers">For Providers</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4 class="footer-title">Resources</h4>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Training</a></li>
                        <li><a href="#">Community</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4 class="footer-title">Legal</h4>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Security</a></li>
                        <li><a href="#">Compliance</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>©️ 2025 MEDILINK. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>


    


    <!-- <script src="<?php echo URLROOT; ?>/public/js/main.js"></script>
    <script src="<?php echo URLROOT; ?>/public/js/docs.js"></script>
    <script src="<?php echo URLROOT; ?>/public/js/complete-functionality.js"></script> -->


<?php require APPROOT.'/views/inc/footer.php'; ?>

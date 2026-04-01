<?php require APPROOT.'/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">

<header class="header">
	<div class="container">
		<div class="header-content">
			<div class="logo">
				<a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MEDILINK</a>
			</div>

			<nav class="nav" id="mainNav">
				<a href="<?php echo URLROOT; ?>/Pages/index#features" class="nav-link">Features</a>
				<a href="<?php echo URLROOT; ?>/Pages/index#how-it-works" class="nav-link">How It Works</a>
				<a href="<?php echo URLROOT; ?>/Pages/index#for-providers" class="nav-link">For Providers</a>
			</nav>

			<div class="header-actions">
				<a class="btn btn-primary" href="<?php echo URLROOT; ?>/Users/register">Get Started</a>
			</div>
		</div>
	</div>
</header>

<section class="hero">
	<div class="hero-background"></div>
	<div class="container">
		<div class="hero-content">
			<h1 class="hero-title">Oops, This Page Is Not Available</h1>
			<p class="hero-description">
				The page you requested could not be found, was moved, or is temporarily unavailable.
				You can go back to the homepage or continue to registration.
			</p>
			<div class="header-actions" style="justify-content:center; margin-top: 20px;">
				<a class="btn btn-primary" href="<?php echo URLROOT; ?>/Pages/index">Back to Home</a>
				<a class="btn btn-outline" href="<?php echo URLROOT; ?>/Users/register">Create Account</a>
			</div>
		</div>
	</div>
</section>

<section class="section section-alt">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Need Help Finding Something?</h2>
			<p class="section-description">Try one of these common destinations.</p>
		</div>

		<div class="steps-grid">
			<div class="card">
				<div class="step-number">1</div>
				<h3 class="card-title">Patient Registration</h3>
				<p class="card-description">Create a patient account and book appointments online.</p>
				<a class="btn btn-outline btn-full" href="<?php echo URLROOT; ?>/Users/register">Register</a>
			</div>

			<div class="card">
				<div class="step-number">2</div>
				<h3 class="card-title">User Login</h3>
				<p class="card-description">Already registered? Sign in to continue your care journey.</p>
				<a class="btn btn-outline btn-full" href="<?php echo URLROOT; ?>/Users/login">Login</a>
			</div>

			<div class="card">
				<div class="step-number">3</div>
				<h3 class="card-title">Go to Dashboard</h3>
				<p class="card-description">Return to the main landing page and navigate from there.</p>
				<a class="btn btn-outline btn-full" href="<?php echo URLROOT; ?>/Pages/index">Homepage</a>
			</div>
		</div>
	</div>
</section>

<footer class="footer">
	<div class="container">
		<div class="footer-bottom">
			<p>© 2026 MEDILINK. All rights reserved.</p>
		</div>
	</div>
</footer>

<?php require APPROOT.'/views/inc/footer.php'; ?>

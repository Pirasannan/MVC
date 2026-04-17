<?php require APPROOT.'/views/inc/header.php'; ?>

    <div class="form_container">
        <div class="logo">
            <a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MediLink</a>
        </div>

        <!-- Step indicator -->
        <div class="otp-steps">
            <div class="otp-step active">
                <span class="step-num">1</span>
                <span class="step-label">Email</span>
            </div>
            <div class="otp-step-line"></div>
            <div class="otp-step">
                <span class="step-num">2</span>
                <span class="step-label">OTP</span>
            </div>
            <div class="otp-step-line"></div>
            <div class="otp-step">
                <span class="step-num">3</span>
                <span class="step-label">New Password</span>
            </div>
        </div>

        <p class="otp-page-title">Forgot Your Password?</p>
        <p class="otp-page-subtitle">Enter the email address linked to your account and we'll send you a verification code.</p>

        <form action="<?php echo URLROOT; ?>/Users/forgotPassword" method="POST" class="form" novalidate>

            <div class="form-group <?php echo (!empty($data['email_err'])) ? 'error' : ''; ?>">
                <input
                    type="email"
                    name="email"
                    id="fp_email"
                    class="form-input"
                    value="<?php echo htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                    autocomplete="email"
                >
                <label class="form-label" for="fp_email">Email Address</label>
                <?php if(!empty($data['email_err'])): ?>
                    <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['email_err']); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-button">Send OTP</button>

            <div class="single_acc_link">
                <a class="alreadyhaveaccount" href="<?php echo URLROOT; ?>/Users/login">← Back to Login</a>
            </div>

        </form>
    </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>

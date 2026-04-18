<?php require APPROOT.'/views/inc/header.php'; ?>

    <div class="form_container">
        <div class="logo">
            <a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MediLink</a>
        </div>

        <!-- Step indicator -->
        <div class="otp-steps">
            <div class="otp-step done">
                <span class="step-num">✓</span>
                <span class="step-label">Email</span>
            </div>
            <div class="otp-step-line active-line"></div>
            <div class="otp-step done">
                <span class="step-num">✓</span>
                <span class="step-label">OTP</span>
            </div>
            <div class="otp-step-line active-line"></div>
            <div class="otp-step active">
                <span class="step-num">3</span>
                <span class="step-label">New Password</span>
            </div>
        </div>

        <p class="otp-page-title">Set a New Password</p>
        <p class="otp-page-subtitle">Choose a strong password — at least 7 characters.</p>

        <form action="<?php echo URLROOT; ?>/Users/resetPassword" method="POST" class="form" novalidate>

            <div class="form-row">
                <!-- New Password -->
                <div class="form-group <?php echo (!empty($data['password_err'])) ? 'error' : ''; ?>">
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="rp_password"
                            class="form-input"
                            placeholder="••••••••"
                            value=""
                            required
                            minlength="7"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('rp_password')">
                            <img src="<?php echo URLROOT; ?>/public/img/eye.png" alt="Show Password" id="toggleIcon-rp_password">
                        </button>
                        <label class="form-label" for="rp_password">New Password</label>
                    </div>
                    <?php if(!empty($data['password_err'])): ?>
                        <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['password_err']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div class="form-group <?php echo (!empty($data['confirm_password_err'])) ? 'error' : ''; ?>">
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            name="confirm_password"
                            id="rp_confirm"
                            class="form-input"
                            placeholder="••••••••"
                            value=""
                            required
                            minlength="7"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('rp_confirm')">
                            <img src="<?php echo URLROOT; ?>/public/img/eye.png" alt="Show Password" id="toggleIcon-rp_confirm">
                        </button>
                        <label class="form-label" for="rp_confirm">Confirm Password</label>
                    </div>
                    <?php if(!empty($data['confirm_password_err'])): ?>
                        <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['confirm_password_err']); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="submit-button">Reset Password</button>

        </form>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById('toggleIcon-' + inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.src   = '<?php echo URLROOT; ?>/public/img/view.png';
                icon.alt   = 'Hide Password';
            } else {
                input.type = 'password';
                icon.src   = '<?php echo URLROOT; ?>/public/img/eye.png';
                icon.alt   = 'Show Password';
            }
        }
    </script>

<?php require APPROOT.'/views/inc/footer.php'; ?>

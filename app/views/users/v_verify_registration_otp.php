<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="form_container">
    <div class="logo">
        <a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MediLink</a>
    </div>

    <p class="otp-page-title">Verify Your Email</p>
    <p class="otp-page-subtitle">
        We've sent a 6-digit verification code to
        <strong><?php echo htmlspecialchars($_SESSION['registration_otp_email'] ?? ''); ?></strong>.
        Please enter the code below to complete your registration.
        You have <span id="otpTimer" class="otp-timer">05:00</span> remaining.
    </p>

    <form action="<?php echo URLROOT; ?>/Users/verifyRegistrationOtp" method="POST" class="form" novalidate>

        <div class="form-group otp-input-group <?php echo (!empty($data['otp_err'])) ? 'error' : ''; ?>">
            <input type="text" name="otp" id="otp" class="form-input otp-input"
                value="<?php echo htmlspecialchars($data['otp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" maxlength="6"
                inputmode="numeric" pattern="\d{6}" autocomplete="one-time-code" required placeholder="••••••">
            <label class="form-label" for="otp">Verification Code</label>
            <?php if (!empty($data['otp_err'])): ?>
                <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['otp_err']); ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="submit-button">Verify & Create Account</button>

        <div class="single_acc_link">
            <a class="resend-otp" href="<?php echo URLROOT; ?>/Users/resendRegistrationOtp">Resend OTP</a>
        </div>

        <div class="single_acc_link">
            <a class="forgot_pass" href="<?php echo URLROOT; ?>/Users/register">Cancel</a>
        </div>

    </form>
</div>

<script>
    // ── 5-minute countdown ────
    (function () {
        const timerEl = document.getElementById('otpTimer');
        const redirectTo = <?php echo json_encode(URLROOT . '/Users/register'); ?>;
        if (!timerEl) return;

        let totalSeconds = 5 * 60; // 5 minutes

        function tick() {
            const m = Math.floor(totalSeconds / 60);
            const s = totalSeconds % 60;
            timerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

            if (totalSeconds <= 60) {
                timerEl.style.color = '#e74c3c'; // red warning under 1 min
            }

            if (totalSeconds <= 0) {
                timerEl.textContent = '00:00';
                window.location.href = redirectTo; // redirect back to register
                return;
            }

            totalSeconds--;
            setTimeout(tick, 1000);
        }
        tick();
    })();

    // ── Only allow digits in the OTP field ───────────────────────────────
    document.getElementById('otp').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
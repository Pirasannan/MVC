<?php require APPROOT . '/views/inc/header.php'; ?>

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
        <div class="otp-step active">
            <span class="step-num">2</span>
            <span class="step-label">OTP</span>
        </div>
        <div class="otp-step-line"></div>
        <div class="otp-step">
            <span class="step-num">3</span>
            <span class="step-label">New Password</span>
        </div>
    </div>

    <p class="otp-page-title">Enter Verification Code</p>
    <p class="otp-page-subtitle">
        A 6-digit code was sent to
        <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? ''); ?></strong>.
        You have <span id="otpTimer" class="otp-timer">05:00</span> to enter it.
    </p>

    <form action="<?php echo URLROOT; ?>/Users/verifyOtp" method="POST" class="form" novalidate>

        <div class="form-group otp-input-group <?php echo (!empty($data['otp_err'])) ? 'error' : ''; ?>">
            <input
                type="text"
                name="otp"
                id="otp"
                class="form-input otp-input"
                value="<?php echo htmlspecialchars($data['otp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                maxlength="6"
                inputmode="numeric"
                pattern="\d{6}"
                autocomplete="one-time-code"
                required
                placeholder="••••••"
            >
            <label class="form-label" for="otp">6-Digit OTP</label>
            <?php if (!empty($data['otp_err'])): ?>
                <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['otp_err']); ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="submit-button">Verify OTP</button>

        <div class="account-links">
            <a class="create_acc" href="<?php echo URLROOT; ?>/Users/forgotPassword">← Change Email</a>
            <a class="forgot_pass" href="<?php echo URLROOT; ?>/Users/forgotPassword">Resend OTP</a>
        </div>

    </form>
</div>

<script>
    // ── 5-minute countdown — auto-redirects to Step 1 when time is up ────
    (function () {
        const timerEl   = document.getElementById('otpTimer');
        const redirectTo = '<?php echo URLROOT; ?>/Users/forgotPassword';
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
                window.location.href = redirectTo; // ← hard redirect back to email step
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
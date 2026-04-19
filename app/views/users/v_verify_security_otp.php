<?php
require APPROOT . '/views/inc/header.php';
$role = $_SESSION['user_role'] ?? 'patient';
$cancelUrl = URLROOT . '/Pages/' . $role . 'Profile';
?>

<div class="form_container">
    <div class="logo">
        <a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MediLink</a>
    </div>

    <p class="otp-page-title">Identity Verification</p>
    <p class="otp-page-subtitle">
        To proceed with your request, we sent a 6-digit code to
        <strong><?php echo htmlspecialchars($_SESSION['security_email'] ?? ''); ?></strong>.
        You have <span id="otpTimer" class="otp-timer">05:00</span> to enter it.
    </p>

    <form action="<?php echo URLROOT; ?>/Users/verifySecurityOtp" method="POST" class="form" novalidate>

        <div class="form-group otp-input-group <?php echo (!empty($data['otp_err'])) ? 'error' : ''; ?>">
            <input type="text" name="otp" id="otp" class="form-input otp-input"
                value="<?php echo htmlspecialchars($data['otp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" maxlength="6"
                inputmode="numeric" pattern="\d{6}" autocomplete="one-time-code" required placeholder="••••••">
            <label class="form-label" for="otp">6-Digit OTP</label>
            <?php if (!empty($data['otp_err'])): ?>
                <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['otp_err']); ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="submit-button">Confirm Identification</button>

        <div class="single_acc_link">
            <a class="resend-otp"
                href="<?php echo URLROOT; ?>/Users/securityOtp?action=<?php echo $_SESSION['security_action']; ?>">Resend
                OTP</a>
        </div>

        <div class="single_acc_link">
            <a class="forgot_pass" href="<?php echo htmlspecialchars($cancelUrl, ENT_QUOTES, 'UTF-8'); ?>">Cancel</a>
        </div>

    </form>
</div>

<script>
    // ── 5-minute countdown ────
    (function () {
        const timerEl = document.getElementById('otpTimer');
        const redirectTo = '<?php echo htmlspecialchars($cancelUrl, ENT_QUOTES, 'UTF-8'); ?>';
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
                window.location.href = redirectTo; // redirect back to profile
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
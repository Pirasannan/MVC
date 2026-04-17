<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- TOP NAVIGATION -->
<!-- <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?> -->
<!-- <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?> -->

<div class="form_container">
  <div class="logo">
    <a class="logo-text" href="<?php echo URLROOT; ?>/Pages/index">MediLink</a>
  </div>

  <?php if (!empty($_SESSION['password_reset_success'])): ?>
    <div class="otp-success-notice" role="alert">
      Password reset successfully! You can now log in with your new password.
    </div>
    <?php unset($_SESSION['password_reset_success']); ?>
  <?php endif; ?>


  <form action="<?php echo URLROOT; ?>/Users/login" method="POST" class="form" novalidate>
    <div class="form-group <?php echo (!empty($data['email_err'])) ? 'error' : ''; ?>">
      <input type="email" name="email" id="email" class="form-input"
        value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
      <label class="form-label" for="email">Email</label>
      <?php if (!empty($data['email_err'])): ?>
        <span class="Form-Invalid" role="alert"><?php echo $data['email_err']; ?></span>
      <?php endif; ?>
    </div>

    <div class="form-group <?php echo (!empty($data['password_err'])) ? 'error' : ''; ?>">
      <input type="password" name="password" id="password" class="form-input" required>
      <label class="form-label" for="password">Password</label>
      <?php if (!empty($data['password_err'])): ?>
        <span class="Form-Invalid" role="alert"><?php echo $data['password_err']; ?></span>
      <?php endif; ?>
    </div>

    <button type="submit" class="submit-button">Login</button>
    <div class="account-links">
      <a class="create_acc" href="<?php echo URLROOT; ?>/Users/register">Create Account</a>
      <a class="forgot_pass" href="<?php echo URLROOT; ?>/Users/forgotPassword">Forgot Password ?</a>
      <!-- have to link proper file and page */ -->
    </div>

  </form>
</div>


<script>
  function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('toggleIcon-' + inputId);
    if (input.type === 'password') {
      input.type = 'text';
      icon.src = '<?php echo URLROOT; ?>/public/img/view.png';
      icon.alt = 'Hide Password';
    } else {
      input.type = 'password';
      icon.src = '<?php echo URLROOT; ?>/public/img/eye.png';
      icon.alt = 'Show Password';
    }
  }


</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>
<?php require APPROOT.'/views/inc/header.php'; ?>

<?php
// set default role to 'doctor' when not provided by controller/data
$role = (isset($data['role']) && !empty($data['role'])) ? $data['role'] : 'doctor';
?>

<!-- TOP NAVIGATION -->
    <!-- <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?> -->

    <div class="form_container">
        <div class="logo">
        <a class="logo-text" href= "<?php echo URLROOT; ?>/Pages/index">MediLink</a>
        </div>
        
    <form class="form signup-form" action="<?php echo URLROOT?>/Users/register" method="POST" novalidate>
            <!-- Account Selection (Embedded) -->
            <div class="account-selection-container <?php echo (!empty($data['role_err'])) ? 'error' : ''; ?>">
                <div class="account-options-compact">
                    <div class="account-option-compact <?php echo ($role == 'doctor') ? 'selected' : ''; ?>" data-type="Doctor">
                        <span class="option-title-compact">Doctor</span>
                    </div>
                    <div class="account-option-compact <?php echo ($role == 'patient') ? 'selected' : ''; ?>" data-type="Patient">
                        <span class="option-title-compact">Patient</span>
                    </div>
                    <div class="account-option-compact <?php echo ($role == 'admin') ? 'selected' : ''; ?>" data-type="Admin">
                        <span class="option-title-compact">Admin</span>
                    </div>
                </div>
                <input type="hidden" name="role" id="roleInput" value="<?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if(!empty($data['role_err'])): ?>
                    <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['role_err'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group <?php echo (!empty($data['email_err'])) ? 'error' : ''; ?>">
                <input 
                    type="email" 
                    name="email"
                    id="email" 
                    class="form-input" 
                    placeholder="example@example.com"
                    value="<?php echo htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
                <label class="form-label" for="email">Email</label>
                <?php if(!empty($data['email_err'])): ?>
                    <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['email_err'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group <?php echo (!empty($data['name_err'])) ? 'error' : ''; ?>">
                     <input 
                         type="text" 
                         name="name"
                         id="name" 
                         class="form-input" 
                         value="<?php echo htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                         required
                     >
                     <label class="form-label" for="name">Name</label>
                     <?php if(!empty($data['name_err'])): ?>
                        <span class="Form-Invalid"><?php echo htmlspecialchars($data['name_err'], ENT_QUOTES, 'UTF-8'); ?></span>
                     <?php endif; ?>
                 </div>
             </div>
 
            <div id="slmcContainer" class="form-group <?php echo (!empty($data['slmc_err'])) ? 'error' : ''; ?>" style="<?php echo ($role == 'doctor') ? 'display: block;' : 'display: none;'; ?>">
                 <input 
                     type="text" 
                     name="slmc"
                     id="slmc" 
                     class="form-input" 
                     pattern="[0-9]+" 
                     value="<?php echo htmlspecialchars($data['slmc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                     <?php echo ($role == 'doctor') ? 'required' : ''; ?>
                 >
                 <label class="form-label" for="slmc">SLMC Number</label>
                 <?php if(!empty($data['slmc_err'])): ?>
                    <span class="Form-Invalid"><?php echo htmlspecialchars($data['slmc_err'], ENT_QUOTES, 'UTF-8'); ?></span>
                 <?php endif; ?>
             </div>
            
            <div class="form-row">
                 <div class="form-group <?php echo (!empty($data['password_err'])) ? 'error' : ''; ?>">
                     <div class="password-input-wrapper">
                         <input 
                             type="password" 
                             name="password"
                             id="password" 
                             class="form-input" 
                             placeholder="••••••••"
                             value=""
                             required
                             minlength="7"
                         >
                         <button type="button" class="password-toggle" onclick="togglePassword('password')">
                             <img src="<?php echo URLROOT; ?>/public/img/eye.png" alt="Show Password" id="toggleIcon-password">
                         </button>
                         <label class="form-label" for="password">Create Password</label>
                     </div>
                     <?php if(!empty($data['password_err'])): ?>
                         <span class="Form-Invalid" role="alert"><?php echo $data['password_err']; ?></span>
                     <?php endif; ?>
                 </div>
                 
                 <div class="form-group <?php echo (!empty($data['confirm_password_err'])) ? 'error' : ''; ?>">
                     <div class="password-input-wrapper">
                         <input 
                             type="password" 
                             name="confirm_password"
                             id="confirm_password" 
                             class="form-input" 
                             placeholder="••••••••"
                             value=""
                             required
                             minlength="7"
                         >
                         <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                             <img src="<?php echo URLROOT; ?>/public/img/eye.png" alt="Show Password" id="toggleIcon-confirm_password">
                         </button>
                         <label class="form-label" for="confirm_password">Confirm Password</label>
                     </div>
                     <?php if(!empty($data['confirm_password_err'])): ?>
                        <span class="Form-Invalid" role="alert"><?php echo htmlspecialchars($data['confirm_password_err'], ENT_QUOTES, 'UTF-8'); ?></span>
                     <?php endif; ?>
                 </div>
             </div>
 
             <button type="submit" class="submit-button">Create Account</button>

             <div class="single_acc_link">
              <a class="alreadyhaveaccount" href="<?php echo URLROOT; ?>/Users/login">Already Have an Account ?</a>
             </div>

         </form>
     </div>

    
    <script>
        // Toggle password visibility
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

        // Handle account type selection and SLMC field visibility
        document.querySelectorAll('.account-option-compact').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.account-option-compact').forEach(opt => 
                    opt.classList.remove('selected')
                );
                this.classList.add('selected');
                
                // Update hidden input value
                const roleInput = document.getElementById('roleInput');
                const roleValue = this.dataset.type.toLowerCase();
                roleInput.value = roleValue;
                
                // Show/hide SLMC field based on selection
                const slmcContainer = document.getElementById('slmcContainer');
                const slmcInput = document.getElementById('slmc');
                if (this.dataset.type === 'Doctor') {
                    slmcContainer.style.display = 'block';
                    slmcInput.required = true;
                } else {
                    slmcContainer.style.display = 'none';
                    slmcInput.required = false;
                }
            });
        });

    </script>

<?php require APPROOT.'/views/inc/footer.php'; ?>


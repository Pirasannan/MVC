<?php require APPROOT.'/views/inc/header.php'; ?>
<!-- TOP NAVIGATION -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


            <div class="form_container">
            
            <div class="form:header">
                <center><h1>Sign up</h1></center>
                <p><b>Please fill me to register</b></p>
            </div>

            <form action="<?php echo URLROOT?>/Users/register" method="POST">



            <div class="form-input-title">Role</div>
<select name="role" id="role" class="role">
    <!-- <option value="">-- Select Role --</option> -->
    <option value="doctor" <?php echo ($data['role'] == 'doctor') ? 'selected' : ''; ?>>Doctor</option>
    <option value="patient" <?php echo ($data['role'] == 'patient') ? 'selected' : ''; ?>>Patient</option>
    <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
</select>
<span class="Form-Invalid"><?php echo $data['role_err']; ?></span>


                <!-- Name -->
                <div class="form-input-title">Name</div>
                <input type="text" name="name" id="name" class = "name " value = "<?php echo $data['name']?> ">
                <span class = "Form-Invalid"><?php echo $data['name_err']; ?></span>

                <!-- email -->
                <div class="form-input-title">Email</div>
                <input type="text" name="email" id="email" class="email" value="<?php echo $data['email']?>">
                <span class="Form-Invalid"><?php echo $data['email_err']; ?></span>
                  
                <!-- password -->
                <div class="form-input-title">Password</div>
                <input type="password" name="password" id="password" class="password" value="<?php echo $data['password']?>">
                <span class="Form-Invalid"><?php echo $data['password_err']; ?></span>

                <!-- confirm password -->
                <div class="form-input-title">Confirm password</div>
                <input type="password" name="confirm_password" id="confirm_password" class="confirm_password" value="<?php echo $data['confirm_password']?>">
                <span class="Form-Invalid"><?php echo $data['confirm_password_err']; ?></span>
                
                <br>
                <input type="submit" value="Register" class="form_btn">
            </form>
        </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>


<?php require APPROOT.'/views/inc/header.php'; ?>
<!-- TOP NAVIGATION -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


        <div class="form_container">
            <div class="form:header">
                <center><h1>User Login</h1></center>
                <p><b>Please fill correct credentials to Login</b></p>
            </div>
            <form action ="" method="POST">
            
                <!-- email -->
                <div class="form-input-title">Email</div>
                <input type="text" name="name" id="name" class = "name ">
                <span class = "Form Invalid"></span>
                  
                <!-- password -->
                  <div class="form-input-title">Password</div>
                <input type="password" name="confirm_password" id="confirm_password" class = "confirm_password">
                <span class = "Form Invalid"></span>

            
                
                <br>
                <input type="submit" value="Login" class="form_btn">
            </form>
        </div>

<?php require APPROOT.'/views/inc/footer.php'; ?>


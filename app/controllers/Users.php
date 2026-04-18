<?php 

class Users extends Controller {
    private $userModel; // there was error called undeclared property 
    
    public function __construct() {
        $this->userModel = $this->model('M_Users');
    }

    public function register()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // sanitize & collect
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => trim($_POST['role'] ?? 'doctor'),
                'slmc' => trim($_POST['slmc'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'confirm_password' => trim($_POST['confirm_password'] ?? ''),
                'name_err' => '',
                'email_err' => '',
                'slmc_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'role_err' => ''
            ];

            // basic validations...
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter an email.';
            } else {
                // check email exists in DB (example)
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken.';
                }
            }

            if(empty($data['name'])){
                $data['name_err'] = 'Please enter your name.';
            }

            // SLMC validation - only for doctors
            if($data['role'] === 'doctor'){
                if(empty($data['slmc'])){
                    $data['slmc_err'] = 'Please enter your SLMC number.';
                } elseif(!preg_match('/^[0-9]+$/', $data['slmc'])){
                    $data['slmc_err'] = 'SLMC must be numeric.';
                } else {
                    // Check if SLMC number already exists in Users table
                    if($this->userModel->findUserBySlmc($data['slmc'])){
                        $data['slmc_err'] = 'There is already a user with this SLMC number.';
                    } 
                    // Validate SLMC number against slmc table
                    elseif(!$this->userModel->validateSlmcNumber($data['slmc'])){
                        $data['slmc_err'] = 'Invalid SLMC number. Please check your registration number.';
                    }
                }
            } else {
                // For non-doctors, clear SLMC to avoid issues
                $data['slmc'] = null;
                $data['slmc_err'] = ''; // Clear any SLMC errors for non-doctors
            }

            // password validation
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter a password.';
            } elseif(strlen($data['password']) < 7){
                $data['password_err'] = 'Password must be at least 7 characters.';
            }

            // confirm password validation
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm your password.';
            } elseif($data['password'] !== $data['confirm_password']){
                $data['confirm_password_err'] = 'Passwords do not match.';
            }

            // if any errors, re-render view
            if(!empty($data['name_err']) || !empty($data['email_err']) || !empty($data['slmc_err']) || !empty($data['password_err']) || !empty($data['confirm_password_err'])){
                $this->view('users/v_register', $data);
                return;
            }

            // If we reach here, all validations passed
            // Hash the password now
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
            // Save registration data in session for post-OTP persistence
            $_SESSION['temp_registration_data'] = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'slmc' => $data['slmc'],
                'password' => $data['password']
            ];

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', time() + 15 * 60); // 15 mins

            // Save OTP to DB
            if($this->userModel->saveOtp($data['email'], $otp, $expiresAt)){
                // Send OTP via email
                $mailSent = Mailer::sendOtp($data['email'], $data['name'], $otp, 'register');

                if($mailSent){
                    $_SESSION['registration_otp_email'] = $data['email'];
                    redirect('Users/verifyRegistrationOtp');
                } else {
                    $data['email_err'] = 'Failed to send verification email. Please try again.';
                    $this->view('users/v_register', $data);
                }
            } else {
                die('Something went wrong. Please try again.');
            }
        }
        else {
            //initial form
            $data=[
                'role'=> '',
                'name' => '',
                'email' => '',
                'slmc' => '',
                'password' => '',
                'confirm_password' => '',
                'role_err' =>'',
                'name_err' => '',
                'email_err' => '',
                'slmc_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            //Load view
            $this->view('users/v_register', $data);

        }
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //validate the form
            $_POST = array_map(function($value) {
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }, $_POST);

            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                
                'email_err' => '',
                'password_err' => '',
            ];
            
            // Email validation
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter your email';
            }
            else{
                if($this->userModel->findUserByEmail($data['email'])){
                    // user is found
                }
                else{
                    //user is not found
                    $data['email_err'] = 'user not found';
                    $this->userModel->logLoginEvent(null, $data['email'], false, 'user_not_found', $ipAddress, $userAgent);
                }
            }
            
            // Password validation
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter your password';
            }
            
            // If no errors, do login
            if(empty($data['email_err']) && empty($data['password_err'])){
                //Log the user
                $loggeduser = $this->userModel->login($data['email'], $data['password']);

                if($loggeduser){
                    $role = strtolower($loggeduser->role ?? '');
                    $status = strtolower($loggeduser->status ?? 'active');

                    if(($role === 'patient' || $role === 'doctor') && $status === 'inactive'){
                        $data['password_err'] = 'Your account is deactivated. Please contact admin.';
                        $this->view('users/v_login', $data);
                        return;
                    }

                    //user is authenticated

                    $this->userModel->logLoginEvent($loggeduser->id, $loggeduser->email, true, '', $ipAddress, $userAgent);

                    //create user sessions      
                    $this->createUserSessions($loggeduser);
                    
                } else {
                    $existingUser = $this->userModel->getUserByEmail($data['email']);
                    $this->userModel->logLoginEvent($existingUser->id ?? null, $data['email'], false, 'invalid_password', $ipAddress, $userAgent);
                    $data['password_err'] = 'Password Incorrect';
                    
                    //load view with errors
                    $this->view('users/v_login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/v_login', $data);
            }
        } 
        else {
            //initial form
            $data = [
                'email' => '',
                'password' => '',
                
                'email_err' => '',
                'password_err' => '',
            ];
            
            //load view
            $this->view('users/v_login', $data);
        }
    }

    public function createUserSessions($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = strtolower($user->role ?? '');
        $_SESSION['user_slmc'] = $user->slmc ?? null;
        $_SESSION['user_status'] = strtolower($user->status ?? 'active');
        $_SESSION['user_profile_image'] = $this->userModel->getUserProfileImage($user->id);


        //Role based sessions
        switch($_SESSION['user_role']){
            case 'admin':
                redirect('Pages/adminDashboard');
                break;
            case 'doctor':
                redirect('Pages/doctorDashboard'); 
                break;
            case 'patient':
                redirect('Pages/patientDashboard'); 
                break;
            default:
                redirect('Pages/index');   

        }

    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_profile_image']);

        session_destroy();

        redirect('Users/login');  

    }

    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        }
        else{
            return false;
        }
    }

    // Show e-prescription form
    public function add() {
        $patients = $this->userModel->getPatients();
        $data = ['patients' => $patients];
        $this->view('pages/v_add_prescription', $data); // create this view file
    }


    // ──────────────────────────────────────────────────────────
    // FORGOT PASSWORD – 3-STEP OTP FLOW
    // ──────────────────────────────────────────────────────────

    /**
     * STEP 1: Ask for email, validate it exists, generate OTP & save to DB.
     * GET  /Users/forgotPassword  → show form
     * POST /Users/forgotPassword  → process email
     */
    public function forgotPassword(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'email'     => trim($_POST['email'] ?? ''),
                'email_err' => ''
            ];

            // Validate email field
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter your email address.';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Please enter a valid email address.';
            } else {
                // Check user exists in DB
                $user = $this->userModel->getUserByEmail($data['email']);
                if(!$user){
                    $data['email_err'] = 'No account found with that email address.';
                }
            }

            if(!empty($data['email_err'])){
                $this->view('users/v_forgot_password', $data);
                return;
            }

            // Generate cryptographically secure 6-digit OTP
            $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', time() + 15 * 60); // expires in 15 minutes

            // Save OTP to DB (clears any previous OTP for this email)
            if($this->userModel->saveOtp($data['email'], $otp, $expiresAt)){

                // Send OTP via PHPMailer
                $mailSent = Mailer::sendOtp(
                    $data['email'],
                    $user->name ?? $data['email'], // use their name if available
                    $otp
                );

                if(!$mailSent){
                    // Mail failed — show error on the same form
                    $data['email_err'] = 'Failed to send OTP email. Please check your mail settings or try again.';
                    $this->view('users/v_forgot_password', $data);
                    return;
                }

                // Store email in session so Step 2 & 3 can use it
                $_SESSION['reset_email']  = $data['email'];
                $_SESSION['otp_verified'] = false;

                redirect('Users/verifyOtp');

            } else {
                die('Something went wrong saving the OTP. Please try again.');
            }

        } else {
            $data = ['email' => '', 'email_err' => ''];
            $this->view('users/v_forgot_password', $data);
        }
    }

    /**
     * STEP 2: Show OTP input, validate OTP.
     * GET  /Users/verifyOtp → show form
     * POST /Users/verifyOtp → validate OTP
     */
    public function verifyOtp(){
        // Guard: must have gone through step 1 first
        if(empty($_SESSION['reset_email'])){
            redirect('Users/forgotPassword');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp   = trim($_POST['otp'] ?? '');
            $email = $_SESSION['reset_email'];

            $data = [
                'otp'     => $otp,
                'otp_err' => ''
            ];

            if(empty($otp)){
                $data['otp_err'] = 'Please enter the OTP.';
            } elseif(!preg_match('/^\d{6}$/', $otp)){
                $data['otp_err'] = 'OTP must be a 6-digit number.';
            } else {
                $result = $this->userModel->verifyOtp($email, $otp);
                if(!$result){
                    $data['otp_err'] = 'Invalid or expired OTP. Please try again.';
                } else {
                    // Mark OTP as used
                    $this->userModel->markOtpUsed($email, $otp);
                    $_SESSION['otp_verified'] = true;
                    redirect('Users/resetPassword');
                    return;
                }
            }

            $this->view('users/v_verify_otp', $data);

        } else {
            $data = ['otp' => '', 'otp_err' => ''];
            $this->view('users/v_verify_otp', $data);
        }
    }

    /**
     * STEP 3: Set new password.
     * GET  /Users/resetPassword → show form
     * POST /Users/resetPassword → update password
     */
    public function resetPassword(){
        // Guard: must have verified OTP in step 2
        if(empty($_SESSION['reset_email']) || empty($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true){
            redirect('Users/forgotPassword');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'password'              => trim($_POST['password'] ?? ''),
                'confirm_password'      => trim($_POST['confirm_password'] ?? ''),
                'password_err'          => '',
                'confirm_password_err'  => ''
            ];

            if(empty($data['password'])){
                $data['password_err'] = 'Please enter a new password.';
            } elseif(strlen($data['password']) < 7){
                $data['password_err'] = 'Password must be at least 7 characters.';
            }

            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm your new password.';
            } elseif($data['password'] !== $data['confirm_password']){
                $data['confirm_password_err'] = 'Passwords do not match.';
            }

            if(!empty($data['password_err']) || !empty($data['confirm_password_err'])){
                $this->view('users/v_reset_password', $data);
                return;
            }

            // Hash and save new password
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $email  = $_SESSION['reset_email'];

            if($this->userModel->updatePassword($email, $hashed)){
                $isProfileReset = $_SESSION['is_profile_reset'] ?? false;
                
                // Clean up all reset-related session vars
                unset($_SESSION['reset_email']);
                unset($_SESSION['otp_verified']);
                unset($_SESSION['is_profile_reset']);

                // If reset from profile, logout the user first
                if($isProfileReset){
                    session_destroy();
                }

                // Redirect to login with a success flag
                $_SESSION['password_reset_success'] = true;
                redirect('Users/login');
            } else {
                die('Something went wrong updating the password. Please try again.');
            }

        } else {
            $data = [
                'password'             => '',
                'confirm_password'     => '',
                'password_err'         => '',
                'confirm_password_err' => ''
            ];
            $this->view('users/v_reset_password', $data);
        }
    }

    /**
     * Security OTP - For Change Password or Deactivation when logged in
     * GET /Users/securityOtp?action=password|deactivate
     */
    public function securityOtp(){
        if(!isset($_SESSION['user_id'])){
            redirect('Users/login');
            return;
        }

        $action = $_GET['action'] ?? '';
        if($action !== 'password' && $action !== 'deactivate'){
            redirect('Pages/index');
            return;
        }

        $email = $_SESSION['user_email'];
        $user_name = $_SESSION['user_name'];

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', time() + 15 * 60);

        if($this->userModel->saveOtp($email, $otp, $expiresAt)){
            $mailSent = Mailer::sendOtp($email, $user_name, $otp);

            if($mailSent){
                $_SESSION['security_email'] = $email;
                $_SESSION['security_action'] = $action;
                $_SESSION['security_verified'] = false;
                redirect('Users/verifySecurityOtp');
            } else {
                $role = $_SESSION['user_role'];
                $redirectPage = $role . 'Profile';
                redirect('Pages/' . $redirectPage . '?error=mail_failed');
            }
        }
    }

    /**
     * Verify Security OTP
     */
    public function verifySecurityOtp(){
        if(empty($_SESSION['security_email']) || empty($_SESSION['security_action'])){
            redirect('Pages/index');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp = trim($_POST['otp'] ?? '');
            $email = $_SESSION['security_email'];
            $action = $_SESSION['security_action'];

            $data = ['otp' => $otp, 'otp_err' => ''];

            if($this->userModel->verifyOtp($email, $otp)){
                $this->userModel->markOtpUsed($email, $otp);
                $_SESSION['security_verified'] = true;

                if($action === 'password'){
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['otp_verified'] = true;
                    $_SESSION['is_profile_reset'] = true;
                    unset($_SESSION['security_email']);
                    unset($_SESSION['security_action']);
                    unset($_SESSION['security_verified']);
                    redirect('Users/resetPassword');
                } else {
                    if($this->userModel->updateUserStatus($_SESSION['user_id'], 'inactive')){
                        unset($_SESSION['security_email']);
                        unset($_SESSION['security_action']);
                        unset($_SESSION['security_verified']);
                        $this->logout();
                    }
                }
            } else {
                $data['otp_err'] = 'Invalid or expired OTP.';
                $this->view('users/v_verify_security_otp', $data);
            }
        } else {
            $data = ['otp' => '', 'otp_err' => ''];
            $this->view('users/v_verify_security_otp', $data);
        }
    }
    /**
     * Verify Registration OTP and Finalize Account Creation
     */
    public function verifyRegistrationOtp(){
        if(empty($_SESSION['registration_otp_email']) || empty($_SESSION['temp_registration_data'])){
            redirect('Users/register');
            return;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $otp = trim($_POST['otp'] ?? '');
            $email = $_SESSION['registration_otp_email'];

            $data = ['otp' => $otp, 'otp_err' => ''];

            if($this->userModel->verifyOtp($email, $otp)){
                // OTP is valid
                $this->userModel->markOtpUsed($email, $otp);
                
                // Get the stored registration data
                $regData = $_SESSION['temp_registration_data'];
                
                // Set status based on role
                // admin, patient status - active
                // doctor status - unverified
                $role = strtolower($regData['role']);
                if($role === 'doctor'){
                    $regData['status'] = 'unverified';
                } else {
                    $regData['status'] = 'active';
                }

                // Finalize registration in DB
                if($this->userModel->register($regData)){
                    // Clear temporary data
                    unset($_SESSION['registration_otp_email']);
                    unset($_SESSION['temp_registration_data']);
                    
                    // Redirect to login with success message
                    $_SESSION['registration_success'] = true;
                    redirect('Users/login');
                } else {
                    die('Something went wrong during account creation.');
                }
            } else {
                $data['otp_err'] = 'Invalid or expired OTP. Please check your email and try again.';
                $this->view('users/v_verify_registration_otp', $data);
            }
        } else {
            $data = ['otp' => '', 'otp_err' => ''];
            $this->view('users/v_verify_registration_otp', $data);
        }
    }


}

?>
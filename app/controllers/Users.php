
<?php 

class Users extends Controller {
    private $userModel; // there was error called undeclared property 
    
    public function __construct() {
        $this->userModel = $this->model('M_Users');
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] =='POST'){
            //form is submitting
            //validate the data
            $_POST = array_map('htmlspecialchars', $_POST);

            //input data
            $data=[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),

                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',

            ];
        
                //validating each inputs

                //Name validation
            if(empty($data['name'])){   
                    $data['name_err'] = 'Please enter a Name';
            }

            //Email validation
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter a valid Email';
            }
            else{
                //email is registered or not
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }

            //password validation
            if(empty($data['password'])){ // check for password entered
                $data['password_err'] = 'Please enter the password';
            }
            else if(empty($data['confirm_password'])){ // check for confirm password entered
                $data['confirm_password_err'] = 'Please confirm the password';
            }
            else{
                if($data['password'] !== $data['confirm_password']){ //chekc both passwords matches
                    $data['confirm_password_err'] = 'Passwords do not match';

                }
            }
        

            //validation completed & No errors , then register users
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                //Hash the password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
            //Register the user
                if($this->userModel->register($data)){
                redirect('Users/login');
                }
                else{
                    die('Something went wrong');
                }
            }
            else{
                //Load view
                $this->view('users/v_register', $data);
            }
        }
        else {
            //initial form
            $data=[
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',

                'name_err' => '',
                'email_err' => '',
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
            $_POST = array_map('htmlspecialchars', $_POST);
            
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
                    //user is authenticated

                    //create user sessions      
                    $this->createUserSessions($loggeduser);
                    
                } else {
                    $data['password_err'] = 'Password Incorrect';
                    
                    //load view with errors
                    $this->view('users/v_login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/v_login', $data);
            }
        } else {
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

        redirect('Pages/index');  

    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);

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

}

?>
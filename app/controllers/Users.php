
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
                die('User is Registered');
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
        $data = [];

        $this->view('users/v_login',$data);

    }
}

?>
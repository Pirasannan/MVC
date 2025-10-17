<?php 
    class M_Users {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }
        
        public function register($data){
            $this->db->query('INSERT INTO Users(role,name, email, password) VALUES (:role, :name, :email, :password)');
            $this->db->bind('role', $data['role']);
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':email',$data['email']);
            $this->db->bind(':password',$data['password']);

            if($this->db->execute()){
                return true;
            }
            else{
                return false;
            }
            

        }
        public function findUserByEmail($email){
            $this->db->query('SELECT * FROM Users WHERE email = :email');
            $this->db->bind(':email',$email);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }

        }

        // Login User
        public function login($email, $password){
            $this->db->query('SELECT * FROM Users WHERE email = :email');
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            if($row){
                $hashed_password = $row->password;
                if(password_verify($password, $hashed_password)){
                    return $row;
                }
            }
            return false;
        }
            
        
    }


    
?>
<?php 
    class M_Users {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }
        
        public function register($data){
            $this->db->query('INSERT INTO Users(role, name, email, password, slmc) VALUES (:role, :name, :email, :password, :slmc)');
            $this->db->bind(':role', $data['role']);
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':email',$data['email']);
            $this->db->bind(':password',$data['password']);
            $this->db->bind(':slmc', $data['slmc'] ?? null);

            if($this->db->execute()){
                return true;
            }
            else{
                return false;
            }
        }

        public function validateSlmcNumber($slmc){
            $this->db->query('SELECT * FROM slmc WHERE slmc = :slmc');
            $this->db->bind(':slmc',$slmc);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true; // SLMC number exists in slmc table
            }
            else{
                return false; // SLMC number does not exist in slmc table
            }
        }

        public function findUserBySlmc($slmc){
            $this->db->query('SELECT * FROM Users WHERE slmc = :slmc');
            $this->db->bind(':slmc', $slmc);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true; // SLMC number already used by another user
            }
            else{
                return false; // SLMC number is available
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

        //fetch all patients
        public function getPatients(){
            $this->db->query("SELECT id, name FROM Users WHERE role = 'patient' AND status = 'active'");
            return $this->db->resultSet(); //returns as array if patient objects
        }
            
        
    }


    
?>
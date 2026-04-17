<?php 
    class M_Users {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }

        private function tableExists($tableName){
            $this->db->query('SELECT COUNT(*) AS total FROM information_schema.tables WHERE table_schema = :db AND table_name = :table LIMIT 1');
            $this->db->bind(':db', DB_NAME);
            $this->db->bind(':table', $tableName);
            $result = $this->db->single();
            return isset($result->total) && (int)$result->total > 0;
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

        public function getUserByEmail($email){
            $this->db->query('SELECT * FROM Users WHERE email = :email');
            $this->db->bind(':email', $email);
            return $this->db->single();
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

        public function logLoginEvent($userId, $email, $success, $reason, $ipAddress, $userAgent){
            if (!$this->tableExists('activity_logs')) {
                return false;
            }

            $action = $success ? 'login_success' : 'login_failed';
            $description = $success ? 'Login success' : ('Login failed: ' . $reason);
            if (!empty($email)) {
                $description .= ' (' . $email . ')';
            }

            $this->db->query('INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) VALUES (:user_id, :action, :description, :ip_address, :user_agent, NOW())');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':action', $action);
            $this->db->bind(':description', $description);
            $this->db->bind(':ip_address', $ipAddress);
            $this->db->bind(':user_agent', $userAgent);
            return $this->db->execute();
        }

        //fetch all patients
        public function getPatients(){
            $this->db->query("SELECT id, name FROM Users WHERE role = 'patient' AND status = 'active'");
            return $this->db->resultSet(); //returns as array if patient objects
        }

        // Get user by ID
        public function getUserById($userId) {
            $this->db->query('SELECT id AS user_id, name AS user_name, LOWER(role) AS user_role, email FROM Users WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            return $this->db->single();
        }

        // Check if user is online (based on last activity within 5 minutes)
        public function isUserOnline($userId) {
            // Uses updated_at as activity signal in current Users schema.
            $this->db->query('SELECT updated_at AS last_activity FROM Users WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            
            if ($result && isset($result->last_activity)) {
                $lastActivity = strtotime($result->last_activity);
                $currentTime = time();
                // Consider online if active within last 5 minutes
                return ($currentTime - $lastActivity) < 300;
            }
            return false;
        }

        // Update user last activity
        public function updateLastActivity($userId) {
            $this->db->query('UPDATE Users SET updated_at = NOW() WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            return $this->db->execute();
        }

        // Search users for messaging (exclude current user)
        public function searchUsersForMessaging($searchTerm, $currentUserId) {
            $this->db->query('
                SELECT id AS user_id, name AS user_name, LOWER(role) AS user_role, email 
                FROM Users 
                WHERE (name LIKE :search OR email LIKE :search)
                AND id != :current_user_id
                AND status = "active"
                LIMIT 20
            ');
            $this->db->bind(':search', '%' . $searchTerm . '%');
            $this->db->bind(':current_user_id', $currentUserId);
            return $this->db->resultSet();
        }
            
        
    }


    
?>
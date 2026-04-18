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

        public function findUserByEmailExcludingUser($email, $userId){
            $this->db->query('SELECT id FROM Users WHERE email = :email AND id != :user_id LIMIT 1');
            $this->db->bind(':email', $email);
            $this->db->bind(':user_id', (int)$userId);

            $this->db->single();
            return $this->db->rowCount() > 0;
        }

        public function updateUserProfile($userId, $name, $email){
            $this->db->query('UPDATE Users SET name = :name, email = :email, updated_at = NOW() WHERE id = :user_id');
            $this->db->bind(':name', $name);
            $this->db->bind(':email', $email);
            $this->db->bind(':user_id', (int)$userId);

            return $this->db->execute();
        }

        public function updateUserName($userId, $name){
            $this->db->query('UPDATE Users SET name = :name, updated_at = NOW() WHERE id = :user_id');
            $this->db->bind(':name', $name);
            $this->db->bind(':user_id', (int)$userId);

            return $this->db->execute();
        }

        private function ensureUserProfileImageColumn(){
            $this->db->query("SELECT COUNT(*) AS column_count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'Users' AND COLUMN_NAME = 'profile_image'");
            $columnCheck = $this->db->single();

            if ((int)($columnCheck->column_count ?? 0) === 0) {
                $this->db->query('ALTER TABLE Users ADD COLUMN profile_image VARCHAR(255) NULL AFTER slmc');
                $this->db->execute();
            }
        }

        public function getUserProfileImage($userId){
            $this->ensureUserProfileImageColumn();

            $this->db->query('SELECT profile_image FROM Users WHERE id = :user_id LIMIT 1');
            $this->db->bind(':user_id', (int)$userId);
            $row = $this->db->single();

            return $row->profile_image ?? null;
        }

        public function updateUserProfileImage($userId, $profileImagePath){
            $this->ensureUserProfileImageColumn();

            $this->db->query('UPDATE Users SET profile_image = :profile_image, updated_at = NOW() WHERE id = :user_id');
            $this->db->bind(':profile_image', $profileImagePath);
            $this->db->bind(':user_id', (int)$userId);

            return $this->db->execute();
        }

        private function ensurePatientMedicalInfoTable(){
            $this->db->query('
                CREATE TABLE IF NOT EXISTS patient_medical_info (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    patient_id INT NOT NULL UNIQUE,
                    blood_type VARCHAR(8) DEFAULT NULL,
                    date_of_birth DATE DEFAULT NULL,
                    emergency_contact VARCHAR(50) DEFAULT NULL,
                    insurance_provider VARCHAR(255) DEFAULT NULL,
                    allergies TEXT DEFAULT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_patient_medical_info_patient_id (patient_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
            ');

            return $this->db->execute();
        }

        public function getPatientMedicalInfo($patientId){
            $this->ensurePatientMedicalInfoTable();

            $this->db->query('SELECT * FROM patient_medical_info WHERE patient_id = :patient_id LIMIT 1');
            $this->db->bind(':patient_id', (int)$patientId);

            return $this->db->single();
        }

        public function savePatientMedicalInfo($data){
            $this->ensurePatientMedicalInfoTable();

            $this->db->query('
                INSERT INTO patient_medical_info (
                    patient_id,
                    blood_type,
                    date_of_birth,
                    emergency_contact,
                    insurance_provider,
                    allergies
                ) VALUES (
                    :patient_id,
                    :blood_type,
                    :date_of_birth,
                    :emergency_contact,
                    :insurance_provider,
                    :allergies
                )
                ON DUPLICATE KEY UPDATE
                    blood_type = VALUES(blood_type),
                    date_of_birth = VALUES(date_of_birth),
                    emergency_contact = VALUES(emergency_contact),
                    insurance_provider = VALUES(insurance_provider),
                    allergies = VALUES(allergies),
                    updated_at = CURRENT_TIMESTAMP
            ');

            $this->db->bind(':patient_id', (int)$data['patient_id']);
            $this->db->bind(':blood_type', $data['blood_type'] !== '' ? $data['blood_type'] : null);
            $this->db->bind(':date_of_birth', $data['date_of_birth'] !== '' ? $data['date_of_birth'] : null);
            $this->db->bind(':emergency_contact', $data['emergency_contact'] !== '' ? $data['emergency_contact'] : null);
            $this->db->bind(':insurance_provider', $data['insurance_provider'] !== '' ? $data['insurance_provider'] : null);
            $this->db->bind(':allergies', $data['allergies'] !== '' ? $data['allergies'] : null);

            return $this->db->execute();
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
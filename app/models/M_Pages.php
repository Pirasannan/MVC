<?php 
    class M_Pages {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }
        
        public function getUsers(){ // get user details
            $this->db->query('SELECT * FROM Users');

            return $this ->db->resultSet(); 
        }
            
    }


    
?>
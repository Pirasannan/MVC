<?php 
    class Database {
        private $host = DB_HOST;
        private $user = DB_USER;
        private $password = DB_PASSWORD;
        private $dbname = DB_NAME;

        //dbh = data base host
        private $dbh;

        private $statement;
        private $error;

        public function __construct(){
            $dsn = "mysql:host='.$this->host.';dbname='.$this->dbname";
        
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );


            //instantiate PDO
            try{
                $this->dbh = new PDO($dsn, $this->user, $this->password, $options);

            }
            catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }



}
?>
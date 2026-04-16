<?php
class Database
{ //set db config details to variables
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    //dbh = data base host
    private $dbh;

    private $statement; //SQL statement
    private $error; //to store errors thrown due to query

    //Get error info
    public function errorInfo()
    {
        return $this->statement->errorInfo();
    }

    public function __construct()
    {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );


        //instantiate PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    //prepared statement
    public function query($sql)
    {
        $this->statement = $this->dbh->prepare($sql);
    }

    //bind parameters
    public function bind($param, $value, $type = NULL)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;  // String
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    //execute the prepare statment
    public function execute()
    {
        return $this->statement->execute();
    }

    // get multiple records as results
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);  //convert the results as PDO , to return it as array.
    }

    // get single record as result
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);  // we are returning the data in an array. "FETCH_ASSOC" is for array "FETCH_OBJ" is for object

    }

    //Check records count
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

            //Get PDO connection for lastInsertId
            public function getConnection(){
                return $this->dbh;
            }
}

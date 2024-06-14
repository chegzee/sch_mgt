<?php
class Database {

    private $statement;
    private $dbHandler;
    private $error;

    public function __construct()
    {
        $conn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
        $options = array (
            //PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES, false,
        );
        try {
            $this->dbHandler = new PDO($conn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // write queries
    public function query($sql) {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    // Bind values
    public function bind($parameter, $value, $type = null) {
        switch (is_null($type)) {
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
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($parameter, $value, $type);
    }

    // execute prepared statement
    public function execute() {
        try {
            return $this->statement->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Return an array
    public function resultSet() {
        try { 
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Return a single
    public function single() {
        try {
            $this->execute();
            return $this->statement->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Return row count
    public function rowCount() {
        try {
            $this->execute();
            return $this->statement->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
<?php

/*
 * Classe PDO Database
 * Conectar com database
 * Criar prepared statements
 * Bind valores
 * Retornar linhas e resultados
 */
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $error;
    private $stmt;
    
    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        error_log("Attempting database connection to: " . $this->host . " with database: " . $this->dbname);
        
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        // Create a PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            error_log("Database connection successful");
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database connection failed: " . $this->error);
            throw $e;
        }
    }
    
    // Prepare statement with query
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }
    
    // Bind values
    public function bind($param, $value, $type = null)
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
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    // Execute the prepared statement
    public function execute()
    {
        try {
            $result = $this->stmt->execute();
            error_log("Query executed successfully: " . ($result ? "true" : "false"));
            return $result;
        } catch (Exception $e) {
            error_log("Error executing query: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Get result set as array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Get single record as object
    public function single()
    {
        try {
            $this->execute();
            $result = $this->stmt->fetch(PDO::FETCH_OBJ);
            error_log("Single query result: " . ($result ? "Found" : "Not found"));
            return $result;
        } catch (Exception $e) {
            error_log("Error in single() method: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Get record row count
    public function rowCount()
    {
        try {
            $count = $this->stmt->rowCount();
            error_log("Row count: " . $count);
            return $count;
        } catch (Exception $e) {
            error_log("Error getting row count: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Get last insert ID
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}


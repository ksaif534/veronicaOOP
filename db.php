<?php

// $host = "localhost";
// $dbname = "veronica";
// $username = "root";
// $password = "";

class DB{
    protected $pdo;
    protected $dsn;

    public function __construct($pdo, $dsn) {
        $this->pdo = $pdo;
        $this->dsn = $dsn;
    }

    public function getPDO(){
        return $this->pdo;
    }

    public function getConnection() {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }
}

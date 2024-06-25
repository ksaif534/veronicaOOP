<?php

class Credentials{
    protected $dsn;
    protected $username;
    protected $password;

    public function __construct(){
        $this->dsn      = $this->initDSN();
        $this->username = $this->initUsername();
        $this->password = $this->initPassword();
    }

    public function initDSN() {
        return "mysql:host=localhost;dbname=veronicadb;port=3306";
    }

    public function initUserName(){
        return "sk123";
    }

    public function initPassword(){
        return "Sk123&*(";
    }

    public function getDSN(){
        return $this->dsn;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }
}

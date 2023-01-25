<?php

class Database {
    
    private static $host = "127.0.0.1";
    private static $sgbd = "pgsql";
    private static $db = "pagcompleto";
    private static $user = "postgres";
    private static $password = "pass";
    private $connection;

    private function getHost() {return self::$host; }
    private function getSGBD() { return self::$sgbd; }
    private function getUser() { return self::$user; }
    private function getPassword() { return self::$password; }
    private function getDB() {return self::$db; }

    public function __destruct() {
        $this->connection = null;
    }

    public function connect() {
        if (is_null($this->connection)) {
            try {
                $this->connection = new PDO($this->getSGBD() . ":host=" . $this->getHost() . ";dbname=" . $this->getDB(), $this->getUser(), $this->getPassword());
            }
            catch (Exceptions $e) {
                $this->connection = null;
            }
        }

        return $this->connection;
    }
}
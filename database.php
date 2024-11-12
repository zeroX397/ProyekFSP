<?php
require_once("data.php");

class Database {
    protected $connection;

    public function __construct() {
        $this->connection = new mysqli(SERVER, USER, PASS, DATABASE);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __destruct() {
        $this->connection->close();
    }
}
?>

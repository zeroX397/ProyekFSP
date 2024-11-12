<?php
require_once("database.php");

class Member extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Check if the username already exists in the database
    public function isUsernameExists($username) {
        $query = "SELECT * FROM `fsp-project`.member WHERE username = ?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($result) > 0;
    }

    // Register a new member
    public function registerMember($fname, $lname, $username, $password) {
        $hashedPassword = hash('sha256', $password);
        $query = "INSERT INTO `fsp-project`.member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, 'member')";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $fname, $lname, $username, $hashedPassword);
        return mysqli_stmt_execute($stmt);
    }
}
?>

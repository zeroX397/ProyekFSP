<?php
require_once("../../database.php");

class Member extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Method untuk mendapatkan daftar member dengan paging
    public function getAllMembers($start, $perpage) {
        $sql = "SELECT member.idmember as id_member, member.username, CONCAT(member.fname, ' ', member.lname) as member_name 
                FROM `member` 
                ORDER BY member.idmember ASC 
                LIMIT ?, ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $start, $perpage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Method untuk menghitung total data game
    public function getTotalMembers() {
        $sql = "SELECT COUNT(DISTINCT member.idmember) AS total 
                FROM member";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Method untuk mendapatkan data game berdasarkan ID
    public function getMemberById($idmember) {
        $sql = "SELECT * 
                FROM member 
                WHERE idmember = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method untuk mengupdate data game
    public function updateMember($username, $fname, $lname, $hashed_password, $idmember) {
        $sql = "UPDATE member 
                SET username = ?, fname = ?, lname = ?, password = ?   
                WHERE idmember = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('ssssi', $username, $fname, $lname, $hashed_password, $idmember);
        return $stmt->execute();
    }

    // Method untuk menghapus data game
    public function deleteMember($idmember) {
        $sql = "DELETE FROM member 
                WHERE idmember = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idmember);
        return $stmt->execute();
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

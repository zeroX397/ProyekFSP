<?php
require_once("../../database.php");

class Game extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Method untuk mendapatkan daftar game dengan paging
    public function getAllGames($start, $perpage) {
        $sql = "SELECT idgame AS id_game, name, description 
                FROM game 
                ORDER BY idgame 
                ASC LIMIT ?, ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $start, $perpage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Method untuk menghitung total data game
    public function getTotalGames() {
        $sql = "SELECT COUNT(DISTINCT idgame) AS total 
                FROM game";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Method untuk menambahkan game baru
    public function addGame($name, $description) {
        $sql = "INSERT INTO game (name, description) 
                VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Method untuk mendapatkan data game berdasarkan ID
    public function getGameById($idgame) {
        $sql = "SELECT * 
                FROM game 
                WHERE idgame = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idgame);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method untuk mengupdate data game
    public function updateGame($idgame, $name, $description) {
        $sql = "UPDATE game 
                SET name = ?, description = ? 
                WHERE idgame = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('ssi', $name, $description, $idgame);
        return $stmt->execute();
    }

    // Method untuk menghapus data game
    public function deleteGame($idgame) {
        $sql = "DELETE FROM game 
                WHERE idgame = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idgame);
        return $stmt->execute();
    }
}
?>

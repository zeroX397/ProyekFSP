<?php
require_once("../../database.php");

class Game extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Get all games for paging
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

    // Count total games
    public function getTotalGames() {
        $sql = "SELECT COUNT(DISTINCT idgame) AS total 
                FROM game";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Add new game
    public function addGame($name, $description) {
        $sql = "INSERT INTO game (name, description) 
                VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Get game name by ID
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

    // Update game
    public function updateGame($idgame, $name, $description) {
        $sql = "UPDATE game 
                SET name = ?, description = ? 
                WHERE idgame = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('ssi', $name, $description, $idgame);
        return $stmt->execute();
    }

    // Delete game
    public function deleteGame($idgame) {
        $sql = "DELETE FROM game 
                WHERE idgame = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idgame);
        return $stmt->execute();
    }
}
?>

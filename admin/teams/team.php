<?php
require_once("../../database.php");

class Team extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAllTeams($start, $perpage) {
        $sql = "SELECT team.idteam, game.name AS game_name, team.name AS team_name 
                FROM team 
                INNER JOIN game ON game.idgame = team.idgame 
                ORDER BY team.idteam ASC 
                LIMIT ?, ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $start, $perpage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getTotalTeams() {
        $sql = "SELECT COUNT(DISTINCT team.idteam) AS total 
                FROM team
                INNER JOIN game ON game.idgame = team.idgame";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Get All Games 
    public function getAllGames() {
        $sql = "SELECT idgame, name FROM game;";
        $result = $this->connection->query($sql);
        $games = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $games[] = $row;
            }
        }
        return $games;
    }    

    // Menambahkan tim baru
    public function addTeam($idgame, $team_name) {
        $sql = "INSERT INTO team(idgame, name) VALUES (?, ?);";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("is", $idgame, $team_name);
        return $stmt->execute();
    }

    // Mendapatkan detail tim berdasarkan ID
    public function getTeamById($idteam) {
        $sql = "SELECT * FROM team WHERE idteam = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Memperbarui informasi tim
    public function updateTeam($idteam, $idgame, $team_name) {
        $sql = "UPDATE team SET idgame = ?, name = ? WHERE idteam = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("isi", $idgame, $team_name, $idteam);
        return $stmt->execute();
    }

    // Menghapus tim berdasarkan ID
    public function deleteTeam($idteam) {
        $sql = "DELETE FROM team WHERE idteam = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idteam);
        return $stmt->execute();
    }
}
?>

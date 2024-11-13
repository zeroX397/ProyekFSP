<?php
require_once("../../database.php");

class Achievement extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getAchievements($teamFilter = "", $start, $perPage)
    {
        // Query dasar
        $sql = "SELECT achievement.idachievement, team.name AS team_name, 
                    achievement.name AS achievement_name, achievement.date AS achievement_date, 
                    achievement.description AS achievement_description 
                FROM achievement 
                INNER JOIN team ON team.idteam = achievement.idteam";

        // Tambahkan filter tim jika ada
        if (!empty($teamFilter)) {
            $sql .= " WHERE team.idteam = ?";
        }

        $sql .= " ORDER BY team.idteam ASC 
                LIMIT ?, ?";

        // Prepare statement
        $stmt = $this->connection->prepare($sql);

        // Bind parameter berdasarkan apakah ada filter tim atau tidak
        if (!empty($teamFilter)) {
            $stmt->bind_param("sii", $teamFilter, $start, $perPage);
        } else {
            $stmt->bind_param("ii", $start, $perPage);
        }

        // Eksekusi statement dan ambil hasilnya
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }



    // Mendapatkan jumlah total data pencapaian, termasuk filter tim
    public function getAchievementCount($teamFilter = "")
    {
        $sql = "SELECT COUNT(DISTINCT achievement.idachievement) AS total 
                FROM achievement
                INNER JOIN team ON team.idteam = achievement.idteam";

        if (!empty($teamFilter)) {
            $sql .= " WHERE team.idteam = ?";
        }
        $stmt = $this->connection->prepare($sql);

        if (!empty($teamFilter)) {
            $stmt->bind_param("s", $teamFilter);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Mendapatkan daftar tim yang tersedia
    public function getAllTeamsFilter()
    {
        $sql = "SELECT DISTINCT team.name as team_name, team.idteam as team_id 
                FROM team";
        $result = mysqli_query($this->connection, $sql);
        return $result;
    }

    // Method to get all teams for the dropdown
    public function getAllTeams() {
        $sql = "SELECT MIN(idteam) AS idteam, name FROM team GROUP BY name";
        $result = $this->connection->query($sql);
        $teams = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $teams[] = $row;
            }
        }
        return $teams;
    }

    // Menambahkan pencapaian baru ke database
    public function addAchievement($idteam, $achievement_name, $achievement_date, $achievement_description) {
        $sql = "INSERT INTO achievement (idteam, name, date, description) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, 'isss', $idteam, $achievement_name, $achievement_date, $achievement_description);
        $result = mysqli_stmt_execute($stmt);
        return $result;
    }

    // Mendapatkan data pencapaian berdasarkan ID
    public function getAchievementById($idachievement) {
        $sql = "SELECT * FROM achievement WHERE idachievement = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $idachievement);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Memperbarui data pencapaian
    public function updateAchievement($idachievement, $idteam, $achievement_name, $achievement_date, $achievement_description) {
        $sql = "UPDATE achievement SET idteam = ?, name = ?, date = ?, description = ? WHERE idachievement = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, 'isssi', $idteam, $achievement_name, $achievement_date, $achievement_description, $idachievement);
        return mysqli_stmt_execute($stmt);
    }

    // Method untuk menghapus data Achievement
    public function deleteAchievement($idAchievement) {
        $sql = "DELETE FROM achievement 
                WHERE idachievement = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idAchievement);
        return $stmt->execute();
    }
}
?>

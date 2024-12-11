<?php
require_once("database.php");

class TeamDetail extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Get team detail
    public function getTeamDetails($idteam) {
        $query = "SELECT 
                    t.name AS TeamName,
                    e.name AS EventName,
                    e.date AS EventDate,
                    a.name AS AchievementName,
                    a.date AS AchievementDate
                FROM 
                    team t
                    LEFT JOIN event_teams et ON t.idteam = et.idteam
                    LEFT JOIN event e ON et.idevent = e.idevent
                    LEFT JOIN achievement a ON a.idteam = t.idteam
                WHERE 
                    t.idteam = ?;";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>

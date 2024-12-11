<?php
require_once("../database.php");

class Profile extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Get all team with member
    public function getJoinedTeams($idmember) {
        $query = "SELECT t.idteam, t.name as team_name
                  FROM team AS t
                  INNER JOIN team_members AS tm ON t.idteam = tm.idteam
                  WHERE tm.idmember = ?";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get proposal
    public function getJoinProposals($idmember) {
        $query = "SELECT jp.idjoin_proposal, t.name as team_name, jp.status
                  FROM join_proposal AS jp
                  JOIN team AS t ON jp.idteam = t.idteam
                  WHERE jp.idmember = ?";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get team detail
    public function getTeamDetails($idteam) {
        $query = "SELECT 
                    t.name AS TeamName,
                    e.name AS EventName,
                    e.date AS EventDate,
                    a.name AS AchievementName,
                    a.date AS AchievementDate,
                    m.username AS MemberName
                FROM 
                    team t
                    LEFT JOIN event_teams et ON t.idteam = et.idteam
                    LEFT JOIN event e ON et.idevent = e.idevent
                    LEFT JOIN achievement a ON a.idteam = t.idteam
                    LEFT JOIN team_members tm ON tm.idteam = t.idteam
                    LEFT JOIN member m ON m.idmember = tm.idmember
                WHERE 
                    t.idteam = ?";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>

<?php
require_once("../../database.php");

class EventTeam extends Database {
    public function __construct() {
        parent::__construct();
    }

    public function getEventTeams($teamFilter = "", $start, $perPage)
    {
        // Query dasar
        $sql = "SELECT event.idevent AS id_event, event.name AS event_name, 
                       event.date AS event_date, event.description AS event_description, 
                       team.name AS team_name
                FROM event
                JOIN event_teams ON event.idevent = event_teams.idevent
                JOIN team ON event_teams.idteam = team.idteam";

        if (!empty($teamFilter)) {
            $sql .= " WHERE team.idteam = ?";
        }

        $sql .= " ORDER BY team.idteam ASC 
                LIMIT ?, ?";

        $stmt = $this->connection->prepare($sql);
        if (!empty($teamFilter)) {
            $stmt->bind_param("sii", $teamFilter, $start, $perPage);
        } else {
            $stmt->bind_param("ii", $start, $perPage);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }



    // Mendapatkan jumlah total data pencapaian, termasuk filter tim
    public function getEventTeamsCount($teamFilter = "")
    {
        $sql = "SELECT COUNT(DISTINCT event.idevent) AS total 
                FROM event
                INNER JOIN event_teams ON event.idevent = event_teams.idevent
                INNER JOIN team ON event_teams.idteam = team.idteam";

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
}
?>

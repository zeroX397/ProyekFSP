<?php
require_once("../../database.php");

class Event extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Method untuk mendapatkan daftar event dengan paging
    public function getAllEvents($start, $perpage) {
        $sql = "SELECT idevent, name, date, description
                FROM `event`
                ORDER BY idevent ASC
                LIMIT ?, ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $start, $perpage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Method untuk menghitung total data event
    public function getTotalEvents() {
        $sql = "SELECT COUNT(DISTINCT idevent) AS total 
                FROM event";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Method to insert a new event
    public function insertEvent($name, $date, $desc, $team_id) {
        // Insert event data into `event` table
        $sql = "INSERT INTO `event`(`name`, `date`, `description`) VALUES (?, ?, ?);";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('sss', $name, $date, $desc);
        $result = $stmt->execute();

        if ($result) {
            // Get the last inserted event ID
            $lasteventid = $this->connection->insert_id;

            // Link the event to the team in `event_teams` table
            $sqlevent_team = "INSERT INTO `event_teams`(`idevent`, `idteam`) VALUES (?, ?)";
            $stmtevent_team = $this->connection->prepare($sqlevent_team);
            $stmtevent_team->bind_param('ii', $lasteventid, $team_id);
            $resultevent_team = $stmtevent_team->execute();

            if ($resultevent_team) {
                return true;
            } else {
                return "Error linking event to team: " . $this->connection->error;
            }
        } else {
            return "Error inserting event: " . $this->connection->error;
        }
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
    
    // Fetch event data based on event ID
    public function getEventById($idevent) {
        $query = "SELECT * FROM event WHERE idevent = ?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idevent);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Fetch team associated with the event
    public function getTeamByEventId($idevent) {
        $query = "SELECT idteam FROM event_teams WHERE idevent = ?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idevent);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $team = mysqli_fetch_assoc($result);
        return $team['idteam'] ?? null;
    }

    // Update event data
    public function updateEvent($idevent, $name, $date, $description) {
        $query = "UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $date, $description, $idevent);
        return mysqli_stmt_execute($stmt);
    }

    // Method untuk menghapus data event
    public function deleteEvent($idevent) {
        $sql = "DELETE FROM event 
                WHERE idevent = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $idevent);
        return $stmt->execute();
    }
}
?>

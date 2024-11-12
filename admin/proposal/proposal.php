<?php
require_once("../../database.php");

class Proposal extends Database {
    public function __construct() {
        parent::__construct();
    }

    // Method untuk mendapatkan daftar proposal dengan paging
    public function getAllProposals($start, $perpage) {
        $sql = "SELECT join_proposal.idjoin_proposal, member.fname, member.lname, team.name AS team_name, join_proposal.description, join_proposal.status
                FROM join_proposal 
                INNER JOIN team ON team.idteam = join_proposal.idteam
                INNER JOIN member ON member.idmember = join_proposal.idmember
                WHERE join_proposal.status = 'waiting'
                ORDER BY join_proposal.idjoin_proposal ASC 
                LIMIT ?, ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $start, $perpage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Method untuk menghitung total data proposal
    public function getTotalProposals() {
        $sql = "SELECT COUNT(DISTINCT join_proposal.idjoin_proposal) AS total 
                FROM join_proposal
                WHERE join_proposal.status = 'waiting'";
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Update proposal status
    public function approveProposal($idJoinProposal) {
        $sql_update = "UPDATE join_proposal SET status = 'approved' WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($this->connection, $sql_update);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            return false;
        }
    }

    // Update proposal status to 'rejected'
    public function rejectProposal($idJoinProposal) {
        $sql_update = "UPDATE join_proposal SET status = 'rejected' WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($this->connection, $sql_update);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            return false;
        }
    }

    // Get team ID based on proposal ID
    public function getTeamIdByProposal($idJoinProposal) {
        $sql_team = "SELECT idteam FROM join_proposal WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($this->connection, $sql_team);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
            mysqli_stmt_execute($stmt);
            $result_team = mysqli_stmt_get_result($stmt);
            $team = mysqli_fetch_assoc($result_team);
            mysqli_stmt_close($stmt);
            return $team['idteam'] ?? null;
        } else {
            return null;
        }
    }

    // Get member ID based on proposal ID
    public function getMemberIdByProposal($idJoinProposal) {
        $sql_member = "SELECT idmember FROM join_proposal WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($this->connection, $sql_member);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
            mysqli_stmt_execute($stmt);
            $result_member = mysqli_stmt_get_result($stmt);
            $member = mysqli_fetch_assoc($result_member);
            mysqli_stmt_close($stmt);
            return $member['idmember'] ?? null;
        } else {
            return null;
        }
    }

    // Insert a member into a team
    public function addMemberToTeam($idTeam, $idMember) {
        $sql_insert = "INSERT INTO team_members (idteam, idmember) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->connection, $sql_insert);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ii', $idTeam, $idMember);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            return false;
        }
    }

    // Get total count of responded join proposals
    public function getTotalRespondedProposals() {
        $sql = "SELECT COUNT(DISTINCT idjoin_proposal) AS total 
                FROM join_proposal 
                WHERE status IN ('approved', 'rejected')";
        $result = mysqli_query($this->connection, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    // Fetch responded join proposals data with pagination
    public function getRespondedProposals($start, $perPage) {
        $sql = "SELECT join_proposal.idjoin_proposal, member.fname, member.lname, team.name AS team_name, join_proposal.description, join_proposal.status
                FROM join_proposal 
                INNER JOIN team ON team.idteam = join_proposal.idteam
                INNER JOIN member ON member.idmember = join_proposal.idmember
                WHERE join_proposal.status IN ('approved', 'rejected')
                ORDER BY join_proposal.idjoin_proposal ASC 
                LIMIT ?, ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ii', $start, $perPage);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $data;
        }
        return [];
    }
}
?>

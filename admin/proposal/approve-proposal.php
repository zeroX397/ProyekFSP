<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Check ID proposal
if (isset($_POST['idjoin_proposal'])) {
    $idJoinProposal = $_POST['idjoin_proposal'];

    // Update status proposal
    $sql_update = "UPDATE join_proposal SET status = 'approved' WHERE idjoin_proposal = ?";
    $stmt = mysqli_prepare($connection, $sql_update);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Error updating proposal status: " . mysqli_error($connection);
        exit();
    }

    // Get ID team 
    $sql_team = "SELECT idteam FROM join_proposal WHERE idjoin_proposal = ?";
    $stmt_team = mysqli_prepare($connection, $sql_team);
    if ($stmt_team) {
        mysqli_stmt_bind_param($stmt_team, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt_team);
        $result_team = mysqli_stmt_get_result($stmt_team);
        if ($team = mysqli_fetch_assoc($result_team)) {
            $idTeam = $team['idteam'];
        } else {
            echo "Team not found.";
            exit();
        }
        mysqli_stmt_close($stmt_team);
    } else {
        echo "Error retrieving team ID: " . mysqli_error($connection);
        exit();
    }

    // Get ID member
    $sql_member = "SELECT idmember FROM join_proposal WHERE idjoin_proposal = ?";
    $stmt_member = mysqli_prepare($connection, $sql_member);
    if ($stmt_member) {
        mysqli_stmt_bind_param($stmt_member, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt_member);
        $result_member = mysqli_stmt_get_result($stmt_member);
        if ($member = mysqli_fetch_assoc($result_member)) {
            $idMember = $member['idmember'];
        } else {
            echo "Member not found.";
            exit();
        }
        mysqli_stmt_close($stmt_member);
    } else {
        echo "Error retrieving member ID: " . mysqli_error($connection);
        exit();
    }

    // Insert the member into the team
    $sql_insert = "INSERT INTO team_members (idteam, idmember) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($connection, $sql_insert);
    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, 'ii', $idTeam, $idMember);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    } else {
        echo "Error inserting team member: " . mysqli_error($connection);
        exit();
    }

    header("Location: waiting.php?status=approved");
    exit();

} else {
    header("Location: waiting.php");
    exit();
}
?>

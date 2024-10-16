<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Check if the proposal ID is provided
if (isset($_POST['idjoin_proposal'])) {
    $idJoinProposal = $_POST['idjoin_proposal'];

    // Start transaction
    mysqli_begin_transaction($connection);
    try {
        // Update status proposal
        $sql_update = "UPDATE join_proposal SET status = 'approved' WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($connection, $sql_update);
        mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt);
        
        // Get the team ID 
        $sql_team = "SELECT idteam FROM join_proposal WHERE idjoin_proposal = ?";
        $stmt_team = mysqli_prepare($connection, $sql_team);
        mysqli_stmt_bind_param($stmt_team, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt_team);
        $result_team = mysqli_stmt_get_result($stmt_team);
        $team = mysqli_fetch_assoc($result_team);
        $idTeam = $team['idteam'];

        // Get the member ID 
        $sql_member = "SELECT idmember FROM join_proposal WHERE idjoin_proposal = ?";
        $stmt_member = mysqli_prepare($connection, $sql_member);
        mysqli_stmt_bind_param($stmt_member, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt_member);
        $result_member = mysqli_stmt_get_result($stmt_member);
        $member = mysqli_fetch_assoc($result_member);
        $idMember = $member['idmember'];

        // Insert the member into the team
        $sql_insert = "INSERT INTO team_members (idteam, idmember) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($connection, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, 'ii', $idTeam, $idMember);
        mysqli_stmt_execute($stmt_insert);

        // Commit transaction
        mysqli_commit($connection);

        header("Location: waiting.php?status=approved");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($connection);
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: waiting.php");
    exit();
}
?>

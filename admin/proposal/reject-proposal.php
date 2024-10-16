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

    mysqli_begin_transaction($connection);
    try {
        // Update status
        $sql_update = "UPDATE join_proposal SET status = 'rejected' WHERE idjoin_proposal = ?";
        $stmt = mysqli_prepare($connection, $sql_update);
        mysqli_stmt_bind_param($stmt, 'i', $idJoinProposal);
        mysqli_stmt_execute($stmt);

        mysqli_commit($connection);

        header("Location: waiting.php?status=rejected");
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

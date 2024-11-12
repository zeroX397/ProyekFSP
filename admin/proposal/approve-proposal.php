<?php
session_start();
require_once("proposal.php");

$proposal = new Proposal();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

if (isset($_POST['idjoin_proposal'])) {
    $idJoinProposal = $_POST['idjoin_proposal'];

    // Approve proposal
    if ($proposal->approveProposal($idJoinProposal)) {
        $idTeam = $proposal->getTeamIdByProposal($idJoinProposal);
        $idMember = $proposal->getMemberIdByProposal($idJoinProposal);

        if ($idTeam && $idMember && $proposal->addMemberToTeam($idTeam, $idMember)) {
            header("Location: waiting.php?status=approved");
            exit();
        } else {
            echo "Error adding member to team.";
            exit();
        }
    } else {
        echo "Error approving proposal.";
        exit();
    }
} else {
    header("Location: waiting.php");
    exit();
}
?>

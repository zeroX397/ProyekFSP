<?php
session_start();
require_once("team.php");

$team = new Team();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Fetch game data to fill the dropdown
$games = $team->getAllGames();

// Check if team ID is set
if (isset($_POST['idteam']) || isset($_GET['idteam'])) {
    $idteam = isset($_POST['idteam']) ? $_POST['idteam'] : $_GET['idteam'];

    // Fetch team data by ID
    $teamData = $team->getTeamById($idteam);
    if ($teamData) {
        $teamInfo = $teamData; // Store data in $teamInfo
    } else {
        echo "<script>alert('Team not found.'); window.location.href='/admin/teams/index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Team ID not provided.'); window.location.href='/admin/teams/';</script>";
    exit();
}

// Update team data
if (isset($_POST['submit']) && isset($_POST['idteam'])) {
    $idteam = $_POST['idteam'];
    $idgame = $_POST['idgame'];
    $team_name = $_POST['team_name'];
    
    // Update the team data in the database
    if ($team->updateTeam($idteam, $idgame, $team_name)) {
        echo "<script>alert('Team updated successfully.'); window.location.href='/admin/teams/index.php';</script>";
    } else {
        $error = "Error during team update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/team.css">
    <title>Edit Team - Informatics E-Sport Club</title>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/how-to-join.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo
                '<div class="dropdown">
                    <a class="dropbtn" onclick="adminpageDropdown()">Admin Sites
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-content" id="dd-admin-page">
                        <a href="/admin/teams/">Manage Teams</a>
                        <a href="/admin/members/">Manage Members</a>
                        <a href="/admin/events/">Manage Events</a>
                        <a href="/admin/games/">Manage Games</a>
                        <a href="/admin/achievements/">Manage Achievements</a>
                        <a href="/admin/event_teams/">Manage Event-Teams</a>
                    </div>
                </div>';
                echo
                '<div class="dropdown">
                    <a class="dropbtn" onclick="proposalDropdown()">Join Proposal
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-content" id="proposalPage">
                        <a href="/admin/proposal/waiting.php">Waiting Approval</a>
                        <a href="/admin/proposal/responded.php">Responded</a>
                    </div>
                </div>';
            }
        }
        ?>
    </nav>
    <!-- Form to Edit Team -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="edit-form" method="post">
            <!-- Hidden input for team ID -->
            <input type="hidden" name="idteam" value="<?= htmlspecialchars($teamInfo['idteam']) ?>">
            <label for="idgame">Select Game</label>
            <select name="idgame" required>
                <?php foreach ($games as $game): ?>
                    <option value="<?= $game['idgame'] ?>" <?= $teamInfo['idgame'] == $game['idgame'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($game['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="team_name">Team Name</label>
            <input name="team_name" type="text" placeholder="Team Name" value="<?= htmlspecialchars($teamInfo['name']) ?>" required>
            <button name="submit" type="submit" class='btnsubmit'>Update</button>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
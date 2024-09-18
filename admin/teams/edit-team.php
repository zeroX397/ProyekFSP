<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Fetch game data to fill the dropdown
$gameQuery = "SELECT idgame, name FROM game;";
$gameResult = mysqli_query($connection, $gameQuery);
$games = [];
if ($gameResult) {
    while ($gameRow = mysqli_fetch_assoc($gameResult)) {
        $games[] = $gameRow;
    }
}

// Check if team ID is set
if (isset($_GET['idteam'])) {
    $idteam = mysqli_real_escape_string($connection, $_GET['idteam']);

    // Fetch the existing team data to pre-fill the form
    $teamQuery = "SELECT * FROM team WHERE idteam = ?";
    $stmt = mysqli_prepare($connection, $teamQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idteam);
    mysqli_stmt_execute($stmt);
    $teamResult = mysqli_stmt_get_result($stmt);
    $team = mysqli_fetch_assoc($teamResult);

    if (!$team) {
        echo "<script>alert('Team not found.'); window.location.href='/admin/teams/index.php';</script>";
        exit();
    }
} else {
    header('Location: /admin/teams/index.php');
    exit();
}

// Update team data
if (isset($_POST['submit'])) {
    $idgame = mysqli_real_escape_string($connection, $_POST['idgame']);
    $team_name = mysqli_real_escape_string($connection, $_POST['team_name']);

    $sql = "UPDATE `team` SET `idgame` = ?, `name` = ? WHERE `idteam` = ?;";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'isi', $idgame, $team_name, $idteam);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Team updated successfully.'); window.location.href='/admin/teams/index.php';</script>";
    } else {
        $error = "Error updating team.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/edit-team.css">
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
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </nav>
    <!-- Admin Navigation Bar -->
    <nav class="topnav admin-nav">
        <a class="label">Administration Menus</a>
        <a href="/admin/teams/">Manage Teams</a>
        <a href="/admin/members/">Manage Members</a>
        <a href="/admin/events/">Manage Events</a>
        <a href="/admin/games/">Manage Games</a>
        <a href="/admin/achievements/">Manage Achievements</a>
    </nav>
    <!-- Form to Edit Team -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="edit-form" method="post">
            <br><br><br><br><br><br>
            <table class="edit-table">
                <tr>
                    <td><label for="idgame">Select Game</label></td>
                    <td><select name="idgame" required>
                            <?php foreach ($games as $game): ?>
                                <option value="<?= $game['idgame'] ?>" <?= $team['idgame'] == $game['idgame'] ? 'selected' : '' ?>>
                                    <?= $game['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="team_name">Team Name</label></td>
                    <td><input name="team_name" type="text" placeholder="Team Name" value="<?= $team['name'] ?>" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class='btnsubmit'>Update</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>
<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
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

// Insert new team data
if (isset($_POST['submit'])) {
    $idgame = mysqli_real_escape_string($connection, $_POST['idgame']);
    $team_name = mysqli_real_escape_string($connection, $_POST['team_name']);

    $sql = "INSERT INTO `team`(`idgame`, `name`) VALUES (?, ?);";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $idgame, $team_name);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        echo "<script>alert('Team registration successful. You may see in Teams page.'); window.location.href='/admin/teams/index.php';</script>";
    } else {
        $error = "Error during registration.";
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
    <link rel="stylesheet" href="/assets/styles/admin/teams/add-team.css">
    <title>Informatics E-Sport Club</title>
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
            // User is not logged in
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            // User is logged in
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo
                '<div class="dropdown">
                    <a class="dropbtn" onclick="dropdownFunction()">Admin Sites
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
            }
        }
        ?>
    </nav>
    <!-- Form to Add New Team -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="add-form" method="post">
            <select name="idgame" required>
                <option value="">Select Game</option>
                <?php foreach ($games as $game): ?>
                    <option value="<?= $game['idgame'] ?>"><?= $game['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input name="team_name" type="text" placeholder="Team Name" required>
            <button name="submit" type="submit">Save Team</button><br>
        </form>
    </div>
    <script src="/assets/js/dropdown.js"></script>
</body>

</html>
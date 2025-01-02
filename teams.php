<?php
session_start();
include("config.php");

// Query to get teams ordered by game
$sql = "SELECT team.idteam, team.name AS team_name, game.idgame, game.name AS game_name 
        FROM team 
        INNER JOIN game ON team.idgame = game.idgame 
        ORDER BY game.idgame;";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/teams.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/team_picture.css">
    <title>All Teams List</title>
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
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
            // To check whether is admin or not
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
    <!-- Team(s) list with button "Apply Member" -->
    <section>
        <h1 class="hello-mssg">Hello! You can see the full list of teams and join them.</h1>
        <div class="element">
            <?php
            $teamsData = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $teamsData[] = $row;
                }
            } else {
                echo "<div>No teams found</div>";
            }
            if (!empty($teamsData)) {
                $current_game_id = null;
                foreach ($teamsData as $row) {
                    // If the game changes, print a new game name header
                    if ($current_game_id !== $row['idgame']) {
                        $current_game_id = $row['idgame'];
                        echo "<strong class='game-name'>" . htmlspecialchars($row['game_name']) . "</strong>";
                    }

                    // Define team logo path
                    $idteam = $row['idteam'];
                    $logoPath = "/assets/img/team_picture/$idteam.jpg";
                    $defaultPath = "/assets/img/team_picture/default.jpg";

                    // Print team data
                    echo "<div class='container'>";
                    echo "<div>";
                    echo "<img class='team-logo' src='$logoPath' alt='Team Logo' class='team-logo' onerror=\"this.onerror=null;this.src='$defaultPath';\">";
                    echo "<div class='title'>" . htmlspecialchars($row['team_name']) . "</div>";
                    echo "<div class='content'>" . "Team ID   : " . htmlspecialchars($row['idteam']) . "</div>";
                    echo "<div class='content'>" . "Team Game : " . htmlspecialchars($row['game_name']) . "</div>";
                    echo "</div>";
                    echo "<form action='join-team.php' method='post'>";
                    echo "<input type='hidden' name='idteam' value='" . htmlspecialchars($idteam) . "'>";
                    echo "<input type='submit' id='btn-join' class='button' value='Apply'>";
                    echo "</form>";
                    echo "<form action='team-detail.php' method='get'>";
                    echo "<input type='hidden' name='idteam' value='" . htmlspecialchars($idteam) . "'>";
                    echo "<input type='submit' id='btn-join' class='button' value='Details'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<tr><td colspan='6'>No teams found</td></tr>";
            }
            ?>
        </div>

    </section>
    <script src="/assets/js/script.js"></script>
</body>

</html>
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
    <link rel="stylesheet" href="/assets/styles/teams.css?v= time(), ?>">
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
        }
            // To check whether user is admin or not
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
        ?>
    </nav>
    <h1 class="hello-mssg">Hello! You can see the full list of teams and join them.</h1>

    <div class="all-team">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            $current_game_id = null;

            while ($row = mysqli_fetch_assoc($result)) {
                if ($current_game_id !== $row['idgame']) {
                    // Tutup div untuk game sebelumnya jika ada
                    if ($current_game_id !== null) {
                        echo "</div>"; // Tutup div.game-container
                    }

                    // Mulai div baru untuk game yang berbeda
                    $current_game_id = $row['idgame'];
                    echo "<div class='game-container'>";
                    echo "<h2 class='game-header'>" . htmlspecialchars($row['game_name']) . "</h2>";
                }

                $idteam = $row['idteam'];
                $logoPath = "/assets/img/team_picture/$idteam.jpg";
                $defaultPath = "/assets/img/team_picture/default.jpg";

                echo "<div class='container'>";
                echo "<img src='$logoPath' alt='Team Logo' class='team-logo' onerror=\"this.onerror=null;this.src='$defaultPath';\">";
                echo "<div class='content'>";
                echo "<div class='title'>" . htmlspecialchars($row['team_name']) . "</div>";
                echo "<div class='details'>Game: " . htmlspecialchars($row['game_name']) . "</div>";
                echo "</div>";
                echo "<div class='buttons'>";

                echo "<form action='join-team.php' method='post'>";
                echo "<input type='hidden' name='idteam' value='" . $idteam . "'>";
                echo "<input type='submit' class='edit' value='Apply'>";
                echo "</form>";

                echo "<form action='team-detail.php' method='get'>";
                echo "<input type='hidden' name='idteam' value='" . $idteam . "'>";
                echo "<input type='submit' class='delete' value='Details'>";
                echo "</form>";

                echo "</div>"; // Tutup div.buttons
                echo "</div>"; // Tutup div.container
            }

            // Tutup div terakhir untuk game
            echo "</div>";
        } else {
            echo "<p>No teams found</p>";
        }
        ?>
    </div>

    <!-- Paging -->
    <!-- <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='teams.php?p=$prev'>Prev</a>"; // Previous page 
        }

        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; // Current page 
            } else {
                echo "<a href='teams.php?p=$i'>$i</a>"; // Other page 
            }
        }

        if ($page < $totalpage) {
            $next = $page + 1;
            echo "<a href='teams.php?p=$next'>Next</a>"; // Next page 
        }
        ?>
    </div> -->
    <script src="/assets/js/script.js"></script>
</body>

</html>
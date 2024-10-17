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
        <h1 class="hello-mssg">Hello! You can see full list of teams and join them.</h1>
        <div class="all-team">
            <table>
                <tr>
                    <th>Team ID</th>
                    <th>Team Name</th>
                    <th>Game Played</th>
                    <th>Join Team</th>
                    <th>View Detail</th>
                </tr>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $current_game_id = null;

                    while ($row = mysqli_fetch_assoc($result)) {
                        // If the game changes, print a new game name header
                        if ($current_game_id !== $row['idgame']) {
                            $current_game_id = $row['idgame'];
                            echo "<tr><td colspan='5'><strong>" . $row['game_name'] . "</strong></td></tr>";
                        }
                        // Print team data
                        echo "<tr>";
                        echo "<td>" . $row['idteam'] . "</td>";
                        echo "<td>" . $row['team_name'] . "</td>";
                        echo "<td>" . $row['game_name'] . "</td>";
                        echo "<td>";
                        echo "<form action='join-team.php' method='post'>";
                        echo "<input type='hidden' name='idteam' value='" . $row['idteam'] . "'>";
                        echo "<input type='submit' name='joinbtn' id='btn-join' class='button' value='Apply'>";
                        echo "</form>";
                        echo "</td>";
                        // View Teams Details
                        echo "<td>";
                        echo "<form action='team-detail.php' method='get'>";
                        echo "<input type='hidden' name='idteam' value='" . $row['idteam'] . "'>";
                        echo "<input type='submit' name='joinbtn' id='btn-join' class='button' value='Details'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No teams found</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
    <script src="/assets/js/script.js"></script>
</body>

</html>
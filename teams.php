<?php
session_start();
include("config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

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
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/teams.css">
    <title>Informatics E-Sport Club</title>
</head>

<body>
    <!-- Top Navigation Bar -->
    <div class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/become-member.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            // User is not logged in
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            // User is logged in
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </div>
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
                </tr>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $current_game_id = null;

                    while ($row = mysqli_fetch_assoc($result)) {
                        // If the game changes, print a new game name header
                        if ($current_game_id !== $row['idgame']) {
                            $current_game_id = $row['idgame'];
                            echo "<tr><td colspan='4'><strong>" . $row['game_name'] . "</strong></td></tr>";
                        }

                        // Print team data
                        echo "<tr>";
                        echo "<td>" . $row['idteam'] . "</td>";
                        echo "<td>" . $row['team_name'] . "</td>";
                        echo "<td>" . $row['game_name'] . "</td>";
                        echo "<td>";
                        echo "<form action='join-team.php' method='post'>";
                        echo "<input type='hidden' name='id_urls' value='" . $row['idteam'] . "'>";
                        echo "<button type='submit' name='joinbtn' id='btn-join' class='edit'>Apply</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No teams found</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
</body>

</html>

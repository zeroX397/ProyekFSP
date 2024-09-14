<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}
$sql = "SELECT achievement.idachievement, team.name AS team_name, achievement.name AS achievement_name, achievement.date AS achievement_date, 
        achievement.description AS achievement_description FROM achievement INNER JOIN team ON team.idteam = achievement.idachievement 
        ORDER BY team.idteam ASC;";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/home.css">
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
            // To check wether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </div>
    <!-- Admin Navigation Bar -->
    <div class="topnav admin-nav">
        <a class="label">Administration Menus</a>
        <a href="/admin/teams/">Manage Teams</a>
        <a href="/admin/members/">Manage Members</a>
        <a href="/admin/events/">Manage Events</a>
        <a href="/admin/games/">Manage Games</a>
        <a href="/admin/achievements/">Manage Achievements</a>
    </div>
    <!-- List of Teams to Edit or Delete -->
    <h1 class="welcome-mssg">Manage or Add Achievement</h1>
    <form action="add-team.php">
        <input type="submit" value="Add New Team">
    </form>
    <div class="all-team">
        <table>
            <tr>
                <th>Achievement ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Team</th>
                <th>Edit Team</th>
                <th>Delete Team</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['idachievement'] . "</td>";
                    echo "<td>" . $row['achievement_name'] . "</td>";
                    echo "<td>" . $row['achievement_date'] . "</td>";
                    echo "<td>" . $row['achievement_description'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td>";
                    echo "<form action='edit-achievement.php' method='post'>";
                    echo "<input type='hidden' name='id_urls' value='" . $row['idachievement'] . "'>";
                    echo "<button type='submit' name='editbtn' id='btn-editdelete' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='delete-achievement.php' method='post'>";
                    echo "<input type='hidden' name='id_urls' value='" . $row['idachievement'] . "'>";
                    echo "<button type='submit' name='deletebtn' id='btn-editdelete' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No teams found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
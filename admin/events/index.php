<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}
$sql = "SELECT event.idevent as id_event, event.name, event.date, event.description FROM `event` ORDER BY event.idevent ASC;";
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
    <h1 class="welcome-mssg">Manage Events</h1>
    <form action="../../signup.php">
        <input type="submit" value="Add New Event">
    </form>
    <div class="all-member">
        <table>
            <tr>
                <th>Event ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Edit Member</th>
                <th>Delete Member</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_event'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>";
                    echo "<form action='edit-event.php' method='post'>";
                    echo "<input type='hidden' name='id_urls' value='" . $row['id_event'] . "'>";
                    echo "<button type='submit' name='editbtn' id='btn-editdelete' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='delete-event.php' method='post'>";
                    echo "<input type='hidden' name='id_urls' value='" . $row['id_event'] . "'>";
                    echo "<button type='submit' name='deletebtn' id='btn-editdelete' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No game found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/join-team.css">
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
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </nav>

    <!-- Form Apply to Join Team -->
    <div class="application-form">
        <h3 text-align="center">Tell us just a bit about yourself:</h3>
        <textarea class="application-text" name="application-text" maxlength="100" rows="4" cols="50" placeholder="Your role in a game, or your main agents/heroes...&#10;Max. 100 characters."></textarea>
        <br><input type="button" value="Apply">
    </div>
</body>

</html>
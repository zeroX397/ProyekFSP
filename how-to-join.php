<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/how-to-join.css">
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
            echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </nav>

    <section class="join-instructions">
        <h1>How to join a team</h1>
        <p>If you wonder how to join a team, here some steps:</p>
        <ol class="steps-lists">
            <li>If you do not have an account, register one through <a href="/signup.php">this link</a>. But if you already have, do login <a href="/login.php">here</a>.</li>
            <li>Go to the <a href="/teams.php">teams page</a>, and find a team you desired.</li>
            <li>You can check the team details first. It contains every detail of the team, including its members.</li>
            <li>If you happy enough, click the join button.</li>
            <li>You will be asked to describe yourself. Provide useful information about you: e.g. your main heroes or agents, or you favorite roles. <br><em>Note: max. 100 characters.</em></li>
            <li>Click the Apply button. And please kindly wait for admin to decide your application.</li>
        </ol>
        <p>You may join more than 1 (one) team. So after you submit first application, you can submit another application to different team. But please take a note that admin can either <strong>accept or reject</strong> you application.</p>
        <p style="background-color: yellow;">Warning: Do not repeatedly send application, or admin will <strong>BANS</strong> and <strong>DELETE</strong> your account.</p>
    </section>
</body>

</html>
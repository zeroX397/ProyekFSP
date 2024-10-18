<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <section class="join-instructions">
        <h1>How to join a team</h1>
        <p>If you wonder how to join a team, here some steps:</p>
        <ol class="steps-lists">
            <li>If you do not have an account, register one through <a href="/signup.php">this link</a>. But if you already have, do login <a href="/login.php">here</a>.</li>
            <li>Go to the <a href="/teams.php">teams page</a>, and find a team you desired.</li>
            <li>You can check the team details first. It contains every detail of the team, including its members.</li>
            <li>If you happy enough, click the join button.</li>
            <li>You will be asked to describe yourself. Provide useful information about you: e.g. your main heroes or agents, or you favorite roles. <br><em>Note: maximum <strong>100 characters.</strong></em></li>
            <li>Click the <strong>Apply</strong> button. And please kindly wait for admin to decide your application.</li>
        </ol>
        <p>You may join more than 1 (one) team. So after you submit first application, you can submit another application to different team. But please take a note that admin can either <strong>accept or reject</strong> you application.</p>
        <p style="background-color: yellow;">Warning: Do not repeatedly send application (spamming), or admin will <strong>BAN</strong> and <strong>DELETE</strong> your account.</p>
    </section>
    <script src="/assets/js/script.js"></script>
</body>

</html>
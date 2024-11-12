<?php
session_start();
require_once("event.php");

$event = new Event();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Insert new event data
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $desc =  $_POST['description'];
    $team_id = $_POST['team'];

    $result = $event->insertEvent($name, $date, $desc, $team_id);

    if ($result === true) {
        echo "<script>alert('Event registration successful. You may see it on the event page.'); window.location.href='/admin/events/index.php';</script>";
    } else {
        $error = $result;
    }
}

// Mendapatkan data tim untuk dropdown
$teams = $event->getAllTeams();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/add-member.css">
    <title>Informatics E-Sport Club - Add Event</title>
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
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
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
    <!-- Form to Add New Event -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="add-form" method="post">
            <p>Enter Event Name</p>
            <input name="name" type="text" placeholder="Event Name" required>
            <p>Enter Event Date</p>
            <input type="date" name="date" placeholder="Enter Event's Date" required>
            <textarea class="application-text" name="description" maxlength="100" rows="5" placeholder="Event Description" required></textarea>
            <select name="team" required>
                <option value="">Select Team</option>
                <?php
                if (!empty($teams)) {
                    foreach ($teams as $team_row) {
                        echo "<option value='" . $team_row['idteam'] . "'>" . htmlspecialchars($team_row['name']) . "</option>";
                    }
                } else {
                    echo "<option disabled>No teams available</option>";
                }
                ?>
            </select>

            <button name="submit" type="submit">Save Event</button><br>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

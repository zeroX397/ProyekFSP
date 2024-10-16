<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Insert new event data
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $date = mysqli_real_escape_string($connection, string: $_POST['date']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']); 
    $team_id = mysqli_real_escape_string($connection, $_POST['team']); 

    $sql = "INSERT INTO `event`(`name`, `date`, `description`) VALUES (?, ?, ?);";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $name, $date, $desc);
    $result = mysqli_stmt_execute($stmt);


    $lasteventid = mysqli_insert_id($connection);


    $sqlevent_team = "INSERT INTO `event_teams`(`idevent`, `idteam`) VALUES (?, ?)";
    $stmtevent_team = mysqli_prepare($connection, $sqlevent_team);
    mysqli_stmt_bind_param($stmtevent_team, 'ii', $lasteventid, $team_id);
    $resultevent_team = mysqli_stmt_execute($stmtevent_team);
    if ($result) {
        echo "<script>alert('Game registration successful. You may see it on the event page.'); window.location.href='/admin/events/index.php';</script>";
    } else {
        $error = "Error during game registration.";
    }
}
$team_sql = "SELECT idteam, name FROM team";
$team_result = mysqli_query($connection, $team_sql);
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
    <title>Informatics E-Sport Club - Add Game</title>
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
            echo '<a class="logout" href="/logout.php">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo 
                '<div class="dropdown">
                    <a class="dropbtn" onclick="dropdownFunction()">Admin Sites
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
            }
        }
        ?>
    </nav>
    
    <!-- Admin Navigation Bar -->
    <nav class="topnav admin-nav">
        <a class="label">Administration Menus</a>
        <a href="/admin/teams/">Manage Teams</a>
        <a href="/admin/members/">Manage Members</a>
        <a href="/admin/events/">Manage Events</a>
        <a href="/admin/games/">Manage Games</a>
        <a href="/admin/achievements/">Manage Achievements</a>
        <a href="/admin/event_teams/">Manage Event-Teams</a>

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
                if ($team_result && mysqli_num_rows($team_result) > 0) {
                    while ($team_row = mysqli_fetch_assoc($team_result)) {
                        echo "<option value='" . $team_row['idteam'] . "'>" . $team_row['name'] . "</option>";
                    }
                }
                ?>
            </select>

            <button name="submit" type="submit">Save Event</button><br>
        </form>
    </div>
</body>

</html>

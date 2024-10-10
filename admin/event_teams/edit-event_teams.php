<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the event ID from the URL
if (isset($_POST['id_event'])) {
    $idevent = mysqli_real_escape_string($connection, $_POST['id_event']);

    // Fetch the event data to pre-fill the form
    $eventQuery = "SELECT * FROM event WHERE idevent = ?";
    $stmt = mysqli_prepare($connection, $eventQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idevent);
    mysqli_stmt_execute($stmt);
    $eventResult = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($eventResult);

    // If event not found, redirect back
    if (!$event) {
        echo "<script>alert('Event not found.'); window.location.href='/admin/events/index.php';</script>";
        exit();
    }
    $teamQuery = "SELECT idteam FROM event_teams WHERE idevent = ?";
    $stmt_team = mysqli_prepare($connection, $teamQuery);
    mysqli_stmt_bind_param($stmt_team, 'i', $idevent);
    mysqli_stmt_execute($stmt_team);
    $teamResult = mysqli_stmt_get_result($stmt_team);
    $current_team = mysqli_fetch_assoc($teamResult)['idteam'];

    // Fetch all teams for the dropdown
    $allTeamsQuery = "SELECT idteam, name FROM team";
    $teamsResult = mysqli_query($connection, $allTeamsQuery);
} else {
    header('Location: /admin/events/index.php');
    exit();
}

// Handle the form submission for updating the event
if (isset($_POST['submit'])) {
    
    $team_id = mysqli_real_escape_string($connection, $_POST['team']);

    $updateTeamQuery = "UPDATE event_teams SET idteam = ? WHERE idevent = ?";
    $stmt_team = mysqli_prepare($connection, $updateTeamQuery);
    mysqli_stmt_bind_param($stmt_team, 'ii', $team_id, $idevent);
    $result_team = mysqli_stmt_execute($stmt_team);

    if ($result_team) {
        echo "<script>alert('Event updated successfully.'); window.location.href='/admin/events/index.php';</script>";
    } else {
        $error = "Error during event update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <title>Informatics E-Sport Club - Edit Event Teams</title>
</head>

<body>
    <!-- Top Navigation bars -->
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
            echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
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
        <a href="/admin/event_teams/">Manage Event Teams</a>

    </nav>

    <!-- Form to Edit Event -->
    <div class="form">
        <?php if (isset($error)): ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
            <br><br><br>
            <table class="edit-table">
                
                    <td><label for="name">Event Name</label></td>
                    <td><span><?php echo htmlspecialchars($event['name']); ?></span></td>
                    <input type="hidden" name="id_event" value="<?php echo htmlspecialchars($idevent); ?>">

                <tr>
                    <td><label for="team">Team</label></td>
                    <td>
                        <select name="team" class="dropdown-menu" required>
                            <?php
                            while ($team = mysqli_fetch_assoc($teamsResult)) {
                                $selected = ($team['idteam'] == $current_team) ? 'selected' : '';
                                echo "<option value='" . $team['idteam'] . "' $selected>" . $team['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class="btnsubmit">Update Event</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>
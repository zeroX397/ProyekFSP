<?php
session_start();
require_once("event.php");

$event = new Event();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the event ID from the URL
if (isset($_POST['idevent'])) {
    $idevent = isset($_POST['idevent']) ? $_POST['idevent'] : $_GET['idevent'];

    // Fetch the event data to pre-fill the form
    $eventData = $event->getEventById($idevent);

    // If event not found, redirect back
    if (!$eventData) {
        echo "<script>alert('Event not found.'); window.location.href='/admin/events/index.php';</script>";
        exit();
    }

    // Fetch the current team associated with the event
    $currentTeam = $event->getTeamByEventId($idevent);

    // Fetch all teams for the dropdown
    $teams = $event->getAllTeams();
} else {
    header('Location: /admin/events/index.php');
    exit();
}

// Handle the form submission for updating the event
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Update the event data in the database
    $result = $event->updateEvent($idevent, $name, $date, $description);

    if ($result) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <title>Informatics E-Sport Club - Edit Event</title>
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

    <!-- Form to Edit Event -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
            <br><br><br>
            <table class="edit-table">
                <tr>
                    <td><label for="name">Event Name</label></td>
                    <td><input name="name" type="text" placeholder="Event Name" value="<?php echo htmlspecialchars($eventData['name']); ?>" required></td>
                    <input type="hidden" name="idevent" value="<?php echo htmlspecialchars($idevent); ?>">
                </tr>
                <tr>
                    <td><label for="date">Event Date</label></td>
                    <td><input name="date" type="date" value="<?php echo htmlspecialchars($eventData['date']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="description">Description</label></td>
                    <td><textarea style="width: 500px;" name="description" placeholder="Event Description" rows="10" required><?php echo htmlspecialchars($eventData['description']); ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class="btnsubmit">Update Event</button></td>
                </tr>
            </table>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
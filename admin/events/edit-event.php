<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the event ID from the URL
if (isset($_GET['id_event'])) {
    $idevent = mysqli_real_escape_string($connection, $_GET['id_event']);
    
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
    
} else {
    header('Location: /admin/events/index.php');
    exit();
}

// Handle the form submission for updating the event
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);

    // Update the event data in the database
    $updateQuery = "UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $date, $description, $idevent);
    $result = mysqli_stmt_execute($stmt);

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
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Informatics E-Sport Club - Edit Event</title>
</head>
<style>
    .btnsubmit {
        display: inline-block;
        padding: 10px 24px;
        background-color: #fa1c1c;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 0px;
        border: none;
        cursor: pointer;
    }
    .btnsubmit:hover {
        background-color: #bf1616;
    }
</style>
<body>
    <!-- Top Navigation bars -->
    <div class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/become-member.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
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
                <td><input name="name" type="text" placeholder="Event Name" value="<?php echo htmlspecialchars($event['name']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="date">Event Date</label></td>
                <td><input name="date" type="date" value="<?php echo htmlspecialchars($event['date']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="description">Description</label></td>
                <td><textarea name="description" placeholder="Event Description" required><?php echo htmlspecialchars($event['description']); ?></textarea></td>
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

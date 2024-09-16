<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Fetch team data to fill the dropdown
$teamQuery = "SELECT idteam, name FROM team;";
$teamResult = mysqli_query($connection, $teamQuery);
$teams = [];
if ($teamResult) {
    while ($teamRow = mysqli_fetch_assoc($teamResult)) {
        $teams[] = $teamRow;
    }
}

// Get the achievement ID from the URL
if (isset($_GET['idachievement'])) {
    $idachievement = mysqli_real_escape_string($connection, $_GET['idachievement']);
    
    // Fetch the achievement data to pre-fill the form
    $achievementQuery = "SELECT * FROM achievement WHERE idachievement = ?";
    $stmt = mysqli_prepare($connection, $achievementQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idachievement);
    mysqli_stmt_execute($stmt);
    $achievementResult = mysqli_stmt_get_result($stmt);
    $achievement = mysqli_fetch_assoc($achievementResult);

    // If achievement not found, redirect back
    if (!$achievement) {
        echo "<script>alert('Achievement not found.'); window.location.href='/admin/achievements/index.php';</script>";
        exit();
    }
    
} else {
    header('Location: /admin/achievements/index.php');
    exit();
}

// Handle the form submission for updating the achievement
if (isset($_POST['submit'])) {
    $idteam = mysqli_real_escape_string($connection, $_POST['idteam']);
    $achievement_name = mysqli_real_escape_string($connection, $_POST['achievement_name']);
    $achievement_date = mysqli_real_escape_string($connection, $_POST['achievement_date']);
    $achievement_description = mysqli_real_escape_string($connection, $_POST['achievement_description']);

    // Update the achievement data in the database
    $updateQuery = "UPDATE achievement SET idteam = ?, name = ?, date = ?, description = ? WHERE idachievement = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'isssi', $idteam, $achievement_name, $achievement_date, $achievement_description, $idachievement);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Achievement updated successfully.'); window.location.href='/admin/achievements/index.php';</script>";
    } else {
        $error = "Error during achievement update.";
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
    <link rel="stylesheet" href="/assets/styles/admin/achievements/edit-achievement.css">
    <title>Informatics E-Sport Club - Edit Achievement</title>
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
            // To check whether the user is an admin
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
    
    <!-- Form to Edit Achievement -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
        <br><br><br>
            <table class="edit-table">
                <tr>
                    <td><label for="idteam">Team</label></td>
                    <td>
                        <select name="idteam" required>
                            <option value="">Select Team</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?= $team['idteam'] ?>" <?= $team['idteam'] == $achievement['idteam'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($team['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="achievement_name">Achievement Name</label></td>
                    <td><input name="achievement_name" type="text" placeholder="Achievement Name" value="<?= htmlspecialchars($achievement['name']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="achievement_date">Achievement Date</label></td>
                    <td><input type="date" name="achievement_date" value="<?= htmlspecialchars($achievement['date']) ?>" required></td>
                </tr>
                <tr>
                    <td><label for="achievement_description">Achievement Description</label></td>
                    <td><textarea name="achievement_description" maxlength="300" rows="4" cols="50" placeholder="Description..." required><?= htmlspecialchars($achievement['description']) ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class="btnsubmit">Update</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>

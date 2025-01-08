<?php
session_start();
require_once("achievement.php");

$achievement = new Achievement();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Fetch team data to fill the dropdown
$teams = $achievement->getAllTeams();

// Get the achievement ID from the URL
if (isset($_POST['idachievement'])) {
    $idachievement = isset($_POST['idachievement']) ? $_POST['idachievement'] : $_GET['idachievement'];

    // Fetch the achievement data to pre-fill the form
    $achievementData = $achievement->getAchievementById($idachievement);

    // If achievement not found, redirect back
    if (!$achievementData) {
        echo "<script>alert('achievement not found.'); window.location.href='/admin/achievements/index.php';</script>";
        exit();
    }
} else {
    header('Location: /admin/achievements/index.php');
    exit();
}

// Handle the form submission for updating the achievement
if (isset($_POST['submit'])) {
    $idteam = $_POST['idteam'];
    $achievement_name = $_POST['achievement_name'];
    $achievement_date = $_POST['achievement_date'];
    $achievement_description = $_POST['achievement_description'];

    // Validate that team is selected
    if (empty($idteam)) {
        echo "<script>alert('Please select a team.'); window.location.href='edit-achievement.php?idachievement=" . $_POST['idachievement'] . "';</script>";
        exit();
    }

    // Update the achievement data in the database
    $result = $achievement->updateAchievement($idachievement, $idteam, $achievement_name, $achievement_date, $achievement_description);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/achievements/achievements.css">
    <title>Informatics E-Sport Club - Edit Achievement</title>
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
            // To check whether the user is an admin
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
                    <div class="dropdown-content" id="dd-proposal-page">
                        <a href="/admin/proposals/">Proposal List</a>
                        <a href="/admin/proposals/accepted">Accepted Proposals</a>
                    </div>
                </div>';
            }
        }
        ?>
    </nav>

    <!-- Edit Achievement Form -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <form action="edit-achievement.php" method="post" class="edit-form">
            <input type="hidden" name="idachievement" value="<?php echo htmlspecialchars($achievementData['idachievement']); ?>">
            <select name="idteam" required>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo htmlspecialchars($team['idteam']); ?>" <?php if ($achievementData['idteam'] == $team['idteam']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($team['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input name="achievement_name" type="text" placeholder="Achievement Name" value="<?php echo htmlspecialchars($achievementData['name']); ?>" required>
            <input type="date" name="achievement_date" value="<?php echo htmlspecialchars($achievementData['date']); ?>" required>
            <textarea name="achievement_description" rows="10" placeholder="Achievement Description" required><?php echo htmlspecialchars($achievementData['description']); ?></textarea>
            <button name="submit" type="submit" class="btnsubmit">Update</button>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
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

// Insert new achievement data
if (isset($_POST['submit'])) {
    $idteam = mysqli_real_escape_string($connection, $_POST['idteam']);
    $achievement_name = mysqli_real_escape_string($connection, $_POST['achievement_name']);
    $achievement_date = mysqli_real_escape_string($connection, $_POST['achievement_date']);
    $achievement_description = mysqli_real_escape_string($connection, $_POST['achievement_description']);

    $sql = "INSERT INTO `achievement`(`idteam`, `name`, `date`, `description`) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'isss', $idteam, $achievement_name, $achievement_date, $achievement_description);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        echo "<script>alert('Achievement registration successful. You may see in achievement page.'); window.location.href='/admin/achievements/index.php';</script>";
    } else {
        $error = "Error during registration.";
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
    <link rel="stylesheet" href="/assets/styles/admin/achievements/add-achievement.css">
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
    <!-- Form to Add New Team -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="add-form" method="post">
            <select name="idteam" required>
                <option value="">Select Team</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?= $team['idteam'] ?>"><?= $team['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input name="achievement_name" type="text" placeholder="Achievement Name" required>
            <input type="date" name="achievement_date" id="" required>
            <textarea class="achievement-text" name="achievement_description" maxlength="100" rows="4" cols="50"
                placeholder="Achievement's description" required></textarea>
            <button name="submit" type="submit">Save Team</button><br>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
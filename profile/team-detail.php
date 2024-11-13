<?php
session_start();
include("../config.php");

$idteam = isset($_GET['idteam']) ? intval($_GET['idteam']) : 0; // Default to 0 if not set

// Query to get detail team, members, achievements, dan events
$sql = "SELECT 
            t.name AS TeamName,
            e.name AS EventName,
            e.date AS EventDate,
            a.name AS AchievementName,
            a.date AS AchievementDate,
            m.username AS MemberName
        FROM 
            team t
            LEFT JOIN event_teams et ON t.idteam = et.idteam
            LEFT JOIN event e ON et.idevent = e.idevent
            LEFT JOIN achievement a ON a.idteam = t.idteam
            LEFT JOIN team_members tm ON tm.idteam = t.idteam
            LEFT JOIN member m ON m.idmember = tm.idmember
        WHERE 
            t.idteam = ?";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $idteam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if any results were returned
if ($result && mysqli_num_rows($result) > 0) {
    $firstRow = mysqli_fetch_assoc($result);
    $teamName = $firstRow['TeamName'];
} else {
    $teamName = "Team not found or no access";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/team-detail.css">
    <title>Team's Detail</title>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <?php
        if (!isset($_SESSION['username'])) {
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            $displayName = "Welcome, " . $_SESSION['username'];
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
        }
        ?>
    </nav>

    <!-- Begin team's details -->
    <h1><?= htmlspecialchars($teamName) ?></h1>
    <?php if ($teamName !== "Team not found or no access"): ?>
<h2>Members</h2>
        <table>
            <tr>
                <th>Member Name</th>
            </tr>
            <?php
            mysqli_data_seek($result, 0);
            $memberFound = false;
            $memberSet = []; 
            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($row['MemberName']) && !in_array($row['MemberName'], $memberSet)) {
                    echo "<tr><td>{$row['MemberName']}</td></tr>";
                    $memberSet[] = $row['MemberName']; 
                    $memberFound = true;
                }
            }
            if (!$memberFound) {
                echo "<tr><td>No members available.</td></tr>";
            }
            ?>
        </table>


<h2>Achievements</h2>
        <table>
            <tr>
                <th>Achievement Name</th>
                <th>Date Acquired</th>
            </tr>
            <?php
            mysqli_data_seek($result, 0);
            $achievementFound = false;
            $achievementSet = []; 
            while ($row = mysqli_fetch_assoc($result)) {
                $achievementKey = $row['AchievementName'] . $row['AchievementDate'];
                if (!empty($row['AchievementName']) && !in_array($achievementKey, $achievementSet)) {
                    echo "<tr><td>{$row['AchievementName']}</td><td>{$row['AchievementDate']}</td></tr>";
                    $achievementSet[] = $achievementKey; 
                    $achievementFound = true;
                }
            }
            if (!$achievementFound) {
                echo "<tr><td colspan='2'>No achievements available.</td></tr>";
            }
            ?>
        </table>


<h2>Events</h2>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Date Joined</th>
            </tr>
            <?php
            mysqli_data_seek($result, 0);
            $eventFound = false;
            $eventSet = []; 
            while ($row = mysqli_fetch_assoc($result)) {
                $eventKey = $row['EventName'] . $row['EventDate'];
                if (!empty($row['EventName']) && !in_array($eventKey, $eventSet)) {
                    echo "<tr><td>{$row['EventName']}</td><td>{$row['EventDate']}</td></tr>";
                    $eventSet[] = $eventKey; 
                    $eventFound = true;
                }
            }
            if (!$eventFound) {
                echo "<tr><td colspan='2'>No events available.</td></tr>";
            }
            ?>
        </table>

    <?php else: ?>
        <h2>You do not have access to view this team's details.</h2>
    <?php endif; ?>

    <script src="/assets/js/script.js"></script>
</body>

</html>
<?php
session_start();
include("config.php");

$idteam = isset($_GET['idteam']) ? intval($_GET['idteam']) : 0; // Default to 0 if not set

// Query to get detail teams alongside with achievement and event
$sql = "SELECT 
            t.name AS TeamName,
            e.name AS EventName,
            e.date AS EventDate,
            a.name AS AchievementName,
            a.date AS AchievementDate
        FROM 
            team t
            LEFT JOIN event_teams et ON t.idteam = et.idteam
            LEFT JOIN event e ON et.idevent = e.idevent
            LEFT JOIN achievement a ON a.idteam = t.idteam
        WHERE 
            t.idteam = ?;";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $idteam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if any results were returned
if ($result && mysqli_num_rows($result) > 0) {
    $firstRow = mysqli_fetch_assoc($result);
    $teamName = $firstRow['TeamName'];
} else {
    // No results were returned. Handle the case where the team has no details, events, or achievements.
    $teamName = "Team not found or no data available";
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
            echo '<a class="logout" href="/logout.php">Logout</a>';
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
    <!-- Begin team's details -->
    <h1><?= htmlspecialchars($teamName) ?></h1>
    <?php if ($teamName !== "Team not found or no data available"): ?>
        <h2>Achievements</h2>
        <table>
            <th>Achievement Name</th>
            <th>Date Acquired</th>
            <?php
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($row['AchievementName'])) {
                    echo 
                    "<tr>
                        <td>{$row['AchievementName']}</td>
                        <td>{$row['AchievementDate']}</td>
                    </tr>";
                }
                else {
                    echo 
                    "<tr>
                        <td colspan=2>Sorry, no data available. This team may not have any achievement yet.</td>
                    </tr>";
                }
            }
            ?>
        </table>

        <h2>Events Joined</h2>
        <table>
            <th>Event Name</th>
            <th>Date Joined</th>
            <?php
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($row['EventName'])) {
                    echo 
                    "<tr>
                        <td>{$row['EventName']}</td>
                        <td>{$row['EventDate']}</td>
                    </tr>";
                }
                else {
                    echo 
                    "<tr>
                        <td colspan=2>Sorry, no data available. This team may not join any event yet.</td>
                    </tr>";
                }
            }
            ?>
        </table>
    <?php else: ?>
        <h2>No details are available for this team. Please check another team or add new data.</h2>
    <?php endif; ?>
    <script src="/assets/js/script.js"></script>
</body>

</html>
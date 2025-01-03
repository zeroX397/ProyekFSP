<?php
session_start();
require_once("profile.php");

$profile = new Profile();

$idteam = isset($_GET['idteam']) ? intval($_GET['idteam']) : 0; // Default to 0 if not set

$result = $profile->getTeamDetails($idteam);

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
            // To check whether user is admin or not
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
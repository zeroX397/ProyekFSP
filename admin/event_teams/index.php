<?php
session_start();
require_once("event_team.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perPage = 5; 
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * $perPage;

// Initialize EventTeam object
$event_team = new EventTeam();

// Set up team filter
$team_filter = isset($_GET['team']) ? $_GET['team'] : "";
$totalData = $event_team->getEventTeamsCount($team_filter);
$totalpage = ceil($totalData / $perPage);

// Fetch achievements with team filter
$result = $event_team->getEventTeams($team_filter, $start, $perPage);

// Fetch list of all teams for dropdown filter
$teamResult = $event_team->getAllTeamsFilter();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/home.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/home.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Manage Event-Teams</title>
</head>

<body>
    <header>
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
        <div class="header-content">
            <h1 class="welcome-mssg">Manage Event-Teams</h1>
        </div>
    </header>

    <div class="all-member">
        <!-- Filter by Team -->
        <form method="get" action="index.php">
            <label for="team">Filter by Team:</label>
            <select name="team" id="team" onchange="this.form.submit()">
                <option value="">All Teams</option>
                <?php
                if ($teamResult && mysqli_num_rows($teamResult) > 0) {
                    while ($team = mysqli_fetch_assoc($teamResult)) {
                        $selected = ($team_filter == $team['team_id']) ? 'selected' : '';
                        echo "<option value='" . $team['team_id'] . "' $selected>" . $team['team_name'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
        <table>
            <tr>
                <th>Event</th>
                <th>Teams</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['event_name'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No event found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Paging -->
    <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='index.php?p=$prev&team=$team_filter'>Prev</a>"; // Previous page 
        }
        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; // Current page 
            } else {
                echo "<a href='index.php?p=$i&team=$team_filter'>$i</a>"; // Other page 
            }
        }
        if ($page < $totalpage) {
            $next = $page + 1;
            echo "<a href='index.php?p=$next&team=$team_filter'>Next</a>"; // Next page 
        }
        ?>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

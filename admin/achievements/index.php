<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 5; // Number sql per page
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}
$start = ($page - 1) * $perpage;

// Handle achievement filtering
$team_filter = "";
if (isset($_GET['team'])) {
    $team_filter = mysqli_real_escape_string($connection, $_GET['team']);
}

// SQL Filtering
$teamQuery = "SELECT DISTINCT team.name as team_name, team.idteam as team_id 
              FROM team 
              JOIN event_teams ON team.idteam = event_teams.idteam";
$teamResult = mysqli_query($connection, $teamQuery);

// SQL Total Count 
$sql_count = "SELECT COUNT(DISTINCT achievement.idachievement) AS total 
              FROM achievement
              INNER JOIN team ON team.idteam = achievement.idteam";

if (!empty($team_filter)) {
    $sql_count .= " WHERE team.idteam = '$team_filter'";
}

$result_count = mysqli_query($connection, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$totaldata = $row_count['total'];
$totalpage = ceil($totaldata / $perpage);

$sql = "SELECT achievement.idachievement, team.name AS team_name, achievement.name AS achievement_name, achievement.date AS achievement_date, 
        achievement.description AS achievement_description 
        FROM achievement 
        INNER JOIN team ON team.idteam = achievement.idteam";

$result = mysqli_query($connection, $sql);

if (!empty($team_filter)) {
    $sql .= " WHERE team.idteam = '$team_filter'";
}

$sql .= " ORDER BY team.idteam ASC 
          LIMIT $start, $perpage";
$result = mysqli_query($connection, $sql);

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
    <link rel="stylesheet" href="/assets/styles/admin/members/index.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Informatics E-Sport Club</title>
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
                            <a href="/admin/join_proposals/">Manage Proposal</a>
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
            <h1 class="welcome-mssg">Manage or Add Achievement</h1>
            <form action="add-achievement.php" class="add-new">
                <button type="submit">Add Achievement</button>
            </form>
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
    </div>

    <div class="all-team">
        <table>
            <tr>
                <th>Achievement ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Team</th>
                <th>Edit Team</th>
                <th>Delete Team</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['idachievement'] . "</td>";
                    echo "<td>" . $row['achievement_name'] . "</td>";
                    echo "<td>" . $row['achievement_date'] . "</td>";
                    echo "<td>" . $row['achievement_description'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='edit-achievement.php'>";
                    echo "<input type='hidden' name='idachievement' value='" . $row['idachievement'] . "'>";
                    echo "<button type='submit' name='editbtn' id='btn-editdelete' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='delete-achievement.php' method='post'>";
                    echo "<input type='hidden' name='idachievement' value='" . $row['idachievement'] . "'>";
                    echo "<button type='submit' name='deletebtn' id='btn-editdelete' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No achievement found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Paging -->
    <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='index.php?p=$prev'>Prev</a>"; // Previous page 
        }
        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; // Current page 
            } else {
                echo "<a href='index.php?p=$i'>$i</a>"; // Other page 
            }
        }
        if ($page < $totalpage) {
            $next = $page + 1;
            echo "<a href='index.php?p=$next'>Next</a>"; // Next page 
        }
        ?>
    </div>
    <script src="/assets/js/dropdown.js"></script>
</body>

</html>
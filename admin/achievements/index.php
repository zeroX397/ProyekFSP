<?php
session_start();
require_once("achievement.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perPage = 6; // Number of achievements per page
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * $perPage;

// Initialize Achievement object
$achievement = new Achievement();

// Set up team filter
$team_filter = isset($_GET['team']) ? $_GET['team'] : "";
$totalData = $achievement->getAchievementCount($team_filter);
$totalPage = ceil($totalData / $perPage);

// Fetch achievements with team filter
$result = $achievement->getAchievements($team_filter, $start, $perPage);

// Fetch list of all teams for dropdown filter
$teamResult = $achievement->getAllTeamsFilter();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css?v= time(), ?>">
    <!-- <link rel="stylesheet" href="/assets/styles/admin/main.css"> -->
    <link rel="stylesheet" href="/assets/styles/admin/achievements/achievements.css?v= time(), ?>">
    <title>Manage Achievements</title>
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
            <h1 class="welcome-mssg">Manage or Add Achievement</h1>
            <form action="add-achievement.php" class="add-new">
                <button type="submit">Add Achievement</button>
            </form>
        </div>
    </header>

    <div class="filter">
        <!-- Filter by Team -->
        <form method="get" action="index.php">
            <label for="team">Filter by Team:</label>
            <select name="team" id="team" onchange="this.form.submit()">
                <option value="">All Teams</option>
                <?php
                // Populate the dropdown with team options
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

    <div class="all-member">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<div class='all-member'>"; // Start main container
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='container'>";
                echo "<div class='content'>";
                echo "<div class='title'>" . htmlspecialchars($row['achievement_name']) . "</div>";
                echo "<div class='details'>Name: " . htmlspecialchars($row['team_name']) . "</div>";
                echo "<div class='details'>ID: " . htmlspecialchars($row['idachievement']) . "</div>";
                echo "<div class='details'>Date: " . htmlspecialchars($row['achievement_date']) . "</div>";
                echo "<div class='description-area'> " . htmlspecialchars($row['achievement_description']) . "</div>";
                echo "</div>";

                echo "<div class='buttons'>";
                echo "<form action='edit-achievement.php' method='post'>";
                echo "<input type='hidden' name='idachievement' value='" . htmlspecialchars($row['idachievement']) . "'>";
                echo "<button type='submit' class='edit'>Edit</button>";
                echo "</form>";

                echo "<form action='delete-achievement.php' method='post' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='idachievement' value='" . htmlspecialchars($row['idachievement']) . "'>";
                echo "<button type='submit' class='delete'>Delete</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>"; // End main container
        } else {
            echo "<div>No achievement found</div>";
        }
        ?>
    </div>

    <!-- Paging -->
    <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='index.php?p=$prev'>Prev</a>"; // Previous page 
        }
        for ($i = 1; $i <= $totalPage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; // Current page 
            } else {
                echo "<a href='index.php?p=$i'>$i</a>"; // Other page 
            }
        }
        if ($page < $totalPage) {
            $next = $page + 1;
            echo "<a href='index.php?p=$next'>Next</a>"; // Next page 
        }
        ?>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
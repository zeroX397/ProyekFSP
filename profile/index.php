<?php
session_start();
require_once("profile.php");

$profile = new Profile();

$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

if ($idmember) {

    $result = $profile->getJoinedTeams($idmember);
    $proposals = $profile->getJoinProposals($idmember);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/proposal/waiting.css">
    <link rel="stylesheet" href="/assets/styles/profile/main.css">
    <title>Profile</title>
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

    <main>
        <h1>Hello <?php echo $_SESSION['fname'] . " " . $_SESSION['lname'] ?>!</h1>
        <h2>Joined Team</h2>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='container'>";
                echo "<div class='content'>";
                echo "<div class='title'>Team ID: " . htmlspecialchars($row['idteam']) . "</div>";
                echo "<div class='details'>Team Name: " . htmlspecialchars($row['team_name']) . "</div>";
                echo "<form action='team-detail.php' method='get'>";
                echo "<input type='hidden' name='idteam' value='" . $row['idteam'] . "'>";
                echo "<input type='submit' id='detail-btn' value='Details'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div>No proposals found</div>";
        }
        ?>
        <h2>Your Proposal Status</h2>
        <?php
        if ($result && mysqli_num_rows($proposals) > 0) {
            while ($row = mysqli_fetch_assoc($proposals)) {
                echo "<div class='container'>";
                echo "<div class='content'>";
                echo "<div class='title'>Proposal ID: " . htmlspecialchars($row['idjoin_proposal']) . "</div>";
                echo "<div class='details'>Team Name: " . htmlspecialchars($row['team_name']) . "</div>";
                if ($row['status'] == 'approved') {
                    $tdclass = 'approved';
                } else if ($row['status'] == 'waiting') {
                    $tdclass = 'waiting';
                } else if ($row['status'] == 'rejected') {
                    $tdclass = 'rejected';
                }
                echo "<td class='td-status $tdclass'>" . strtoupper($row['status']);
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div>Sorry, no proposals found. Find a team <a href='/teams.php'>here</a></div>";
        }
        ?>
    </main>
    <script src="/assets/js/script.js"></script>
</body>

</html>
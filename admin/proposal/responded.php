<?php
session_start();
require_once("proposal.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perPage = 5;
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * $perPage;

$proposal = new Proposal();
$totalData = $proposal->getTotalRespondedProposals();
$totalPage = ceil($totalData / $perPage);

// Fetch responded proposals
$respondedProposals = $proposal->getRespondedProposals($start, $perPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/proposal/index.css">
    <title>Manage Join Proposals - Responded Proposal</title>
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

    <div class="header-content">
        <h1 class="welcome-mssg">Manage Join Proposals - Responded</h1>
    </div>

    <div class="all-proposals">
        <table>
            <tr>
                <th>Proposal ID</th>
                <th>Member Name</th>
                <th>Team Name</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php
            // Ensure we have data to display
            if (!empty($respondedProposals)) {
                foreach ($respondedProposals as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['idjoin_proposal'] . "</td>";
                    echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    // Code for checking 
                    if ($row['status'] == 'approved') {
                        $tdclass = 'approved';
                    } else if ($row['status'] == 'waiting') {
                        $tdclass = 'waiting';
                    } else {
                        $tdclass = 'rejected';
                    }
                    echo "<td class='td-status $tdclass'>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No proposals found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Paging -->
    <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='responded.php?p=$prev'>Prev</a>";
        }

        for ($i = 1; $i <= $totalPage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>";
            } else {
                echo "<a href='responded.php?p=$i'>$i</a>";
            }
        }

        if ($page < $totalPage) {
            $next = $page + 1;
            echo "<a href='responded.php?p=$next'>Next</a>";
        }
        ?>
    </div>

    <script src="/assets/js/script.js"></script>
</body>

</html>
<?php
session_start();
require_once("proposal.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 5;
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * $perpage;

$proposal = new Proposal();
$totaldata = $proposal->getTotalProposals();
$totalpage = ceil($totaldata / $perpage);
$result = $proposal->getAllProposals($start, $perpage);
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
    <link rel="stylesheet" href="/assets/styles/admin/proposal/waiting.css">
    <title>Manage Join Proposals - Waiting for Approval</title>
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

    <div class="header-content">
        <h1 class="welcome-mssg">Manage Join Proposals - Waiting Approval</h1>
    </div>

    <div class="all-proposals">
        <table>
            <tr>
                <th>Proposal ID</th>
                <th>Member Name</th>
                <th>Team Name</th>
                <th>Description</th>
                <th>Status</th>
                <th colspan="2">Action</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['idjoin_proposal'] . "</td>";
                    echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td class='td-status waiting'>" . $row['status'] . "</td>";

                    if ($row['status'] == 'waiting') {
                        // Accept button 
                        echo "<td>";
                        echo "<form method='post' action='approve-proposal.php'>"; 
                        echo "<input type='hidden' name='idjoin_proposal' value='" . $row['idjoin_proposal'] . "'>";
                        echo "<button type='submit' name='accept' class='accept'>Accept</button>";
                        echo "</form>";
                        echo "</td>";

                        // Reject button
                        echo "<td>";
                        echo "<form method='post' action='reject-proposal.php'>"; 
                        echo "<input type='hidden' name='idjoin_proposal' value='" . $row['idjoin_proposal'] . "'>";
                        echo "<button type='submit' name='reject' class='reject'>Reject</button>";
                        echo "</form>";
                        echo "</td>";
                    } else {
                        // Display approved or rejected
                        echo "<td colspan='2'>" . $row['status'] . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No proposals found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Paging -->
    <div class="paging">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<a href='waiting.php?p=$prev'>Prev</a>"; 
        }

        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; 
            } else {
                echo "<a href='waiting.php?p=$i'>$i</a>"; 
            }
        }

        if ($page < $totalpage) {
            $next = $page + 1;
            echo "<a href='waiting.php?p=$next'>Next</a>"; 
        }
        ?>
    </div>

    <script src="/assets/js/script.js"></script>
</body>

</html>

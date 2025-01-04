<?php
session_start();
require_once("proposal.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 6;
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
    <link rel="stylesheet" href="/assets/styles/admin/proposal/waiting.css?v=<?= time(); ?>">
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

    <h1 class="welcome-mssg">Manage Join Proposals - Waiting Approval</h1>

    <div class="all-member">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<div class='all-member'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='container'>";
                echo "<div class='content'>";
                echo "<div class='title'>Proposal ID: " . htmlspecialchars($row['idjoin_proposal']) . "</div>";
                echo "<div class='details'>Member Name: " . htmlspecialchars($row['fname'] . " " . $row['lname']) . "</div>";
                echo "<div class='details'>Team Name: " . htmlspecialchars($row['team_name']) . "</div>";
                echo "<div class='description-area'>Description: " . htmlspecialchars($row['description']) . "</div>";
                echo "<div class='status-area'>Status: <span class='td-status " . htmlspecialchars($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></div>";
                echo "</div>";

                if ($row['status'] == 'waiting') {
                    echo "<div class='buttons'>";
                    // Accept button
                    echo "<form action='approve-proposal.php' method='post'>";
                    echo "<input type='hidden' name='idjoin_proposal' value='" . htmlspecialchars($row['idjoin_proposal']) . "'>";
                    echo "<button type='submit' name='accept' class='accept'>Accept</button>";
                    echo "</form>";

                    // Reject button
                    echo "<form action='reject-proposal.php' method='post'>";
                    echo "<input type='hidden' name='idjoin_proposal' value='" . htmlspecialchars($row['idjoin_proposal']) . "'>";
                    echo "<button type='submit' name='reject' class='reject'>Reject</button>";
                    echo "</form>";
                    echo "</div>";
                } else {
                    // Display approved or rejected
                    echo "<div class='buttons'>";
                    echo "<span>" . htmlspecialchars(ucfirst($row['status'])) . "</span>";
                    echo "</div>";
                }

                echo "</div>"; // Close container
            }
            echo "</div>"; // Close all-member
        } else {
            echo "<div>No proposals found</div>";
        }
        ?>
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

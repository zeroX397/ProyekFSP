<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 5; // Number of entries per page
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1; 
}
$start = ($page - 1) * $perpage;

// Count total join proposals
$sql_count = "SELECT COUNT(DISTINCT join_proposal.idjoin_proposal) AS total 
              FROM join_proposal
              INNER JOIN team ON team.idteam = join_proposal.idteam
              INNER JOIN member ON member.idmember = join_proposal.idmember";
$result_count = mysqli_query($connection, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$totaldata = $row_count['total'];
$totalpage = ceil($totaldata / $perpage);

// Fetch join proposals data
$sql = "SELECT join_proposal.idjoin_proposal, member.fname, member.lname, team.name AS team_name, join_proposal.description, join_proposal.status
        FROM join_proposal 
        INNER JOIN team ON team.idteam = join_proposal.idteam
        INNER JOIN member ON member.idmember = join_proposal.idmember
        ORDER BY join_proposal.idjoin_proposal ASC 
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
    <title>Manage Join Proposals</title>
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
                echo '<a class="active" href="/login.php">Login</a>';
            } else {
                $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username'];
                echo '<a class="logout" href="/logout.php">Logout</a>';
                echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
                if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                    echo 
                    '<div class="dropdown">
                        <a class="dropbtn" onclick="dropdownFunction()">Admin Sites
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-content" id="dd-admin-page">
                            <a href="/admin/teams/">Manage Teams</a>
                            <a href="/admin/members/">Manage Members</a>
                            <a href="/admin/events/">Manage Events</a>
                            <a href="/admin/games/">Manage Games</a>
                            <a href="/admin/achievements/">Manage Achievements</a>
                            <a href="/admin/event_teams/">Manage Event-Teams</a>
                            <a href="/admin/join_proposals/">Manage Join Proposals</a>
                        </div>
                    </div>';
                }
            }
            ?>
        </nav>
        <div class="header-content">
            <h1 class="welcome-mssg">Manage Join Proposals</h1>
        </div>
    </header>

    <div class="all-proposals">
        <table>
            <tr>
                <th>Proposal ID</th>
                <th>Member Name</th>
                <th>Team Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Accept Proposal</th>
                <th>Reject Proposal</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['idjoin_proposal'] . "</td>";
                    echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";

                    if ($row['status'] == 'waiting') {
                        // Accept button column
                        echo "<td>";
                        echo "<form method='post' action='process-proposal.php'>";
                        echo "<input type='hidden' name='idjoin_proposal' value='" . $row['idjoin_proposal'] . "'>";
                        echo "<button type='submit' name='accept' class='accept'>Accept</button>";
                        echo "</form>";
                        echo "</td>";

                        // Reject button column
                        echo "<td>";
                        echo "<form method='post' action='process-proposal.php'>";
                        echo "<input type='hidden' name='idjoin_proposal' value='" . $row['idjoin_proposal'] . "'>";
                        echo "<button type='submit' name='reject' class='reject'>Reject</button>";
                        echo "</form>";
                        echo "</td>";
                    } else {
                        // Display the final status if not waiting in both columns
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
            echo "<a href='index.php?p=$prev'>Prev</a>"; 
        }

        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong>"; 
            } else {
                echo "<a href='index.php?p=$i'>$i</a>"; 
            }
        }

        if ($page < $totalpage) {
            $next = $page + 1;
            echo "<a href='index.php?p=$next'>Next</a>"; 
        }
        ?>
    </div>
    <script src="/assets/js/dropdown.js"></script>
</body>

</html>

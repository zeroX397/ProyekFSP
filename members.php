<?php
session_start();
include("config.php");


// Paging configuration
$perpage = 5; // Number sql per page
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}
$start = ($page - 1) * $perpage;

$sql_count = "SELECT COUNT(*) AS total FROM member";
$result_count = mysqli_query($connection, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$totaldata = $row_count['total'];
$totalpage = ceil($totaldata / $perpage);

// Query to get member ordered by game
$sql = "SELECT member.idmember, member.username, CONCAT(member.fname, ' ', member.lname) as member_name 
        FROM member
        LIMIT $start, $perpage;";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/[CHANGE LATER].css">
    <title>All Members List</title>
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
    <section>
        <h1 class="hello-mssg">Hello! You can see full list of all members.</h1>
        <div class="all-member">
            <table>
                <tr>
                    <th>Member ID</th>
                    <th>Username</th>
                    <th>Member Name</th>
                    <th>Detail</th>
                </tr>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $current_game_id = null;

                    while ($row = mysqli_fetch_assoc($result)) {
                        // If the game changes, print a new game name header
                        if ($current_game_id !== $row['idmember']) {
                            $current_game_id = $row['idmember'];
                            // echo "<tr><td colspan='5'><strong>" . $row['idmember'] . "</strong></td></tr>";
                        }
                        // Print member data
                        echo "<tr>";
                        echo "<td>" . $row['idmember'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['member_name'] . "</td>";
                        // View Member Details
                        echo "<td>";
                        echo "<form action='member-detail.php' method='post'>";
                        echo "<input type='hidden' name='idmember' value='" . $row['idmember'] . "'>";
                        echo "<button type='submit' name='joinbtn' id='btn-join' class='button'>Detail</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No members found</td></tr>";
                }
                ?>
            </table>
        </div>
        <!-- Paging -->
        <div class="paging">
            <?php
            if ($page > 1) {
                $prev = $page - 1;
                echo "<a href='members.php?p=$prev'>Prev</a>"; // Previous page 
            }

            for ($i = 1; $i <= $totalpage; $i++) {
                if ($i == $page) {
                    echo "<strong>$i</strong>"; // Current page 
                } else {
                    echo "<a href='members.php?p=$i'>$i</a>"; // Other page 
                }
            }

            if ($page < $totalpage) {
                $next = $page + 1;
                echo "<a href='members.php?p=$next'>Next</a>"; // Next page 
            }
            ?>
        </div>
    </section>
    <script src="/assets/js/script.js"></script>
</body>

</html>
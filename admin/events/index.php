<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 5; // Number of results per page
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}
$start = ($page - 1) * $perpage;

// SQL to count total records in the event table
$sql_count = "SELECT COUNT(DISTINCT idevent) AS total FROM event";
$result_count = mysqli_query($connection, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$totaldata = $row_count['total'];
$totalpage = ceil($totaldata / $perpage);

// SQL to retrieve paginated data from the event table
$sql = "SELECT idevent, name, date, description
        FROM `event`
        ORDER BY idevent ASC
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
    <title>Manage Events</title>
</head>

<body>
    <header>
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
                echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
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
            <h1 class="welcome-mssg">Manage Events</h1>
            <form action="add-event.php" class="add-new">
                <button type="submit" class="">Add New Event</button>
            </form>
        </div>
    </header>

    <div class="all-member">
        <table>
            <tr>
                <th>Event ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Edit Event</th>
                <th>Delete Event</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['idevent'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='edit-event.php'>";
                    echo "<input type='hidden' name='idevent' value='" . $row['idevent'] . "'>";
                    echo "<button type='submit' name='editbtn' id='btn-editdelete' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='delete-event.php' method='post' onsubmit='return confirmDelete()'>";
                    echo "<input type='hidden' name='idevent' value='" . $row['idevent'] . "'>";
                    echo "<button type='submit' name='deletebtn' id='btn-editdelete' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No event found</td></tr>";
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

    <script src="/assets/js/script.js"></script>
</body>

</html>

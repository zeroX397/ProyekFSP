<?php
session_start();
require_once("event.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Paging configuration
$perpage = 6;
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * $perpage;

$event = new Event();
$totaldata = $event->getTotalEvents();
$totalpage = ceil($totaldata / $perpage);
$result = $event->getAllEvents($start, $perpage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css?v= time(), ?>">
    <!-- <link rel="stylesheet" href="/assets/styles/admin/main.css"> -->
    <link rel="stylesheet" href="/assets/styles/admin/events/events.css?v= time(), ?>">
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
            <h1 class="welcome-mssg">Manage Events</h1>
            <form action="add-event.php" class="add-new">
                <button type="submit" class="">Add New Event</button>
            </form>
        </div>
    </header>

    <div class="all-member">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<div class='all-member'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='container'>";
                echo "<div class='content'>";
                echo "<div class='title'>" . htmlspecialchars($row['name']) . "</div>";
                echo "<div class='details'>ID: " . htmlspecialchars($row['idevent']) . "</div>";
                echo "<div class='details'>Date: " . htmlspecialchars($row['date']) . "</div>";
                echo "<div class='description-area'>" . htmlspecialchars($row['description']) . "</div>";
                echo "</div>";

                echo "<div class='buttons'>";
                echo "<form action='edit-event.php' method='post'>";
                echo "<input type='hidden' name='idevent' value='" . $row['idevent'] . "'>";
                echo "<button type='submit' name='editbtn' class='edit'>Edit</button>";
                echo "</form>";

                echo "<form action='delete-event.php' method='post' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='idevent' value='" . $row['idevent'] . "'>";
                echo "<button type='submit' name='deletebtn' class='delete'>Delete</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div>No events found</div>";
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

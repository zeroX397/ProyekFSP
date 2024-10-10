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

// $sql_count = "SELECT COUNT(*) AS total FROM game";
$sql_count = "SELECT COUNT(DISTINCT game.idgame) AS total 
              FROM game";
$result_count = mysqli_query($connection, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$totaldata = $row_count['total'];
$totalpage = ceil($totaldata / $perpage);

$sql = "SELECT game.idgame as id_game, game.name, game.description
        FROM `game` 
        ORDER BY game.idgame ASC 
        LIMIT $start, $perpage";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/teams/home.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/index.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Informatics E-Sport Club</title>
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
                echo '<a class="logout" href="/logout.php">Logout</a>';
                echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
                // To check whether is admin or not
                if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                    echo '<a href="/admin/">Admin Site</a>';
                }
            }
            ?>
        </nav>
        <!-- Admin Navigation Bar -->
        <nav class="topnav admin-nav">
            <a class="label">Administration Menus</a>
            <a href="/admin/teams/">Manage Teams</a>
            <a href="/admin/members/">Manage Members</a>
            <a href="/admin/events/">Manage Events</a>
            <a href="/admin/games/">Manage Games</a>
            <a href="/admin/achievements/">Manage Achievements</a>
        </nav>
        <div class="header-content">
            <h1 class="welcome-mssg">Manage Games</h1>
            <form action="add-game.php" class="add-new">
                <button type="submit" class="">Add New Game</button>
            </form>
        </div>


    </header>
    <!-- Top Navigation Bar -->

    <div class="all-member">
        <table>
            <tr>
                <th>Game ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Edit Member</th>
                <th>Delete Member</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_game'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='edit-game.php'>";
                    echo "<input type='hidden' name='id_game' value='" . $row['id_game'] . "'>";
                    echo "<button type='submit' name='editbtn' id='btn-editdelete' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='delete-game.php' method='post'>";
                    echo "<input type='hidden' name='id_game' value='" . $row['id_game'] . "'>";
                    echo "<button type='submit' name='deletebtn' id='btn-editdelete' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No game found</td></tr>";
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
</body>

</html>
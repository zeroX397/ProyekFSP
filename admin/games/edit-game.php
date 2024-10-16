<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the game ID from the URL or form submission
if (isset($_POST['id_game'])) {
    $idgame = mysqli_real_escape_string($connection, $_POST['id_game']);

    $gameQuery = "SELECT * FROM game WHERE idgame = ?";
    $stmt = mysqli_prepare($connection, $gameQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idgame);
    mysqli_stmt_execute($stmt);
    $gameResult = mysqli_stmt_get_result($stmt);
    $game = mysqli_fetch_assoc($gameResult);

    // If game not found, redirect back
    if (!$game) {
        echo "<script>alert('Game not found.'); window.location.href='/admin/games/index.php';</script>";
        exit();
    }
} else {
    header('Location: /admin/games/index.php');
    exit();
}

// Handle the form submission for updating the game
if (isset($_POST['submit']) && isset($_POST['id_game'])) {
    $idgame = mysqli_real_escape_string($connection, $_POST['id_game']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']);

    // Update the game data in the database
    $updateQuery = "UPDATE game SET name = ?, description = ? WHERE idgame = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $desc, $idgame);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Game updated successfully.'); window.location.href='/admin/games/index.php';</script>";
    } else {
        $error = "Error during game update: " . mysqli_error($connection); // Detail error dari MySQL
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/games/edit-game.css">
    <title>Informatics E-Sport Club - Edit Game</title>
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
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
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
                    </div>
                </div>';
            }
        }
        ?>
    </nav>
    <!-- Form to Edit Game -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
            <!-- Hidden input for game ID -->
            <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($game['idgame']); ?>">
            <form action="" class="add-form" method="post">
                <input name="name" type="text" placeholder="Game Name" value="<?php echo htmlspecialchars($game['name']); ?>" required>
                <textarea name="description" rows="10" placeholder="Game Description" required><?php echo htmlspecialchars($game['description']); ?></textarea>
                <button name="submit" type="submit" class="btnsubmit">Update</button>
            </form>
        </form>
    </div>
    <script src="/assets/js/dropdown.js"></script>
</body>

</html>
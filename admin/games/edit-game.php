<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the game ID from the URL
if (isset($_GET['id_game'])) {
    $idgame = mysqli_real_escape_string($connection, $_GET['id_game']);
    
    // Fetch the game data to pre-fill the form
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
if (isset($_POST['submit'])) {
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
        $error = "Error during game update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/games/edit-game.css">
    <title>Informatics E-Sport Club - Edit Game</title>
</head>
<style>
    
</style>
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
            echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
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
    
    <!-- Form to Edit Game -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
        <br><br><br>
            <table class="edit-table">
                <tr>
                    <td><label for="name">Game Name</label></td>
                    <td><input name="name" type="text" placeholder="Game Name" value="<?php echo htmlspecialchars($game['name']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="description">Game Description</label></td>
                    <td><textarea name="description" rows="4" cols="50" placeholder="Game Description" required><?php echo htmlspecialchars($game['description']); ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class="btnsubmit">Update</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>

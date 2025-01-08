<?php
session_start();
require_once("game.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Create Game instance
$game = new Game();

// Get the game ID from the URL or form submission
if (isset($_POST['id_game']) || isset($_GET['id_game'])) {
    $idgame = isset($_POST['id_game']) ? $_POST['id_game'] : $_GET['id_game'];
    
    // Fetch game data by ID
    $gameData = $game->getGameById($idgame);
    if ($gameData) {
        $gameInfo = $gameData; 
    } else {
        echo "<script>alert('Game not found.'); window.location.href='/admin/games/index.php';</script>";
        exit();
    }
} else {
    header('Location: /admin/games/index.php');
    exit();
}

// Handle form submission for updating the game
if (isset($_POST['submit']) && isset($_POST['id_game'])) {
    $idgame = $_POST['id_game'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Update the game data 
    if ($game->updateGame($idgame, $name, $description)) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/games/games.css">
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
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username'];
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
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
    
    <!-- Form to Edit Game -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
            <!-- Hidden input for game ID -->
            <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($gameInfo['idgame']); ?>">
            <input name="name" type="text" placeholder="Game Name" value="<?php echo htmlspecialchars($gameInfo['name'] ?? ''); ?>" required>
            <textarea name="description" rows="10" placeholder="Game Description" required><?php echo htmlspecialchars($gameInfo['description'] ?? ''); ?></textarea>
            <button name="submit" type="submit" class="btnsubmit">Update</button>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

<?php
session_start();
require_once("team.php");

$team = new Team();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Fetch game data to fill the dropdown
$games = $team->getAllGames();

// Insert new team data
if (isset($_POST['submit'])) {
    $idgame = $_POST['idgame'];
    $team_name = $_POST['team_name'];
    // Assuming you have already inserted the team data, so $idteam is available.
    $idteam = $row['idteam'] ?? $team->getLastInsertedTeamId();

    // Periksa apakah file diupload
    if (isset($_FILES['team_picture']) && $_FILES['team_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/../../assets/img/team_picture/";
        $finalFileName = $uploadDir . $idteam . ".jpg"; // Set final filename directly

        // Validasi file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = mime_content_type($_FILES['team_picture']['tmp_name']);

        // Debugging output untuk mengecek MIME type
        error_log('File type detected: ' . $fileType);
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['team_picture']['tmp_name'], $finalFileName)) {
                $imgPath = "/assets/img/team_picture/" . $idteam . ".jpg?" . time();

                echo "<script>
                alert('Team registration successful with logo.');
                window.location.href='/admin/teams/index.php?img={$imgPath}';
            </script>";
            } else {
                $error = "Error moving the uploaded file to the target location.";
            }
        } else {
            $error = "Invalid file type. Only JPG and PNG files are allowed.";
        }
    } else {
        $error = "No file uploaded or an upload error occurred. Error code: ";
    }

    // Jika terjadi error
    if (isset($error)) {
        echo "<script>alert('{$error}');</script>";
    }



    if ($team->addTeam($idgame, $team_name)) {
        echo "<script>alert('Team registration successful.'); window.location.href='/admin/teams/index.php';</script>";
    } else {
        $error = "Error during team registration.";
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
    <link rel="stylesheet" href="/assets/styles/admin/teams/add-team.css">
    <title>Add a Team</title>
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
    <!-- Form to Add New Team -->
    <div class="form">
        <?php if (isset($error)): ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="add-form" method="post">
            <select name="idgame" required>
                <option value="">Select Game</option>
                <?php foreach ($games as $game): ?>
                    <option value="<?= $game['idgame'] ?>"><?= $game['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input name="team_name" type="text" placeholder="Team Name" required>
            <input type="file" name="team_picture" accept="image/*">
            <button name="submit" type="submit">Save Team</button><br>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
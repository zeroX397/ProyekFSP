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

// Function to handle file upload
function handleFileUpload($file, $idteam) {
    $uploadDir = __DIR__ . "/../../assets/img/team_picture/";
    $finalFileName = $uploadDir . $idteam . ".jpg";

    // Validate file type
    $allowedTypes = ['image/jpeg'];
    $fileType = mime_content_type($file['tmp_name']);
    $fileError = $file['error'];

    if ($fileError === UPLOAD_ERR_OK) {
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($file['tmp_name'], $finalFileName)) {
                return "/assets/img/team_picture/" . $idteam . ".jpg?" . time(); 
            } else {
                throw new Exception("Failed to move the uploaded file.");
            }
        } else {
            throw new Exception("Invalid file type. Only JPG are allowed.");
        }
    } else {
        throw new Exception("File upload error (code: $fileError).");
    }
}

// Insert new team data
if (isset($_POST['submit'])) {
    try {
        $idgame = $_POST['idgame'];
        $team_name = $_POST['team_name'];

        if ($team->addTeam($idgame, $team_name)) {
            $idteam = $team->getLastInsertedTeamId();

            // Check if a file is uploaded
            if (isset($_FILES['team_picture']) && $_FILES['team_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                $imgPath = handleFileUpload($_FILES['team_picture'], $idteam);
                echo "<script>
                alert('Team registration successful with logo.');
                window.location.href='/admin/teams/index.php?img={$imgPath}';
                </script>";
            } else {
                echo "<script>
                alert('Team registration successful without logo.');
                window.location.href='/admin/teams/index.php';
                </script>";
            }
        } else {
            throw new Exception("Error during team registration.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
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
    <link rel="stylesheet" href="/assets/styles/admin/teams/teams.css">
    <title>Add a Team</title>
</head>

<body>
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

    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <form action="" class="add-form" method="post" enctype="multipart/form-data">
            <select name="idgame" required>
                <option value="">Select Game</option>
                <?php foreach ($games as $game): ?>
                    <option value="<?= $game['idgame'] ?>"> <?= htmlspecialchars($game['name']) ?> </option>
                <?php endforeach; ?>
            </select>
            <input name="team_name" type="text" placeholder="Team Name" required>
            <textarea class="application-text" name="team_description" maxlength="100" rows="4"
            placeholder="Team's description" required></textarea>
            <input type="file" name="team_picture" accept="image/jpeg,image/png">
            <button name="submit" type="submit">Save Team</button><br>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

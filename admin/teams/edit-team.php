<?php
session_start();
require_once("team.php");

$team = new Team();

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Fetch game data to fill the dropdown
$games = $team->getAllGames();

// Check if team ID is provided
if (!isset($_POST['idteam']) && !isset($_GET['idteam'])) {
    echo "<script>alert('Team ID not provided.'); window.location.href='/admin/teams/';</script>";
    exit();
}

$idteam = isset($_POST['idteam']) ? $_POST['idteam'] : $_GET['idteam'];
$teamInfo = $team->getTeamById($idteam);

if (!$teamInfo) {
    echo "<script>alert('Team not found.'); window.location.href='/admin/teams/index.php';</script>";
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $idgame = $_POST['idgame'];
    $team_name = $_POST['team_name'];
    $uploadDir = __DIR__ . "/../../assets/img/team_picture/";
    $uploadFile = $uploadDir . $idteam . ".jpg";

    // Check if a new file is uploaded
    if (isset($_FILES['team_picture']) && $_FILES['team_picture']['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['team_picture']['tmp_name']);
        $allowedTypes = ['image/jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Invalid file type. Only JPG is allowed.";
        } elseif ($_FILES['team_picture']['size'] > $maxFileSize) {
            $error = "File size should not exceed 2MB.";
        } else {
            // Remove existing file if exists
            if (file_exists($uploadFile)) {
                unlink($uploadFile);
            }

            // Upload new file
            if (!move_uploaded_file($_FILES['team_picture']['tmp_name'], $uploadFile)) {
                $error = "Error uploading the logo.";
            }
        }
    }

    // Update team details in the database
    if (!isset($error)) {
        if ($team->updateTeam($idteam, $idgame, $team_name)) {
            echo "<script>alert('Team updated successfully.'); window.location.href='/admin/teams/index.php';</script>";
            exit();
        } else {
            $error = "Error during team update.";
        }
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
    <link rel="stylesheet" href="/assets/styles/admin/teams/team.css">
    <title>Edit Team - Informatics E-Sport Club</title>
</head>

<body>
    <nav class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/how-to-join.php">How to Join</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>
            <a class="active" href="/profile"><?= htmlspecialchars($_SESSION['username']) ?></a>
        <?php else: ?>
            <a class="active" href="/login.php">Login</a>
        <?php endif; ?>
    </nav>

    <div class="form">
        <?php if (isset($error)): ?>
            <div style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data" class="edit-form">
            <input type="hidden" name="idteam" value="<?= htmlspecialchars($teamInfo['idteam']) ?>">
            
            <label for="idgame">Select Game</label>
            <select name="idgame" required>
                <?php foreach ($games as $game): ?>
                    <option value="<?= htmlspecialchars($game['idgame']) ?>" <?= $teamInfo['idgame'] == $game['idgame'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($game['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="team_name">Team Name</label>
            <input name="team_name" type="text" value="<?= htmlspecialchars($teamInfo['name']) ?>" required>

            <label for="team_picture">Team Logo (JPG only)</label>
            <input type="file" name="team_picture" accept="image/jpeg">

            <?php
            $logoPath = "/assets/img/team_picture/" . $teamInfo['idteam'] . ".jpg";
            $defaultLogo = "/assets/img/team_picture/default.jpg";

            $logoSrc = file_exists(__DIR__ . $logoPath) ? $logoPath : $defaultLogo;
            echo "<img src='$logoSrc' alt='Team Logo' style='width: 100px; height: auto; margin-top: 10px;'><br>";
            ?>

            <button name="submit" type="submit">Update</button>
        </form>
    </div>
</body>

</html>

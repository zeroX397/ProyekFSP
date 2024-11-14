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
    $idteam =  $row['idteam'].uniqid();//id unik

    $uploadDir = __DIR__ . "/assets/img/team_picture/";
    $uploadFile = $uploadDir . $idteam . ".jpg";
    if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] === UPLOAD_ERR_OK) {
        // Validasi ukuran dan tipe file jika diperlukan
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = mime_content_type($_FILES['team_logo']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            // Pindahkan file yang diupload ke folder sementara
            if (move_uploaded_file($_FILES['team_logo']['tmp_name'], $uploadFile)) {
                // Dapatkan ID tim yang baru saja ditambahkan (idteam harus diperoleh setelah insert)
                $idteam = $team->getLastInsertedTeamId(); // Pastikan fungsi ini mengembalikan ID terakhir

                // File yang telah di-upload, ganti namanya menjadi [idteam].jpg
                $finalFileName = $uploadDir . $idteam . ".jpg"; // Nama file baru sesuai idteam

                // Rename file yang telah diupload sesuai idteam.jpg
                if (rename($uploadFile, $finalFileName)) {
                    // Set the image path in the database if needed
                    echo "<script>alert('Team registration successful with logo.'); window.location.href='/admin/teams/index.php';</script>";
                } else {
                    $error = "Error renaming the logo.";
                }
            } else {
                $error = "Error uploading the logo.";
            }
        } else {
            $error = "Invalid file type. Only JPG and PNG are allowed.";
        }
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
        <?php if (isset($error)) : ?>
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
            <input type="file" name="team_logo" accept="image/*">
            <button name="submit" type="submit">Save Team</button><br>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>
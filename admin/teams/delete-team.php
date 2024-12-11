<?php
session_start();
require_once("team.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Delete team
if (isset($_POST['deletebtn']) && isset($_POST['idteam'])) {
    $idTeam = $_POST['idteam'];
    $uploadDir = __DIR__ . "/../../assets/img/team_picture/"; // Directory for team images
    $teamLogo = $uploadDir . $idTeam . ".jpg";
    $defaultLogo = $uploadDir . "default.jpg"; // Path to default image

    // Use class in team to delete team
    $team = new Team();
    if ($team->deleteTeam($idTeam)) {
        // Check if the uploaded file exists and is not the default logo
        if (file_exists($teamLogo) && $teamLogo !== $defaultLogo) {
            unlink($teamLogo); // Delete the logo file
        }

        echo "<script>alert('Team deleted successfully.'); window.location.href='/admin/teams/index.php';</script>";
    } else {
        $error = "Failed to delete team.";
    }
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

<?php
session_start();
require_once("achievement.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Delete achievement
if (isset($_POST['deletebtn']) && isset($_POST['idachievement'])) {
    $idachievement = $_POST['idachievement'];

    // Menggunakan instance class achievement untuk delete achievement
    $achievement = new Achievement();
    if ($achievement->deleteAchievement($idachievement)) {
        echo "<script>alert('Achievement deleted successfully.'); window.location.href='/admin/achievements/index.php';</script>";
    } else {
        $error = "Failed to delete achievement.";
    }
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

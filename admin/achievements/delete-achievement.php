<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Check if the delete button was clicked and if id_urls is set
if (isset($_POST['deletebtn']) && isset($_POST['id_urls'])) {
    $achievement_id = $_POST['id_urls'];

    // Delete achievement from the database
    $sql = "DELETE FROM achievement WHERE idachievement = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $achievement_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Success delete achievement
        echo "<script>alert('Success delete achievement'); window.location.href='/admin/achievements/index.php';</script>";
        exit();
    } else {
        // Failed delete achievement
        $error = "Failed delete achievement";
        exit();
    }
} else {
    // Redirect back if accessed incorrectly
    header("Location: /admin/achievements/");
    exit();
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

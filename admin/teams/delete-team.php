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
    $team_id = $_POST['id_urls'];

    // Delete the team from the database
    $sql = "DELETE FROM team WHERE idteam = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $team_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Success delete team
        echo "<script>alert('Success delete team'); window.location.href='/admin/teams/index.php';</script>";
        exit();
    } else {
        // Failed delete team
        $error = "Failed delete team";
        exit();
    }
} else {
    // Redirect back if accessed incorrectly
    header("Location: /admin/teams/");
    exit();
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

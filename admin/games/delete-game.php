<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Check if the delete button was clicked and if id_game is set
if (isset($_POST['deletebtn']) && isset($_POST['id_game'])) {
    $game_id = $_POST['id_game'];

    // Delete game from the database
    $sql = "DELETE FROM game WHERE idgame = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Success delete game
        echo "<script>alert('Success delete game'); window.location.href='/admin/games/index.php';</script>";
        exit();
    } else {
        // Failed delete game
        $error = "Failed delete game";
        exit();
    }
} else {
    // Redirect back if accessed incorrectly
    header("Location: /admin/games/");
    exit();
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

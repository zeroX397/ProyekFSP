<?php
session_start();
require_once("game.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Delete game
if (isset($_POST['deletebtn']) && isset($_POST['id_game'])) {
    $idgame = $_POST['id_game'];

    $game = new Game();
    if ($game->deleteGame($idgame)) {
        echo "<script>alert('Game deleted successfully.'); window.location.href='/admin/games/index.php';</script>";
    } else {
        $error = "Failed to delete game.";
    }
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

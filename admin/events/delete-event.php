<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Check if the delete button was clicked and if id_event is set
if (isset($_POST['deletebtn']) && isset($_POST['id_event'])) {
    $event_id = $_POST['id_event'];

    // Delete event from the database
    $sql = "DELETE FROM event WHERE idevent = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Success delete event
        echo "<script>alert('Success delete event'); window.location.href='/admin/events/index.php';</script>";
        exit();
    } else {
        // Failed delete event
        $error = "Failed delete event";
        exit();
    }
} else {
    // Redirect back if accessed incorrectly
    header("Location: /admin/events/");
    exit();
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

<?php
session_start();
require_once("event.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Delete event
if ( isset($_POST['idevent'])) {
    $idevent = $_POST['idevent'];

    // Menggunakan instance class event untuk delete event
    $event = new Event();
    if ($event->deleteEvent($idevent)) {
        echo "<script>alert('Event deleted successfully.'); window.location.href='/admin/events/index.php';</script>";
    } else {
        $error = "Failed to delete event.";
    }
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>

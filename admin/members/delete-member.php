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
    $member_id = $_POST['id_urls'];

    // Delete member from the database
    $sql = "DELETE FROM member WHERE idmember = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $member_id);

    if (mysqli_stmt_execute($stmt)) {
        // Success delete member
        echo "<script>alert('Success delete member'); window.location.href='/admin/members/index.php';</script>";
        exit();
    } else {
        // Failed delete member
        $error = "Failed delete member";
        exit();
    }
} else {
    // Redirect back if accessed incorrectly
    header("Location: /admin/members/");
    exit();
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>
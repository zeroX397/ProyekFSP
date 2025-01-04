<?php
session_start();
require_once("member.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Delete member
if ( isset($_POST['id_member'])) {
    $idmember = $_POST['id_member'];

    // Use class in member to delete member
    $member = new Member();
    if ($member->deleteMember($idmember)) {
        echo "<script>alert('Member deleted successfully.'); window.location.href='/admin/members/index.php';</script>";
    } else {
        $error = "Failed to delete member.";
    }
}
?>

<?php if (isset($error)) : ?>
    <div style="color: red;"><?php echo $error; ?></div>
<?php endif; ?>
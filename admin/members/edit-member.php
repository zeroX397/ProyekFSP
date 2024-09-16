<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the member ID from the URL
if (isset($_GET['id_member'])) {
    $idmember = mysqli_real_escape_string($connection, $_GET['id_member']);
    
    // Fetch the member data to pre-fill the form
    $memberQuery = "SELECT * FROM member WHERE idmember = ?";
    $stmt = mysqli_prepare($connection, $memberQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idmember);
    mysqli_stmt_execute($stmt);
    $memberResult = mysqli_stmt_get_result($stmt);
    $member = mysqli_fetch_assoc($memberResult);

    // If member not found, redirect back
    if (!$member) {
        echo "<script>alert('Member not found.'); window.location.href='/admin/members/index.php';</script>";
        exit();
    }
    
} else {
    
    header('Location: /admin/members/index.php');
    exit();
}

// Handle the form submission for updating the member
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $fname = mysqli_real_escape_string($connection, $_POST['fname']);
    $lname = mysqli_real_escape_string($connection, $_POST['lname']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Update the member data in the database
    $updateQuery = "UPDATE member SET username = ?, fname = ?, lname = ?, password = ? WHERE idmember = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ssssi', $username, $fname, $lname, $password, $idmember);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Member updated successfully.'); window.location.href='/admin/members/index.php';</script>";
    } else {
        $error = "Error during member update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Informatics E-Sport Club - Edit Member</title>
</head>

<body>
    <!-- Top Navigation bars -->
    <div class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/become-member.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </div>
    <!-- Admin Navigation Bar -->
    <div class="topnav admin-nav">
        <a class="label">Administration Menus</a>
        <a href="/admin/teams/">Manage Teams</a>
        <a href="/admin/members/">Manage Members</a>
        <a href="/admin/events/">Manage Events</a>
        <a href="/admin/games/">Manage Games</a>
        <a href="/admin/achievements/">Manage Achievements</a>
    </div>
    <!-- Form to Edit Member -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
        <br><br><br>
        <table class="edit-table">
            <tr>
                <td><label for="username">Username</label></td>
                <td><input name="username" type="text" placeholder="Username" value="<?php echo htmlspecialchars($member['username']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="fname">First Name</label></td>
                <td><input name="fname" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($member['fname']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="lname">Last Name</label></td>
                <td><input name="lname" type="text" placeholder="Last Name" value="<?php echo htmlspecialchars($member['lname']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input name="password" type="password" placeholder="Password" value="<?php echo htmlspecialchars($member['password']); ?>" required></td>
            </tr>
            <tr>
            <td></td>
            <td><button name="submit" type="submit" class = 'btnsubmit'>Update</button></td>
            </tr>
        </table>
        </form>
    </div>
    

</body>

</html>

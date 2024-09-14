<?php
session_start();
include("../../config.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /'); // Redirect non-admins to the homepage
    exit();
}

// Insert new member data
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $fname = mysqli_real_escape_string($connection, $_POST['fname']);
    $lname = mysqli_real_escape_string($connection, $_POST['lname']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Insert the new member into the database
    $sql = "INSERT INTO `member`(`username`, `fname`, `lname`, `password`) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $username, $fname, $lname, $password);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('Member registration successful. You may see it on the Members page.'); window.location.href='/admin/members/index.php';</script>";
    } else {
        $error = "Error during member registration.";
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
    <link rel="stylesheet" href="/assets/styles/admin/members/add-member.css">
    <title>Informatics E-Sport Club - Add Member</title>
</head>

<body>
    <!-- Top Navigation Bar -->
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
    
    <!-- Form to Add New Member -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="add-form" method="post">
            <input name="username" type="text" placeholder="Username" required>
            <input name="fname" type="text" placeholder="First Name" required>
            <input name="lname" type="text" placeholder="Last Name" required>
            <input name="password" type="password" placeholder="Password" required>
            <button name="submit" type="submit">Save Member</button><br>
        </form>
    </div>
</body>

</html>

<?php 
session_start();
include("config.php");

if (isset($_POST['submit'])) { // Check if form was submitted
    $fname = mysqli_real_escape_string($connection, $_POST['fname']);
    $lname = mysqli_real_escape_string($connection, $_POST['lname']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']); 

    // Check if username already exists
    $checkUser = "SELECT * FROM `fsp-project`.member WHERE username=?";
    $stmt = mysqli_prepare($connection, $checkUser);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        // Insert new user
        $sql = "INSERT INTO `fsp-project`.member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, 'member')";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $fname, $lname, $username, $password);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='/login.php';</script>";
        } else {
            $error = "Error during registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/signup.css">
    <title>Informatics E-Sport Club</title>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/how-to-join.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            // User is not logged in
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            // User is logged in
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </nav>
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="signup-form" method="post">
            <input name="fname" type="text" placeholder="First Name" required>
            <input name="lname" type="text" placeholder="Last Name">
            <input name="username" type="text" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <button name="submit" type="submit">Sign me up</button><br>
            <p style="margin-top: 30px;">Already have an account? <a href="/login.php">Log in here</a></p>
        </form>
    </div>
</body>

</html>
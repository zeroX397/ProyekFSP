<?php
session_start();

include("config.php");

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Prepared statement to avoid SQL injection
    $sql = "SELECT username, password, profile FROM `fsp-project`.member WHERE username=? AND password=?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['profile'] = $row['profile']; // Store user profile in session

        if ($row['profile'] == 'admin') {
            header("Location: /admin/"); // Redirect to admin directory
            exit();
        } else {
            header("Location: /"); // Redirect to homepage or member area
            exit();
        }
    } else {
        echo "<script>alert('Username or password is incorrect. Please try again.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/login.css">
    <title>Informatics E-Sport Club</title>
</head>

<body>
    <!-- Top Navigation Bar -->
    <div class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/become-member.php">Join a Team</a>
        <?php
        if (!isset($_SESSION['username'])) {
            // User is not logged in
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            // User is logged in
            echo '<a class="active" href="/profile.php">My Profile</a>';
            echo '<a class="logout" href="/logout.php">Logout</a>';
            // To check wether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        ?>
    </div>
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="login-form" method="post">
            <input name="username" type="text" placeholder="username" required>
            <input name="password" type="password" placeholder="password" required>
            <button name="submit" type="submit">Log In</button><br>
            <p style="margin-top: 30px;">Don't have an account? <a href="/signup.php">Sign up here</a></p>
        </form>
    </div>
</body>

</html>
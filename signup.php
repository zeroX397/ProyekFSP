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
        <a href="/become-member.php">Become a Member</a>
        <a class="active" href="/login.php">Login</a>
    </div>
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" class="login-form" method="post">
            <input name="fname" type="text" placeholder="First Name" required>
            <input name="lname" type="text" placeholder="Last Name">
            <input name="username" type="text" placeholder="username" required>
            <input name="password" type="password" placeholder="password" required>
            <button name="submit" type="submit">Sign me up</button><br>
            <p style="margin-top: 30px;">Already have an account? <a href="/login.php">Log in here</a></p>
        </form>
    </div>
</body>

</html>
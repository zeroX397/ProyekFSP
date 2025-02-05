<?php
session_start();
require_once("signup_class.php");

$member = new Member();

if (isset($_POST['submit'])) { // Check if form was submitted
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $hashedPassword = hash('sha256', $password);

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else if ($member->isUsernameExists($username)) {
        $error = "Username already exists.";
    } else {
        if ($member->registerMember($fname, $lname, $username, $password)) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/signup.css">
    <title>Sign Up to Informatics Club</title>
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
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
            // To check whether user is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo 
                '<div class="dropdown">
                    <a class="dropbtn" onclick="adminpageDropdown()">Admin Sites
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-content" id="dd-admin-page">
                        <a href="/admin/teams/">Manage Teams</a>
                        <a href="/admin/members/">Manage Members</a>
                        <a href="/admin/events/">Manage Events</a>
                        <a href="/admin/games/">Manage Games</a>
                        <a href="/admin/achievements/">Manage Achievements</a>
                        <a href="/admin/event_teams/">Manage Event-Teams</a>
                    </div>
                </div>';
                echo 
                '<div class="dropdown">
                    <a class="dropbtn" onclick="proposalDropdown()">Join Proposal
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-content" id="proposalPage">
                        <a href="/admin/proposal/waiting.php">Waiting Approval</a>
                        <a href="/admin/proposal/responded.php">Responded</a>
                    </div>
                </div>';
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
            <input name="password" id="password" type="password" placeholder="Password" required>
            <input name="confirm_password" id="confirm_password" type="password" placeholder="Confirm Password" required>
            <div style="margin-bottom: 20px;" id="message"></div>
            <button name="submit" type="submit">Sign me up</button><br>
            <p style="margin-top: 30px;">Already have an account? <a href="/login.php">Log in here</a></p>
        </form>
    </div>
    <script src="/assets/js/jquery-3.7.1.js"></script>
    <script src="/assets/js/script.js"></script>
    <script>
        $('#password, #confirm_password').on('keyup', function() {
            if ($('#password').val() == $('#confirm_password').val()) {
                $('#message').html('Matching').css('color', 'green');
            } else
                $('#message').html('Not Matching').css('color', 'red');
        });
    </script>

</body>

</html>
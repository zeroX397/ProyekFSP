<?php
session_start();
include("config.php");

if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    echo "<script>alert('Please login before applying to Team.');</script>";
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['idmember'], $_POST['idmember'], $_POST['idteam'], $_POST['application-text'])) {
        $idmember = mysqli_real_escape_string($connection, $_SESSION['idmember']);
        $idteam = mysqli_real_escape_string($connection, $_POST['idteam']);
        $description = mysqli_real_escape_string($connection, $_POST['application-text']);
        $status = "waiting";  // Default status

        $sql = "INSERT INTO join_proposal (idmember, idteam, description, status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iiss", $idmember, $idteam, $description, $status);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Application submitted successfully. Track your status in \"My Profile\"'); window.location.href='/';</script>";
            } else {
                $error = "Error during registration: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Error preparing the statement: ' . mysqli_error($connection);
        }
    } else {
        $error = 'All fields are required and must be valid.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/join-team.css">
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
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php">Logout</a>';
            echo '<a class="active" href="/profile.php">' . htmlspecialchars($displayName) . '</a>';
            // To check whether is admin or not
            if (isset($_SESSION['profile']) && $_SESSION['profile'] == 'admin') {
                echo '<a href="/admin/">Admin Site</a>';
            }
        }
        echo $_SESSION['idmember'];
        ?>
    </nav>

    <!-- Form Apply to Join Team -->
    <form class="application-form" method="post">
        <?php if (isset($_SESSION['idmember'])): ?>
            <input type="hidden" name="idmember" value="<?php echo $_SESSION['idmember']; ?>">
        <?php else:
            echo $error; ?>
            <p>Error</p>
        <?php endif; ?>
        <input type="hidden" name="idteam" value="<?php echo isset($_POST['idteam']) ? $_POST['idteam'] : 'default_value'; ?>">
        <h1>Tell us just a bit about yourself:</h1>
        <textarea class="application-text" name="application-text" maxlength="100" rows="4" cols="30" placeholder="Your role in a game, or your main agents/heroes...&#10;Max. 100 characters."></textarea>
        <br><button type="submit">Apply</button>
    </form>
</body>

</html>
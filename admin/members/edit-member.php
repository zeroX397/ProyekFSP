<?php
session_start();
require_once("member.php");

$member = new Member(); 

// Check if user is logged in and is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: /');
    exit();
}

// Get the member ID from the form (via POST or GET)
if (isset($_POST['id_member']) || isset($_GET['id_member'])) {
    $idmember = isset($_POST['id_member']) ? $_POST['id_member'] : $_GET['id_member'];

    // Fetch the member data to pre-fill the form
    $memberData = $member->getMemberById($idmember);

    // If member not found, redirect back
    if (!$memberData) {
        echo "<script>alert('Member not found.'); window.location.href='/admin/members/index.php';</script>";
        exit();
    }
} else {
    header('Location: /admin/members/index.php');
    exit();
}

// Handle the form submission for updating the member
if (isset($_POST['submit']) && isset($_POST['id_member'])) {
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $password = $_POST['password'];

    // Check if password needs to be updated
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing
    } else {
        $hashed_password = $memberData['password']; // Keep existing password if unchanged
    }

    // Update the member data in the database
    if ($member->updateMember($username, $fname, $lname, $hashed_password, $idmember)) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/main.css">
    <link rel="stylesheet" href="/assets/styles/admin/members/edit-member.css">
    <title>Informatics E-Sport Club - Edit Member</title>
</head>

<body>
    <!-- Top Navigation bars -->
    <nav class="topnav">
        <a class="active" href="/">Homepage</a>
        <a href="/teams.php">Teams</a>
        <a href="/members.php">Members</a>
        <a href="/events.php">Events</a>
        <a href="/about.php">About Us</a>
        <a href="/how-to-join.php">How to Join</a>
        <?php
        if (!isset($_SESSION['username'])) {
            echo '<a class="active" href="/login.php">Login</a>';
        } else {
            $displayName = "Welcome, " . $_SESSION['idmember'] . " - " . $_SESSION['username']; // Append ID and username
            echo '<a class="logout" href="/logout.php" onclick="return confirmationLogout()">Logout</a>';
            echo '<a class="active" href="/profile">' . htmlspecialchars($displayName) . '</a>';
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
    <!-- Form to Edit Member -->
    <div class="form">
        <?php if (isset($error)) : ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="edit-form">
            <!-- Hidden input for member ID -->
            <input type="hidden" name="id_member" value="<?php echo htmlspecialchars($memberData['idmember']); ?>">
            <br><br><br>
            <table class="edit-table">
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input name="username" type="text" placeholder="Username" value="<?php echo htmlspecialchars($memberData['username']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="fname">First Name</label></td>
                    <td><input name="fname" type="text" placeholder="First Name" value="<?php echo htmlspecialchars($memberData['fname']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="lname">Last Name</label></td>
                    <td><input name="lname" type="text" placeholder="Last Name" value="<?php echo htmlspecialchars($memberData['lname']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input name="password" type="password" placeholder="Enter new password (leave blank to keep current password)"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button name="submit" type="submit" class='btnsubmit'>Update</button></td>
                </tr>
            </table>
        </form>
    </div>
    <script src="/assets/js/script.js"></script>
</body>

</html>

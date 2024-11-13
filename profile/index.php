<?php
include('../config.php');
session_start();

$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

if ($idmember) {
    // Query to show all joined team
    $queryjoinedteam = "SELECT t.idteam, t.name as team_name FROM team as t 
              INNER JOIN team_members as tm ON t.idteam = tm.idteam 
              WHERE tm.idmember = ?";
    $stmt = $connection->prepare($queryjoinedteam);
    $stmt->bind_param("i", $idmember);
    $stmt->execute();
    $result = $stmt->get_result();

    // Query to show all join proposal that already sent
    $queryjoinproposal = "SELECT jp.idjoin_proposal, t.name as team_name, jp.status 
                      FROM join_proposal jp 
                      JOIN team t ON jp.idteam = t.idteam 
                      WHERE jp.idmember = ?";
    $stmt = $connection->prepare($queryjoinproposal);
    $stmt->bind_param("i", $idmember);
    $stmt->execute();
    $proposals = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/profile/main.css">
    <title>Profile</title>
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
            // To check whether is admin or not
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

    <main>
        <h1>Hello <?php echo $_SESSION['fname'] . " " . $_SESSION['lname'] ?></h1>
        <span id="edit-profile-change-passwd-btn">
            <button id="edit-profile-btn">Edit Profile</button>
            <button id="change-passwd-btn">Change Password</button>
        </span>
        <h2>Joined Team</h2>
        <table>
            <tr>
                <th>ID Team</th>
                <th>Team Name</th>
                <th>Action</th>
            </tr>
            <?php
            if (isset($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['idteam'] . "</td>";
                    echo "<td>" . $row['team_name'] . "</td>";
                    echo "<td><form action='team-detail.php' method='get'>";
                    echo "<input type='hidden' name='idteam' value='" . $row['idteam'] . "'>";
                    echo "<input type='submit' id='detail-btn' value='Details'>";
                    echo "</form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No teams found</td></tr>";
            }
            ?>
        </table>

        <h2>Your Proposal Status</h2>
        <table>
            <tr>
                <th>Proposal ID</th>
                <th>Team Name</th>
                <th>Status</th>
            </tr>
            <?php if ($proposals->num_rows > 0) : ?>
                <?php while ($row = $proposals->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['idjoin_proposal']; ?></td>
                        <td><?php echo $row['team_name']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan='3'>No proposals found</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>
    <script src="/assets/js/script.js"></script>
</body>

</html>
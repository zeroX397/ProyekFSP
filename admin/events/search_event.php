<?php
include("../../config.php");

if (isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($connection, $_POST['searchTerm']);
    $query = "SELECT idevent, event.name AS 'Event Name', DATE_FORMAT(event.date, '%W, %d %M %Y') AS 'Event Date', event.description AS 'Event Description' FROM event WHERE event.name LIKE '%$searchTerm%' OR event.description LIKE '%$searchTerm%'";

    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['idevent']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Event Name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Event Date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Event Description']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No events found</td></tr>";
    }
}
?>

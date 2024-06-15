<?php
session_start();
include("connection.php");

$username = $_SESSION['username'];
$citizen = "SELECT user_id FROM users WHERE username = '$username'";
$citizenResult = $con->query($citizen);
$row = $citizenResult->fetch_assoc();
$citizenID = $row['user_id'];

$sql = "SELECT * FROM announcements where status='open' ";
$result = $con->query($sql);

// Check if there are any announcements
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><input type='radio' name='selected_id' value='" . $row['announcement_id'] . "'></td>";
        echo "<td>" . $row['announcement_id'] . "</td>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . $row['date_created'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>Δεν υπάρχουν ανακοινώσεις</td></tr>";
}

// Close the database connection
$con->close();
?>

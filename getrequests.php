<?php
session_start();
include("connection.php");

$username = $_SESSION['username'];
$citizen = "SELECT user_id FROM users  WHERE username = '$username'";
$citizenResult = $con->query($citizen);
$row = $citizenResult->fetch_assoc();
$citizenID = $row['user_id'];

$sql = "SELECT * FROM Requests WHERE person_id = $citizenID";
$result = $con->query($sql);


// Έλεγχος αποτελεσμάτων
if ($result->num_rows > 0) {
    // Εκτύπωση δεδομένων ως HTML table
    echo "<table border='1'><tr>";

    while ($row = $result->fetch_assoc()) {
        $productId = $row['product_id'];  // Get product_id from the result
        $productNameQuery = "SELECT p.product_name FROM Products p WHERE p.product_id = $productId"; // Query to get product_name
        $productNameResult = $con->query($productNameQuery);

        if ($productNameResult->num_rows > 0) {
            $productNameRow = $productNameResult->fetch_assoc();
            $productName = $productNameRow['product_name'];

            echo "<tr>";
            echo "<td>" . $productName . "</td>";
            echo "<td>" . $row['product_id'] . "</td>";
            echo "<td>" . $row['num_people_affected'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['date_submitted'] . "</td>";
            echo "<td>" . $row['date_accepted'] . "</td>";
            echo "<td>" . $row['date_completed'] . "</td>";
            echo "</tr>";
        }
    }

    echo "</table>";
} else {
    echo "<tr><td colspan='2'>Δεν υπάρχουν αιτήματα</td></tr>";
}

// Κλείσιμο σύνδεσης
$con->close();
?>

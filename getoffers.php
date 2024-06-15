<?php
session_start();
include("connection.php");

$username = $_SESSION['username'];
$citizen = "SELECT user_id FROM users  WHERE username = '$username'";
$citizenResult = $con->query($citizen);
$row = $citizenResult->fetch_assoc();
$citizenID = $row['user_id'];

$sql = "SELECT * FROM offers WHERE citizen_id = $citizenID";
$result = $con->query($sql);

// Έλεγχος αποτελεσμάτων
if ($result->num_rows > 0) {
    // Εκτύπωση δεδομένων ως HTML table
    echo "<table border='1'><tr>";
   

    while ($row = $result->fetch_assoc()) {
     
      $announcementId = $row['announcement_id'];  // Get announcement_id from the result
      $productNameQuery = "SELECT a.product_name FROM announcements a WHERE a.announcement_id = $announcementId"; // Query to get product_name
      $quantityQuery = "SELECT a.quantity FROM announcements a WHERE a.announcement_id = $announcementId"; // Query to get quantity
      $productNameResult = $con->query($productNameQuery);
      $quantityResult=$con->query($quantityQuery);

      if ($productNameResult->num_rows > 0) {
        $productNameRow = $productNameResult->fetch_assoc();
        $productName = $productNameRow['product_name'];


        if ($quantityResult->num_rows > 0) {
          $quantityRow = $quantityResult->fetch_assoc();
          $quantity = $quantityRow['quantity'];
      

        echo "<tr>";
        
        echo "<td>" . $row['announcement_id'] . "</td>";
        echo "<td>" . $productName . "</td>";
        echo "<td>" . $quantity . "</td>"; 
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['date_submitted'] . "</td>";
        echo "<td>" . $row['date_accepted'] . "</td>";
        echo "<td>" . $row['date_completed'] . "</td>";
        echo "<td>" . $row['date_cancelled'] . "</td>";
        echo "</tr>";
      }
      }
    }

    echo "</table>";
} else {
    echo "<tr><td colspan='2'>Δεν υπάρχουν προσφορές</td></tr>";
}

// Κλείσιμο σύνδεσης
$con->close();
?>

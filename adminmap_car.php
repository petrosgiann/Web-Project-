<?php
session_start();
include("connection.php");

// Λήψη των παραμέτρων από το URL
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];


$queryy = "SELECT users.username,products.product_name,RescuerInventory.quantity
FROM users 
LEFT JOIN RescuerInventory  ON  RescuerInventory.rescuer_id=users.user_id
LEFT JOIN products ON RescuerInventory.product_id = products.product_id 
WHERE users.latitude = $latitude AND users.longitude = $longitude";

     


$resulttt = $con->query($queryy);

// Έλεγχος για επιτυχή εκτέλεση του query
if ($resulttt) {
    // Επιστροφή των αποτελεσμάτων ως JSON
    $data = $resulttt->fetch_assoc();
    echo json_encode($data);
} else {
    // Επιστροφή κενού JSON object αν υπάρξει σφάλμα
    echo json_encode([]);
}

// Κλείσιμο της σύνδεσης
$con->close();
?>

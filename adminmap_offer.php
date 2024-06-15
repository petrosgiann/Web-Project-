<?php
session_start();
include("connection.php");

// Λήψη των παραμέτρων από το URL
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];


$queryy = "SELECT users.fullname, users.phone,offers.date_submitted,announcements.product_name,announcements.quantity
FROM users  
INNER JOIN offers ON users.user_id = offers.citizen_id
INNER JOIN announcements ON  offers.announcement_id = announcements.announcement_id
WHERE users.latitude = $latitude AND users.longitude = $longitude AND offers.date_accepted IS NULL"; 

       


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

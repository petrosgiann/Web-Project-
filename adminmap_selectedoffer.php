<?php
session_start();
include("connection.php");

// Λήψη των παραμέτρων από το URL
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

$queryy = "SELECT 
    users.fullname, 
    users.phone, 
    offers.date_submitted, 
    announcements.product_name, 
    announcements.quantity, 
    tasks.date_submitted AS task_date_submitted,
    tasks_rescuer.username AS rescuer_fullname,
    tasks_rescuer.longitude AS rescuer_longitude,
    tasks_rescuer.latitude AS rescuer_latitude
FROM users 
INNER JOIN tasks ON users.user_id = tasks.citizen_id 
INNER JOIN offers ON users.user_id = offers.citizen_id
INNER JOIN markers ON offers.offer_id = markers.offer_id
INNER JOIN announcements ON offers.announcement_id = announcements.announcement_id
INNER JOIN users AS tasks_rescuer ON tasks.rescuer_id = tasks_rescuer.user_id
WHERE users.latitude = $latitude AND users.longitude = $longitude  AND tasks.status = 'accepted'";




       


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

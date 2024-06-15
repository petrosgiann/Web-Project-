<?php
session_start();
include("connection.php");

// Λήψη των παραμέτρων από το URL
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

// Εκτέλεση του δεύτερου SQL query



$username = $_SESSION['username'];
$person = "SELECT user_id FROM users WHERE username = '$username'";
$personResult = $con->query($person);
$row = $personResult->fetch_assoc();
$person_id = $row['user_id'];

$query = "SELECT users.fullname, users.phone,requests.date_submitted,products.product_name,
requests.num_people_affected,tasks.date_submitted AS task_date_submitted,tasks_rescuer.username AS rescuer_fullname
FROM users 
INNER JOIN tasks ON users.user_id = tasks.citizen_id  
INNER JOIN requests ON users.user_id = requests.person_id
INNER JOIN markers  ON requests.request_id = markers.request_id
INNER JOIN products ON requests.product_id = products.product_id
INNER JOIN users AS tasks_rescuer ON tasks.rescuer_id = tasks_rescuer.user_id  
WHERE users.latitude = $latitude AND users.longitude = $longitude AND markers.rescuer_id = $person_id  AND tasks.rescuer_id = $person_id  ";
       


$resultt = $con->query($query);

// Έλεγχος για επιτυχή εκτέλεση του query
if ($resultt) {
    // Επιστροφή των αποτελεσμάτων ως JSON
    $data = $resultt->fetch_assoc();
    echo json_encode($data);
} else {
    // Επιστροφή κενού JSON object αν υπάρξει σφάλμα
    echo json_encode([]);
}

// Κλείσιμο της σύνδεσης
$con->close();
?>

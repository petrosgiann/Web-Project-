<?php
session_start();
include("connection.php");

// Λήψη των παραμέτρων από το URL
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

// Εκτέλεση του δεύτερου SQL query


$query = "SELECT users.fullname, users.phone,requests.date_submitted,products.product_name,
requests.num_people_affected
FROM users 
INNER JOIN requests ON users.user_id = requests.person_id
INNER JOIN products ON requests.product_id = products.product_id  
WHERE users.latitude = $latitude AND users.longitude = $longitude and requests.date_accepted IS NULL";
       


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




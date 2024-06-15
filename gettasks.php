<?php
session_start();
include("connection.php");


$username = $_SESSION['username'];


$rescuerQuery = "SELECT user_id FROM users WHERE username = '$username'";
$rescuerResult = $con->query($rescuerQuery);

// Έλεγχος αν υπάρχουν αποτελέσματα
if ($rescuerResult->num_rows > 0) {
    // Ανάκτηση του ονόματος χρήστη από τα αποτελέσματα
    $rescuerRow = $rescuerResult->fetch_assoc();
    $rescuer = $rescuerRow['user_id'];

    $sql = "SELECT
        Users.fullname AS citizen_name,
        Users.phone AS citizen_phone,
        Tasks.task_id,
        Tasks.date_submitted,
        Tasks.task_type,
        Tasks.quantity,
        Products.product_name
    FROM
        Tasks
    JOIN
        Users ON Tasks.citizen_id = Users.user_id
    JOIN
        Products ON Tasks.product_id = Products.product_id
    WHERE
        Tasks.rescuer_id = $rescuer AND Tasks.status='accepted'";
    
    $result = $con->query($sql);
    
    // Έλεγχος αποτελεσμάτων
    if ($result->num_rows > 0) {
        // Μετατροπή των αποτελεσμάτων σε πίνακα JSON
        $tasks = array();
        while ($rowTask = $result->fetch_assoc()) {
            $tasks[] = $rowTask;
        }
        
        // Επιστροφή των αποτελεσμάτων σε μορφή JSON
        echo json_encode($tasks);
    } else {
        // Επιστροφή κενού πίνακα αν δεν υπάρχουν αποτελέσματα
        echo json_encode(array());
    }
} else {
    // Επιστροφή κενού πίνακα αν το όνομα χρήστη δεν βρέθηκε
    echo json_encode(array());
}


$con->close();
?>

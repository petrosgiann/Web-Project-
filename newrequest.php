<?php

session_start();

include("connection.php");

if (isset($_GET['product']) && isset($_GET['quantity'])) {
    $productId = $_GET['product'];
    $quantity = $_GET['quantity'];

    $username = $_SESSION['username'];
    $person = "SELECT user_id FROM users WHERE username = '$username'";
    $personResult = $con->query($person);
    $row = $personResult->fetch_assoc();
    $person_id = $row['user_id'];


    // Insert into Requests table
    $query = "INSERT INTO Requests (person_id, product_id, num_people_affected, status, date_submitted)
              VALUES ($person_id, $productId, $quantity, 'open', NOW())";

    if (mysqli_query($con, $query)) {
        // Παίρνουμε το τελευταίο user_id που δημιουργήθηκε (auto increment)
        $last_request_id = mysqli_insert_id($con);

        // Εισαγωγή αντίστοιχης εγγραφής στον πίνακα markers
       $sql = "INSERT INTO markers (user_id, type,request_id) VALUES ($person_id, 'request',$last_request_id)"; 
    
// Send the JSON response
header('Content-Type: application/json');
echo json_encode(['message' =>'Request successfully processed']);

    // Execute the query
    $con->query($sql);
    }
}

?>


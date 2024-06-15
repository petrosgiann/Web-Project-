<?php

session_start();

include("connection.php");


$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

$citizen = "SELECT user_id FROM users WHERE longitude= $longitude AND latitude = $latitude ";
$citizenResult = $con->query($citizen);
$row = $citizenResult->fetch_assoc();
$citizen_id = $row['user_id'];


    $username = $_SESSION['username'];
    $person = "SELECT user_id FROM users WHERE username = '$username'";
    $personResult = $con->query($person);
    $row = $personResult->fetch_assoc();
    $person_id = $row['user_id'];
  

    $queryy = "SELECT announcements.product_id,announcements.quantity,offer_id
    FROM offers  
    INNER JOIN users ON offers.citizen_id = users.user_id
    INNER JOIN announcements ON offers.announcement_id  = announcements.announcement_id  
    WHERE users.user_id= $citizen_id AND offers.date_accepted IS NULL"; 
        
           $queryResult = $con->query($queryy);
           $row = $queryResult->fetch_assoc();
           $product_id = $row['product_id'];
           $quantity = $row['quantity'];
           $offer = $row['offer_id'];
    

   $sql = "INSERT INTO tasks (offer_id,quantity,product_id,citizen_id,rescuer_id,task_type,date_submitted,status) 
   VALUES ( $offer,$quantity,$product_id,$citizen_id,$person_id ,'offer',NOW(),'accepted')";
    // Execute the SQL query
    $con->query($sql);


    $updateofferQuery = "UPDATE offers SET status = 'accepted', date_accepted = NOW() WHERE offer_id = '$offer'";
    $updateofferResult = $con->query($updateofferQuery);

    $updatemarkerQuery = "UPDATE markers SET type='selected_offer',rescuer_id = $person_id   WHERE offer_id = '$offer' ";
    $updatemarkerResult = $con->query($updatemarkerQuery);


// Send the JSON response
header('Content-Type: application/json');
echo json_encode(['message' =>'Selection successfully processed']);


?>

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
  

    $queryyy = "SELECT requests.request_id,requests.product_id,requests.num_people_affected
    FROM users  
    INNER JOIN requests ON users.user_id = requests.person_id
    WHERE user_id= $citizen_id AND requests.date_accepted IS NULL";
           
           $queryResultt = $con->query($queryyy);
           $roww = $queryResultt->fetch_assoc();
           $product_id = $roww['product_id'];
           $quantity = $roww['num_people_affected'];
           $request = $roww['request_id'];
    

   $sql = "INSERT INTO tasks (request_id,quantity,product_id,citizen_id,rescuer_id,task_type,date_submitted,status) 
    VALUES ($request,$quantity, $product_id,$citizen_id,$person_id ,'request',NOW(),'accepted')";
    // Execute the SQL query
    $con->query($sql);



  
    $updateofferQuery = "UPDATE requests SET status = 'accepted', date_accepted = NOW() WHERE request_id = '$request'";
    $updateofferResult = $con->query($updateofferQuery);

    $updatemarkerQuery = "UPDATE markers SET type='selected_request',rescuer_id = $person_id  WHERE request_id = '$request' ";
    $updatemarkerResult = $con->query($updatemarkerQuery);

 


// Send the JSON response
header('Content-Type: application/json');
echo json_encode(['message' =>'Selection successfully processed']);


?>

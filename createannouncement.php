<?php

session_start();

include("connection.php");

if (isset($_GET['product']) && isset($_GET['quantity'])) {
    $productId = $_GET['product'];
    $quantity = $_GET['quantity'];

    
    $productquery = "SELECT product_name FROM products WHERE product_id = '$productId' ";
   $productidResult = $con->query($productquery);
   $row = $productidResult->fetch_assoc();
   $title = $row['product_name'];


    // Insert into Requests table
    $query = "INSERT INTO announcements (product_id,product_name, quantity, date_created, status) 
    VALUES ($productId, '$title', '$quantity', NOW(), 'open')";

    if (mysqli_query($con, $query)) {
    
// Send the JSON response
header('Content-Type: application/json');
echo json_encode(['message' =>'Announcement successfully processed']);

    }
}

?>
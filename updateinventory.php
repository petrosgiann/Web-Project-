<?php
session_start();
include("connection.php");

$username = $_SESSION['username'];
$rescuer = "SELECT user_id FROM users  WHERE username = '$username'";

            $rescuerResult = $con->query($rescuer);
            $row = $rescuerResult->fetch_assoc();
            $rescuerID = $row['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["selected_products"])) {
    
    $selectedProducts = $_POST["selected_products"];
    $prodQuantities = $_POST["get_quantities"];


    foreach ($selectedProducts as $productID) {
        
        if (isset($prodQuantities[$productID]) && $prodQuantities[$productID] > 0) {
            
            $updateProducts = "UPDATE Products SET quantity_available = quantity_available - $prodQuantities[$productID] WHERE product_id = $productID";
            mysqli_query($con, $updateProducts);

            $invRegisters = "SELECT product_id FROM RescuerInventory WHERE product_id = $productID AND rescuer_id = $rescuerID";
            $resultRegisters = mysqli_query($con, $invRegisters);


            
            if(mysqli_num_rows($resultRegisters) == 0) {
                // Δεν υπάρχει ήδη εγγραφή, οπότε εκτελούμε το INSERT
                $insertinventory = "INSERT INTO RescuerInventory (rescuer_id, product_id, quantity) VALUES ($rescuerID, $productID, $prodQuantities[$productID])";
                mysqli_query($con, $insertinventory);
            } else {
               
                // Εκτελούμε το UPDATE αντί για το INSERT
                $updateinventory = "UPDATE RescuerInventory SET quantity = quantity + $prodQuantities[$productID] WHERE rescuer_id = $rescuerID AND product_id = $productID";
                mysqli_query($con, $updateinventory);
            }
           
        }
    }

    
} 


$con->close();

?>

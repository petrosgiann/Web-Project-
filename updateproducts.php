<?php
session_start();
include("connection.php");

$username = $_SESSION['username'];
$rescuer = "SELECT user_id FROM users  WHERE username = '$username'";

            $rescuerResult = $con->query($rescuer);
            $row = $rescuerResult->fetch_assoc();
            $rescuerID = $row['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["return_products"])) {
    
    $selectProducts = $_POST["return_products"];
    $returnQuantities = $_POST["return_quantities"];

    foreach ($selectProducts as $returnProductID) {
        
        if (isset($returnQuantities[$returnProductID]) && $returnQuantities[$returnProductID] > 0) {
            
            $updateproduct = "UPDATE RescuerInventory SET quantity = quantity - $returnQuantities[$returnProductID] WHERE product_id = $returnProductID AND rescuer_id = $rescuerID";
            mysqli_query($con, $updateproduct);

             // Έλεγχος αν η ποσότητα γίνει μηδέν
            $checkQuantityQuery = "SELECT quantity FROM RescuerInventory WHERE product_id = $returnProductID AND rescuer_id = $rescuerID";
            $checkQuantityResult = mysqli_query($con, $checkQuantityQuery);
            $row = mysqli_fetch_assoc($checkQuantityResult);
            $quantityLeft = $row['quantity'];

        if ($quantityLeft == 0) {
            // Διαγραφή από το RescuerInventory
            $deleteProductQuery = "DELETE FROM RescuerInventory WHERE product_id = $returnProductID AND rescuer_id = $rescuerID";
            mysqli_query($con, $deleteProductQuery);
        } 


            $prodRegister = "SELECT product_id FROM Products WHERE product_id = $returnProductID";
            $resultRegister = mysqli_query($con, $prodRegister);


            
            if(mysqli_num_rows($resultRegister) == 0) {
                // Δεν υπάρχει ήδη εγγραφή, οπότε εκτελούμε το INSERT
                $insertProd = "INSERT INTO Products (products_id, product_name, quantity_available) VALUES (NULL, $returnProductID, $returnQuantities[$returnProductID])";
                mysqli_query($con, $insertProd);
            } else {
               
                // Εκτελούμε το UPDATE αντί για το INSERT
                $updateProd = "UPDATE Products SET quantity_available = quantity_available + $returnQuantities[$returnProductID] WHERE  product_id = $returnProductID";
                mysqli_query($con, $updateProd);
            }
           
        }
    }

   
} 


$con->close();

?>


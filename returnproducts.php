<?php
include("connection.php");

$username = $_SESSION['username'];

// Ερώτημα SQL με χρήση INNER JOIN
$returnProducts = "SELECT  p.product_id , p.product_name , ri.quantity FROM RescuerInventory ri
        INNER JOIN Products p ON ri.product_id = p.product_id
        INNER JOIN Users u ON ri.rescuer_id = u.user_id
        WHERE u.username = '$username'";

$returnResult = $con->query($returnProducts);

// Έλεγχος αν υπάρχουν εγγραφές
if ($returnResult->num_rows > 0) {
    echo '<form id="unloadProductsForm" action="" method="post">';
    while ($rowReturn = $returnResult->fetch_assoc()) {
        echo "<label>";
        echo "<input type='checkbox' name='return_products[]' value='" . $rowReturn['product_id'] . "'>";
        echo $rowReturn['product_name'] . " (On truck: " . $rowReturn['quantity'] . ")";
        echo " Quantity: <input type='number' name='return_quantities[" . $rowReturn['product_id'] . "]' min='1'  max='" . $rowReturn['quantity'] . "'>";
        echo "</label><br>";
    }
    echo '<input id="unloadProductsButton" type="submit" value="Unload Selected Products" onclick="unloadProducts()">';
    echo '</form>';
} else {
    // Εμφάνιση μηνύματος όταν δεν υπάρχουν εγγραφές
    echo "<h3>Δεν υπάρχουν προϊόντα προς εκφόρτωση αυτή τη στιγμή.</h3>";
}

$con->close();
?>


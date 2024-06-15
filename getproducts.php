<?php
session_start();
include("connection.php");


$product = "SELECT * FROM Products ";
$productResult = $con->query($product);

// Έλεγχος αν υπάρχουν προϊόντα
if ($productResult->num_rows > 0) {
    echo '<form id="loadProductsForm" action="" method="post">';
    while ($rowProduct = $productResult->fetch_assoc()) {
        echo "<label>";
        echo "<input type='checkbox' name='selected_products[]' value='" . $rowProduct['product_id'] . "'>";
        echo $rowProduct['product_name'] . " (Available: " . $rowProduct['quantity_available'] . ")";
        echo " Quantity: <input type='number' name='get_quantities[" . $rowProduct['product_id'] . "]' min='1' max='" . $rowProduct['quantity_available'] . "' >";
        echo "</label><br>";
    }
    echo '<input id="loadProductsButton" type="submit" value="Load Selected Products" onclick="loadProducts()">';
    echo '</form>';
} else {
    // Εμφάνιση μηνύματος όταν δεν υπάρχουν προϊόντα
    echo "<h3>Δεν υπάρχουν διαθέσιμα προϊόντα αυτή τη στιγμή.</h3>";
}
?>


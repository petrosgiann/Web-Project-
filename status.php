<?php
include("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["selected_categories"])) {
    $selectedCategory = $_POST['selected_categories'];
    $selectedCategoryString = implode(', ', $selectedCategory);
    
    $sqlStorage = "SELECT Categories.category_name, Products.product_name, Products.quantity_available
            FROM Products
            INNER JOIN Categories ON Products.category_id = Categories.category_id
            WHERE Categories.category_id IN ($selectedCategoryString)";

    $resultStorage = $con->query($sqlStorage);

    $sqlRescuerInventory = "SELECT Categories.category_name, Products.product_name, RescuerInventory.quantity,RescuerInventory.rescuer_id
            FROM RescuerInventory
            INNER JOIN Products ON RescuerInventory.product_id = Products.product_id
            INNER JOIN Categories ON Products.category_id = Categories.category_id
            WHERE Categories.category_id IN ($selectedCategoryString)";

    $resultRescuerInventory = $con->query($sqlRescuerInventory);

    if ($resultStorage->num_rows > 0 || $resultRescuerInventory->num_rows > 0) {
        

        while ($rowStorage = $resultStorage->fetch_assoc()) {
            echo "<tr><td>" . $rowStorage["category_name"] . "</td><td>" . $rowStorage["product_name"] . "</td><td>"
            . $rowStorage["quantity_available"] ."</td><td>Stored</td></tr>";
        }

        while ($rowInventory = $resultRescuerInventory->fetch_assoc()) {
            echo "<tr><td>" . $rowInventory["category_name"] . "</td><td>" . $rowInventory["product_name"] . "</td><td>"
            . $rowInventory["quantity"] ."</td><td> In " . $rowInventory["rescuer_id"] ." 's Truck</td></tr>";
        }
echo '</tbody></table>';
    } else {
        echo "<tr><td colspan='2'>Δεν υπάρχουν διαθέσιμα προϊόντα </td></tr>";
    }

} else {
    echo "<tr><td colspan='2'>Δεν έχει επιλεγέι κατηγορία</td></tr>";
}

$con->close();
?>


<?php

session_start();

include("connection.php");


// Fetch products based on the selected category
if (isset($_GET['category'])) {
    $categoryId = $_GET['category'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT product_id, product_name, quantity_available FROM Products WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = array();

    while ($rowProducts = $result->fetch_assoc()) {
        $products[] = $rowProducts;
    }

    $stmt->close();

    // Return the products as JSON
    header('Content-Type: application/json');
    echo json_encode(['items' => $products]);
} else {
    // Invalid request, handle accordingly
    echo "Invalid request";
}

$con->close();
?>

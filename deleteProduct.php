<?php
session_start();

include("connection.php");

// Get product ID from the request parameters
if (isset($_GET['product'])) {
    $productId = $_GET['product'];

    $stmtt = $con->prepare("DELETE FROM productDetails WHERE product_id = ?");
    $stmtt->bind_param("i", $productId);
    $stmtt->execute();
    $stmtt->close();

    $stmt = $con->prepare("DELETE FROM Products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();

    // Return a simple JSON response
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted successfully']);
} else {
    // Invalid request, handle accordingly
    echo "Invalid request";
}

$con->close();
?>

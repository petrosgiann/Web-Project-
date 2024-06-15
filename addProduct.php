<?php
session_start();
include("connection.php");

// Λήψη δεδομένων από το αίτημα POST
$data = json_decode(file_get_contents("php://input"));

// Έλεγχος αν έχουν ληφθεί τα απαραίτητα δεδομένα
if (isset($data->category_id, $data->product_name, $data->quantity_available)) {
    $category_id = $data->category_id;
    $product_name = $con->real_escape_string($data->product_name);
    $quantity_available = $data->quantity_available;

    // Εκτέλεση του SQL ερωτήματος με παραμετροποιημένα ερωτήματα για αποφυγή SQL injection
    $sql = "INSERT INTO Products (category_id, product_name, quantity_available) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iss", $category_id, $product_name, $quantity_available);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array("message" => "Product added successfully");
        } else {
            $response = array("message" => "Error adding product");
        }

        $stmt->close();
    } else {
        $response = array("message" => "Error preparing SQL statement");
    }
} else {
    $response = array("message" => "Missing required data");
}

// Κλείσιμο της σύνδεσης
$con->close();

// Επιστροφή της απάντησης στον πελάτη σε μορφή JSON
echo json_encode($response);
?>

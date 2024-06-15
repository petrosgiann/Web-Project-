<?php
session_start();
include("connection.php");

// Αρχικοποίηση της μεταβλητής για το μήνυμα
$message = "";

// URL του JSON
$json_url = "http://usidas.ceid.upatras.gr/web/2023/export.php";

// Φόρτωση του περιεχομένου του αρχείου JSON από το URL
$json_data = file_get_contents($json_url);

// Μετατροπή του JSON σε πίνακα PHP
$data = json_decode($json_data, true);

// Ελέγξτε εάν υπάρχει το πεδίο 'items' στο JSON
if (isset($data['items']) && is_array($data['items'])) {
    // Εισαγωγή δεδομένων στη βάση δεδομένων
    foreach ($data['items'] as $product) {
        // Έλεγχος για ύπαρξη καταχωρήσεων με το ίδιο id και όνομα προϊόντος
        $check_stmt = $con->prepare("SELECT * FROM Products WHERE product_id = ? AND product_name = ?");
        $check_stmt->bind_param('is', $product['id'], $product['name']);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        $insert_stmt = null; // Initialize $insert_stmt

        // Αν δεν υπάρχει καταχώρηση με τα ίδια id και όνομα προϊόντος, τότε προχωράμε στην εισαγωγή
        if ($check_result->num_rows == 0) {
            $productId = $product['id']; // Assign the id to a variable
            $productName = $product['name']; // Assign the name to a variable
            $productCategory = $product['category']; // Assign the category to a variable

            $insert_stmt = $con->prepare("INSERT INTO Products (product_id, product_name, category_id) VALUES (?, ?, ?)");
            $insert_stmt->bind_param('iss', $productId, $productName, $productCategory);

            if ($insert_stmt->execute()) {
                $productId = $insert_stmt->insert_id;

                foreach ($product['details'] as $detail) {
                    $detail_stmt = $con->prepare("INSERT INTO productDetails (product_id, detail_name, detail_value) VALUES (?, ?, ?)");
                
                    // Check if details are present, otherwise, insert with NULL values
                    $detailName = !empty($detail['detail_name']) ? $detail['detail_name'] : NULL;
                    $detailValue = !empty($detail['detail_value']) ? $detail['detail_value'] : NULL;

                    $detail_stmt->bind_param('iss', $productId, $detailName, $detailValue);
                
                    $detail_stmt->execute();
                    $detail_stmt->close();
                }

                $message = "Η μεταφορά δεδομένων ολοκληρώθηκε με επιτυχία.";
            } else {
                $message = "Σφάλμα κατά τη μεταφορά δεδομένων.";
            }
        } else {
            $message = "Υπάρχει ήδη εγγραφή με το ίδιο id και όνομα προϊόντος. Η εγγραφή δεν εισάγεται.";
        }

        $check_stmt->close();
        // Close the $insert_stmt only if it's not null
        if ($insert_stmt !== null) {
            $insert_stmt->close();
        }
    }
} else {
    $message = "Το πεδίο 'items' δεν βρέθηκε ή δεν είναι πίνακας στο JSON.";
}

// Κλείσιμο της σύνδεσης στη βάση δεδομένων
$con->close();

// Εμφάνιση του μηνύματος
echo $message;
?>


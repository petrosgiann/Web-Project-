<?php
session_start();
include("connection.php");

// Αρχικοποίηση της μεταβλητής για το μήνυμα
$message = "";

// Φόρτωση του περιεχομένου του αρχείου JSON
$json_data = file_get_contents("categoriesUpload.json");

// Μετατροπή του JSON σε πίνακα PHP
$data = json_decode($json_data, true);

// Ελέγξτε εάν υπάρχει το πεδίο 'categories' στο JSON
if (isset($data['categories']) && is_array($data['categories'])) {
    // Εισαγωγή δεδομένων στη βάση δεδομένων
    foreach ($data['categories'] as $category) {
        // Έλεγχος για ύπαρξη καταχωρήσεων με το ίδιο id και όνομα κατηγορίας
        $check_stmt = $con->prepare("SELECT * FROM Categories WHERE category_id = ? AND category_name = ?");
        $check_stmt->bind_param('is', $category['id'], $category['category_name']);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        // Αν δεν υπάρχει καταχώρηση με τα ίδια id και όνομα κατηγορίας, τότε προχωράμε στην εισαγωγή
        if ($check_result->num_rows == 0) {
            $insert_stmt = $con->prepare("INSERT INTO Categories (category_id, category_name, date_added) VALUES (?, ?, ?)");
            $insert_stmt->bind_param('iss', $category['id'], $category['category_name'], $category['date_added']);
            
            if ($insert_stmt->execute()) {
                $message = "Η μεταφορά δεδομένων ολοκληρώθηκε με επιτυχία.";
            } else {
                $message = "Σφάλμα κατά τη μεταφορά δεδομένων.";
            }
        } else {
            $message = "Υπάρχει ήδη εγγραφή με το ίδιο id και όνομα κατηγορίας. Η εγγραφή δεν εισάγεται.";
        }

        $check_stmt->close();
    }
} else {
    $message = "Το πεδίο 'categories' δεν βρέθηκε ή δεν είναι πίνακας στο JSON.";
}

// Κλείσιμο της σύνδεσης στη βάση δεδομένων
$con->close();

// Εμφάνιση του μηνύματος
echo $message;
?>

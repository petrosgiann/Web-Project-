<?php
session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];

    // Εισαγωγή νέου χρήστη στον πίνακα users
    $query = "INSERT INTO users (user_type, username, password, longitude, latitude) 
              VALUES ('rescuer', '$username', '$password', '$longitude', '$latitude')";
    if (mysqli_query($con, $query)) {
        // Παίρνουμε το τελευταίο user_id που δημιουργήθηκε (auto increment)
        $last_user_id = mysqli_insert_id($con);

        // Εισαγωγή αντίστοιχης εγγραφής στον πίνακα markers
        $marker_sql = "INSERT INTO markers (user_id, type) VALUES ($last_user_id, 'car')";

        if (mysqli_query($con, $marker_sql)) {
            header("Location: adminmain.html");
            die;
        } else {
            echo "Σφάλμα κατά την εισαγωγή δεδομένων στον πίνακα Markers: " . mysqli_error($con);
        }
    } else {
        echo "Σφάλμα κατά την εισαγωγή δεδομένων στον πίνακα Users: " . mysqli_error($con);
    }

    $con->close();
}
?>

<?php
// Re-establish the database connection
include("connection.php");

// Handle form submission
if (isset($_POST['selected_id'])) {


    $selected = $_POST["selected_id"];
    $queryInsert = "INSERT INTO offers (citizen_id, announcement_id, status, date_submitted)
                    VALUES ($citizenID, $selected, 'pending', NOW())";

    if (mysqli_query($con, $queryInsert)) {
        echo "Επιτυχής εισαγωγή δεδομένων στον πίνακα offers";
     
    
    $last_offer_id = mysqli_insert_id($con);
    $sql = "INSERT INTO markers (user_id, type,offer_id)
    VALUES ($citizenID, 'offer',$last_offer_id)";

if (mysqli_query($con, $sql)) {
    echo "Επιτυχής εισαγωγή δεδομένων στον πίνακα markers";
} else {
    echo "Σφάλμα κατά την εισαγωγή δεδομένων στον πίνακα markers: " . mysqli_error($con);
}
    $res = "UPDATE announcements SET status = 'closed' WHERE announcement_id = $selected";

    if (mysqli_query($con, $res)) {
        echo "Η τιμή του πεδίου status ενημερώθηκε με επιτυχία.";
    } else {
        echo "Σφάλμα κατά την ενημέρωση της τιμής του πεδίου status: " . mysqli_error($con);
    }

    header("Location: myoffers.html");
    die;
}
} 

// Close the database connection
$con->close();
?>

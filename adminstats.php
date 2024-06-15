<?php
session_start();
include("connection.php");


// Συνάρτηση για εκτέλεση ερωτημάτων SQL
function executeQuery($sql) {
    global $con;
    $result = $con->query($sql);
    return $result->fetch_assoc();
   
}

$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];


// Ερώτημα για ανάκτηση των στατιστικών δεδομένων
$sqlRequests = "SELECT COUNT(*) AS count_new_requests FROM Requests WHERE status = 'open' AND date_submitted >= '$startDate' AND date_submitted <= '$endDate'";
$sqlOffers = "SELECT COUNT(*) AS count_new_offers FROM Offers WHERE status = 'pending' AND date_submitted >= '$startDate' AND date_submitted <= '$endDate'";
$sqlCompletedRequests = "SELECT COUNT(*) AS count_completed_requests FROM Requests WHERE status = 'completed' AND date_completed >= '$startDate' AND date_completed <= '$endDate'";
$sqlCompletedOffers = "SELECT COUNT(*) AS count_completed_offers FROM Offers WHERE status = 'completed' AND date_completed >= '$startDate' AND date_completed <= '$endDate'";

// Εκτέλεση των ερωτημάτων
$dataRequests = executeQuery($sqlRequests);
$dataOffers = executeQuery($sqlOffers);
$dataCompletedRequests = executeQuery($sqlCompletedRequests);
$dataCompletedOffers = executeQuery($sqlCompletedOffers);

// Κλείσιμο της σύνδεσης
$con->close();



// Επιστροφή των δεδομένων ως JSON
header('Content-Type: application/json');
echo json_encode([
    'new_requests' => $dataRequests['count_new_requests'],
    'new_offers' => $dataOffers['count_new_offers'],
    'completed_requests' => $dataCompletedRequests['count_completed_requests'],
    'completed_offers' => $dataCompletedOffers['count_completed_offers']
]);
?>

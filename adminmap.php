<?php
session_start();
include("connection.php");

// Fetch marker data from the SQL table and join with users table
$sql = "SELECT users.latitude, users.longitude, markers.type 
        FROM markers 
        INNER JOIN users ON markers.user_id = users.user_id";
$result = $con->query($sql);

// Create an array to store marker positions with types
$markerPositions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure that latitude and longitude are set and not empty
        if (!empty($row['latitude']) && !empty($row['longitude'])) {
            $markerPositions[] = array(
                'lat' => $row['latitude'],
                'lng' => $row['longitude'],
                'type' => $row['type']
            );
        }
    }
}

if ($result === false) {
    die("Database query failed: " . mysqli_error($con));
}

// Check for JSON encoding errors
if (json_last_error() != JSON_ERROR_NONE) {
    die("JSON encoding error: " . json_last_error_msg());
}

// Return the JSON-encoded array
echo json_encode($markerPositions);

// Close the database connection
$con->close();
?>

<?php
session_start();
include("connection.php");

if (isset($_GET['taskId'])) {
    $taskId = $_GET['taskId'];

    $sql = "SELECT tasks.task_type
            FROM tasks 
            WHERE tasks.task_id = $taskId"; 
        
    $Result = $con->query($sql);
    $roww = $Result->fetch_assoc();
    $task_type = $roww['task_type'];

    if ($task_type === 'offer') {
        $queryy = "SELECT markers.marker_id, markers.type, offers.offer_id
                   FROM tasks
                   INNER JOIN offers ON tasks.offer_id = offers.offer_id
                   INNER JOIN markers ON offers.offer_id = markers.offer_id  
                   WHERE tasks.task_id = $taskId"; 
        
        $queryResult = $con->query($queryy);
        $row = $queryResult->fetch_assoc();
        $marker_id = $row['marker_id'];
        $type = $row['type'];
        $offer= $row['offer_id'];

        $updateQuery = "UPDATE Tasks SET status = 'pending', rescuer_id = NULL WHERE task_id = '$taskId'";
        $updateResult = $con->query($updateQuery);

        $updateMarker = "UPDATE markers SET type = 'offer', rescuer_id = NULL WHERE marker_id = '$marker_id'";
        $con->query($updateMarker);

        $updateDate="UPDATE offers SET  date_accepted = NULL WHERE offer_id = '$offer'";
        $con->query($updateDate);

        if ($updateResult) {
            // Return response to the client
            echo json_encode(['message' => 'Task abandoned successfully']);
        } else {
            // Return error response to the client
            echo json_encode(['error' => 'Error abandoning task']);
        }
    } 
    elseif ($task_type === 'request') {
        $queryy = "SELECT markers.marker_id, markers.type, requests.request_id
                   FROM tasks
                   INNER JOIN requests ON tasks.request_id = requests.request_id
                   INNER JOIN markers ON requests.request_id = markers.request_id  
                   WHERE tasks.task_id = $taskId"; 
        
        $queryResult = $con->query($queryy);
        $row = $queryResult->fetch_assoc();
        $marker_id = $row['marker_id'];
        $type = $row['type'];
        $request= $row['request_id'];

        $updateQuery = "UPDATE Tasks SET status = 'pending', rescuer_id = NULL WHERE task_id = '$taskId'";
        $updateResult = $con->query($updateQuery);

        $updateMarker = "UPDATE markers SET type = 'request', rescuer_id = NULL WHERE marker_id = '$marker_id'";
        $con->query($updateMarker);

        $updateDate="UPDATE requests SET  date_accepted = NULL WHERE request_id = '$request'";
        $con->query($updateDate);

        if ($updateResult) {
            // Return response to the client
            echo json_encode(['message' => 'Task abandoned successfully']);
        } else {
            // Return error response to the client
            echo json_encode(['error' => 'Error abandoning task']);
        }
    }
    else {
        // Return error response to the client
        echo json_encode(['error' => 'Task ID not provided']);
    }
}

$con->close();
?>

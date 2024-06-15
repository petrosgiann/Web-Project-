<?php

session_start();
include("connection.php");

// Perform the SQL query to get categories
$sql = "SELECT category_id, category_name FROM Categories";
$resultSql = $con->query($sql);

if ($resultSql->num_rows > 0) {
  // Output data as JSON
  $categories = array();
  while($rowCategories = $resultSql->fetch_assoc()) {
    $categories[] = $rowCategories;
  }
  echo json_encode(array("categories" => $categories));
} else {
  echo json_encode(array("categories" => array()));
}

$con->close();


?>
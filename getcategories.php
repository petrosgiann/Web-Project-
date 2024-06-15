<?php

session_start();
include("connection.php");

$Categories = "SELECT category_id, category_name FROM Categories";
$resultCategories = $con->query($Categories);

echo '<form id="SelectedCategories" action=" " method="post">';
while ($rowCategories = $resultCategories->fetch_assoc()) {
    echo "<label>";
    echo "<input type='checkbox' name='selected_categories[]' value='" . $rowCategories['category_id'] . "'>";
    echo $rowCategories['category_name'];
    echo "</label><br>";
}
echo '<input id="button" type="submit" value="Select" onclick="sendSelectedCategories()">';
echo '</form>';

?>



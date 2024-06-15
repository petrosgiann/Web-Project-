<?php
session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["fullname"];
    $phone = $_POST["phone"];
    $latitude = $_POST["latitude"]; 
    $longitude = $_POST["longitude"]; 

    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) == 0 && !empty($username) && !empty($password) && !is_numeric($username)) {
        $query = "INSERT INTO users (user_type, username, password, fullname, phone, latitude, longitude) 
        VALUES ('citizen', '$username', '$password', '$name', '$phone', '$latitude', '$longitude')";
        mysqli_query($con, $query);

        header("Location: login.html");
        die;
    } else {
        echo "Username already exists or invalid information. Please enter a different username.";
    }
}
?>



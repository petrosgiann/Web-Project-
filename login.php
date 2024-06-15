<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result= mysqli_query($con,$query);

    if ($result && mysqli_num_rows($result)== 1) {
        
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_type'] = $row['user_type'];

        
        switch ($_SESSION['user_type']) {
            case 'admin':
                header("Location: adminmain.html");
                break;
            case 'citizen':
                header("Location: citizenmain.html");
                break;
            case 'rescuer':
                header("Location: rescuermain.html");
                break;
        }
    } 
    else {
       
        echo "Wrong Username or Password !";
    }
}


?>


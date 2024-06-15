   <?php
       
            include("connection.php");
            
            $username = $_SESSION['username'];
            
            // Ερώτημα SQL με χρήση INNER JOIN
            $sql = "SELECT  p.product_name , ri.quantity FROM RescuerInventory ri
                    INNER JOIN Products p ON ri.product_id = p.product_id
                    INNER JOIN Users u ON ri.rescuer_id = u.user_id
                    WHERE u.username = '$username'";


            $result = mysqli_query($con, $sql);
            
            // Έλεγχος αποτελεσμάτων και εκτύπωση στον πίνακα
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["product_name"] . "</td><td>" . $row["quantity"] . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Δεν υπάρχουν προϊόντα στο όχημα</td></tr>";
            }
        



    ?>


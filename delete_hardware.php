<?php
    if (isset($_POST['deviceID'])) {
        $deviceId = $_POST['deviceID'];

        // Database connection and deletion query
        include('db_connection.php'); // Include your DB connection here

        $query = "DELETE FROM hardwares WHERE deviceID = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $deviceId);

        if ($stmt->execute()) {
            echo 'success'; // Send success message back to JavaScript
        } else {
            echo 'error'; // Send error message back to JavaScript
        }
    }
?>

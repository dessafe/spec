<?php
// update_status.php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deviceID = $_POST['deviceID'];
    $status = $_POST['status'];

    if (!empty($deviceID) && !empty($status)) {
        // SQL query to update the status in the database
        $query = "UPDATE hardwares SET status = ? WHERE deviceID = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param('si', $status, $deviceID);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
?>

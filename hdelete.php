<?php
session_start();
include "db_connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $selectedLabID = isset($_GET['labID']) ? $_GET['labID'] : '';

    // Debugging statement
    echo "Device ID: $id, Lab ID: $selectedLabID";

    $sql = "DELETE FROM hardwares WHERE deviceID = '$id'";
    
    if ($link->query($sql) === TRUE) {
        // Debugging statement
        echo "Record deleted successfully";
        exit(); // Ensure no further output is sent after the header redirection
    } else {
        // Debugging statement
        echo "Error deleting record: " . $link->error;
    }
    // Redirect back to lview.php with the selected lab ID preserved
    header("Location: lview.php?labID=$selectedLabID");
    exit();
} else {
    // Debugging statement
    echo "ID not provided.";
}
?>
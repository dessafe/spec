<?php
// Include necessary files and start session
session_start();
include "db_connection.php";

// Check if labID is provided
if (isset($_GET['labID'])) {
    $labID = $_GET['labID'];
    // Sanitize the input to prevent SQL injection
    $labID = mysqli_real_escape_string($link, $labID);

    // Prepare the SQL statement
    $query = "SELECT hardwares.deviceID, hardwares.name, hardwares.doAcquisition, hardwares.status, faculty.fname, faculty.lname, faculty.faculty_stat
    FROM hardwares 
    INNER JOIN faculty ON hardwares.idno = faculty.idno 
    ORDER BY hardwares.labID
            ";

    // Prepare the statement
    $stmt = mysqli_prepare($link, $query);

   
  

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Initialize CSV data
    $csvData = "Device ID,Name,Date of Acquisition,Status,Faculty Administrator, Faculty Status\n";

    // Generate CSV content
    while ($row = mysqli_fetch_assoc($result)) {
        $csvData .= "{$row['deviceID']},{$row['name']},{$row['doAcquisition']},{$row['status']},{$row['fname']} {$row['lname']},{$row['faculty_stat']}\n";
    }

    // Output CSV data
    if (!empty($csvData)) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="lab_data.csv"');
        // Output CSV data
        echo $csvData;
        exit;
    } else {
        echo "No data found for the selected lab.";
    }
} else {
    echo "No lab ID provided.";
}
?>

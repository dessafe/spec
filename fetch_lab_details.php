<?php
include "db_connection.php";

if (isset($_POST['labID'])) {
    $labID = $_POST['labID'];

    // Query to get full name of faculty managing the lab
    $query = "
        SELECT CONCAT(f.fname, ' ', f.mname, ' ', f.lname) AS facultyName
        FROM laboratory l
        JOIN faculty f ON l.idno = f.idno
        WHERE l.labID = '$labID'
    ";

    $facultyResult = mysqli_query($link, $query);
    $facultyName = '';
    if ($facultyResult && mysqli_num_rows($facultyResult) > 0) {
        $facultyRow = mysqli_fetch_assoc($facultyResult);
        $facultyName = $facultyRow['facultyName'];
    }

    // Query to get devices for the selected lab
    $deviceQuery = "
        SELECT deviceID, name
        FROM hardwares
        WHERE labId = '$labID'
    ";

    $deviceResult = mysqli_query($link, $deviceQuery);
    $devices = [];
    while ($deviceRow = mysqli_fetch_assoc($deviceResult)) {
        $devices[] = $deviceRow;
    }

    echo json_encode([
        'facultyName' => $facultyName,
        'devices' => $devices
    ]);
}
?>

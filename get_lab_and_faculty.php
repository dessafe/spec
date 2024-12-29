<?php
// Include the database connection
include "db_connection.php";

// Get the deviceID from the query parameter
$deviceID = $_GET['deviceID'];

// Ensure that deviceID is set
if (!$deviceID) {
    echo json_encode(['error' => 'Missing deviceID']);
    exit;
}

// Query to fetch the lab name and faculty idno from the laboratory table
$labQuery = "SELECT l.labName, l.idno FROM laboratory l 
             JOIN hardwares h ON h.labId = l.labID
             WHERE h.deviceID = '$deviceID'";

$labResult = mysqli_query($link, $labQuery);

if (!$labResult) {
    echo json_encode(['error' => 'Lab query failed: ' . mysqli_error($link)]);
    exit;
}

$labName = "";
$idno = "";
if (mysqli_num_rows($labResult) > 0) {
    $labData = mysqli_fetch_assoc($labResult);
    $labName = $labData['labName'];
    $idno = $labData['idno'];
} else {
    echo json_encode(['error' => 'Lab not found']);
    exit;
}

// Query to fetch the faculty name (first and last name)
$facultyQuery = "SELECT CONCAT(f.fname, ' ', f.lname) AS facultyName 
                 FROM faculty f WHERE f.idno = '$idno'";

$facultyResult = mysqli_query($link, $facultyQuery);

if (!$facultyResult) {
    echo json_encode(['error' => 'Faculty query failed: ' . mysqli_error($link)]);
    exit;
}

$facultyName = "";
if (mysqli_num_rows($facultyResult) > 0) {
    $facultyData = mysqli_fetch_assoc($facultyResult);
    $facultyName = $facultyData['facultyName'];
} else {
    echo json_encode(['error' => 'Faculty not found']);
    exit;
}

// Query to fetch hardware details
$hardwareQuery = "SELECT * FROM hardwares WHERE deviceID = '$deviceID'";
$hardwareResult = mysqli_query($link, $hardwareQuery);

if (!$hardwareResult) {
    echo json_encode(['error' => 'Hardware query failed: ' . mysqli_error($link)]);
    exit;
}

$hardwareDetails = mysqli_fetch_assoc($hardwareResult);

// Return the results as a JSON response
echo json_encode([
    'deviceName' => $hardwareDetails['name'],
    'brand' => $hardwareDetails['brand'],
    'sponsorName' => $hardwareDetails['sponsorName'],
    'category' => $hardwareDetails['category'],
    'serialNo' => $hardwareDetails['serialNo'],
    'doAcquisition' => $hardwareDetails['doAcquisition'],
    'status' => $hardwareDetails['status'],
    'labName' => $labName,
    'facultyName' => $facultyName
]);
?>

<?php
session_start();
include "db_connection.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM `hardwares`";
if (isset($_SESSION["QR"]) && $_SESSION["QR"] !== '') {
    $sql .= " WHERE idno = '{$_SESSION["QR"]}'";
}
if ($search) {
    $sql .= " AND (deviceID LIKE '%$search%' OR name LIKE '%$search%' OR doAcquisition LIKE '%$search%' OR status LIKE '%$search%' OR labID LIKE '%$search%')";
}

$result = $link->query($sql);

// Prepare an array to hold search results
$searchResults = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each row to the search results array
        $searchResults[] = $row;
    }
}

// Send search results as JSON response
header('Content-Type: application/json');
echo json_encode($searchResults);
exit;
?>

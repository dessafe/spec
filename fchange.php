<?php
include "db_connection.php";

$response = array();

if(isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT `faculty_stat` FROM `faculty` WHERE `idno` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $newStatus = ($status === "Active") ? "Inactive" : "Active";

    $toggleState = ($newStatus === "Inactive") ? "off" : "";

    $sql = "UPDATE `faculty` SET `faculty_stat` = ? WHERE `idno` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newStatus, $id);
    if(mysqli_stmt_execute($stmt)) {
        header("Location: fview.php");
        exit; 
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating record: " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
}

echo json_encode($response);
?>

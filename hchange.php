<?php
include "db_connection.php";
session_start(); 

if(isset($_GET["id"])) {
    $id = $_GET["id"];

 
    $sql = "SELECT `status` FROM `hardwares` WHERE `deviceID` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    
    $newStatus = ($status === "Working") ? "Not Working" : "Working";

    $sql = "UPDATE `hardwares` SET `status` = ? WHERE `deviceID` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newStatus, $id);
    if(mysqli_stmt_execute($stmt)) {
        header("Location: hview.php");
        exit(); 
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

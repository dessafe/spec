<?php
    include "db_connection.php";
    
    if (isset($_POST['deviceID'])) {
        $deviceID = $_POST['deviceID'];

        // Query to fetch the lab and faculty information based on the selected device
        $query = "
            SELECT h.labId, l.labname, l.labID, h.idno, f.fname, f.lname 
            FROM hardwares h 
            JOIN laboratory l ON h.labId = l.labID
            JOIN faculty f ON h.idno = f.idno
            WHERE h.deviceID = '$deviceID'
        ";

        $result = mysqli_query($link, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            echo json_encode([
                'fromLabName' => $row['labname'],
                'toLabName' => $row['labname'],
                'fromFaculty' => $row['fname'] . ' ' . $row['lname'],
                'toFaculty' => $row['fname'] . ' ' . $row['lname']
            ]);            
        } else {
            echo json_encode(null);
        }
    }
?>

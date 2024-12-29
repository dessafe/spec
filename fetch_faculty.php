<?php
    include "db_connection.php";
    
    if (isset($_POST['labID'])) {
        $labID = $_POST['labID'];

        // Query para kunin ang faculty batay sa napiling lab
        $query = "
            SELECT l.labname, f.fname, f.lname 
            FROM laboratory l 
            JOIN faculty f ON l.idno = f.idno
            WHERE l.labID = '$labID'
        ";

        $result = mysqli_query($link, $query);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            echo json_encode([
                'toFaculty' => $row['fname'] . ' ' . $row['lname']
            ]);
        } else {
            echo json_encode(null);
        }
    }
?>

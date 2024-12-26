<?php
session_start();
include "db_connection.php";

if (isset($_GET['id'])) {
    $facultyId = $_GET['id'];
    // Fetch faculty details from the database based on the faculty ID
    $query = "SELECT * FROM faculty WHERE idno = '$facultyId'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $faculty = mysqli_fetch_assoc($result);
        // Output faculty details in HTML format
        echo '<ul>';
        echo '<li><strong>ID No:</strong> ' . $faculty['idno'] . '</li>';
        echo '<li><strong>Name:</strong> ' . $faculty['fname'] . ' '. $faculty['mname']  .  ' ' . $faculty['lname'] . '</li>';
        echo '<li><strong>ID No:</strong> ' . $faculty['sex'] . '</li>';
        echo '<li><strong>ID No:</strong> ' . $faculty['addrs'] . '</li>';
        echo '<li><strong>ID No:</strong> ' . $faculty['cpno'] . '</li>';
        echo '<li><strong>Department:</strong> ' . $faculty['department'] . '</li>';
        echo '<li><strong>Position:</strong> ' . $faculty['position'] . '</li>';
        echo '<li><strong>Status:</strong> ' . $faculty['faculty_stat'] . '</li>';
        // Add additional details as needed
        echo '</ul>';
    } else {
        echo 'Faculty details not found.';
    }
} else {
    echo 'Invalid request.';
}
?>

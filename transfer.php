<?php
session_start();
include "db_connection.php";

// Initialize message variables
$successMessage = "";
$errorMessage = "";

// Fetch hardware devices
$hardwareQuery = "SELECT * FROM hardwares";
$hardwareResult = mysqli_query($link, $hardwareQuery);

// Fetch available laboratories
$labQuery = "SELECT * FROM laboratory";
$labResult = mysqli_query($link, $labQuery);

// Fetch faculties
$facultyQuery = "SELECT * FROM faculty";
$facultyResult = mysqli_query($link, $facultyQuery);

// Fetch admins for the dropdown from the administ table
$adminQuery = "SELECT email, CONCAT(fname, ' ', mname, ' ', lname) AS full_name FROM administ";
$adminResult = mysqli_query($link, $adminQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $deviceID = $_POST['deviceID'];
    $toLabID = $_POST['toLabID'];
    $remarks = $_POST['remarks'];
    $adminEmail = $_POST['adminEmail'];

    // Query to get fromLabID and fromFacultyID
    $hardwareDetailsQuery = "
        SELECT h.labId AS fromLabID, h.idno AS fromFacultyID
        FROM hardwares h
        WHERE h.deviceID = '$deviceID'
    ";
    
    $hardwareDetailsResult = mysqli_query($link, $hardwareDetailsQuery);
    
    if ($hardwareDetailsResult && mysqli_num_rows($hardwareDetailsResult) > 0) {
        // Fetch fromLabID and fromFacultyID
        $hardwareDetails = mysqli_fetch_assoc($hardwareDetailsResult);
        $fromLabID = $hardwareDetails['fromLabID'];
        $fromFacultyID = $hardwareDetails['fromFacultyID'];
    } else {
        $errorMessage = 'Device not found.';
    }

    // Get toFacultyID based on toLabID
    $toFacultyQuery = "
        SELECT l.idno 
        FROM laboratory l 
        WHERE l.labId = '$toLabID'
    ";
    $toFacultyResult = mysqli_query($link, $toFacultyQuery);
    
    if ($toFacultyResult && mysqli_num_rows($toFacultyResult) > 0) {
        $toFacultyID = mysqli_fetch_assoc($toFacultyResult)['idno'];
    } else {
        $errorMessage = 'To Faculty not found.';
    }

    // Set transfer date
    $transferDate = date('Y-m-d');

    // Update hardware table and insert into hardware_transfer_history table
    if ($errorMessage == "") {
        $updateQuery = "UPDATE hardwares SET labId = '$toLabID' WHERE deviceID = '$deviceID'";
        $transferQuery = "
            INSERT INTO hardware_transfer_history 
            (deviceID, fromLabID, toLabID, transferDate, adminEmail, remarks, fromFaculty, toFaculty) 
            VALUES ('$deviceID', '$fromLabID', '$toLabID', '$transferDate', '$adminEmail', '$remarks', '$fromFacultyID', '$toFacultyID')
        ";

        if (mysqli_query($link, $updateQuery) && mysqli_query($link, $transferQuery)) {
            $successMessage = 'Hardware transferred successfully';
        } else {
            $errorMessage = 'Error transferring hardware';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        function getFacultyByLab(labID) {
            $.ajax({
                type: "POST",
                url: "fetch_faculty.php", 
                data: { labID: labID },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data) {
                        $('#toFaculty').val(data.toFaculty); 
                    }
                }
            });
        }

        function getLabDetails(labID) {
            if (labID) {
                $.ajax({
                    type: "POST",
                    url: "fetch_lab_details.php", 
                    data: { labID: labID },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data) {
                            $('#fromFaculty').val(data.facultyName);

                            var deviceSelect = $('#deviceID');
                            deviceSelect.empty();
                            deviceSelect.append('<option value="">Choose a device</option>');
                            data.devices.forEach(function(device) {
                                deviceSelect.append('<option value="' + device.deviceID + '">' + device.name + '</option>');
                            });
                        }
                    }
                });
            }
        }
    </script>
</head>
<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <h2>Transfer Hardware</h2>
        <form method="POST" action="transfer.php">
            
            <!-- Select Lab -->
            <div class="mb-3">
                <label for="fromLabID" class="form-label">Select Lab</label>
                <select id="fromLabID" name="fromLabID" class="form-select" required onchange="getLabDetails(this.value)">
                    <option value="">Choose a lab</option>
                    <?php while ($row = mysqli_fetch_assoc($labResult)) : ?>
                        <option value="<?php echo $row['labID']; ?>"><?php echo $row['labname']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Select Faculty (Populated based on selected lab) -->
            <div class="mb-3">
                <label for="fromFaculty" class="form-label">From Faculty</label>
                <input type="text" id="fromFaculty" name="fromFaculty" class="form-control" readonly>
            </div>

            <!-- Select Hardware (Populated based on selected lab) -->
            <div class="mb-3">
                <label for="deviceID" class="form-label">Select Hardware</label>
                <select id="deviceID" name="deviceID" class="form-select" required>
                    <option value="">Choose a device</option>
                </select>
            </div>

            <!-- Select to lab -->
            <div class="mb-3">
                <label for="toLabID" class="form-label">To Lab</label>
                <select id="toLabID" name="toLabID" class="form-select" required onchange="getFacultyByLab(this.value)">
                    <option value="">Choose a lab</option>
                    <?php mysqli_data_seek($labResult, 0); ?>
                    <?php while ($row = mysqli_fetch_assoc($labResult)) : ?>
                        <option value="<?php echo $row['labID']; ?>"><?php echo $row['labname']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Select to faculty -->
            <div class="mb-3">
                <label for="toFaculty" class="form-label">To Faculty</label>
                <input type="text" id="toFaculty" name="toFaculty" class="form-control" readonly>
            </div>

            <!-- Select admin -->
            <div class="mb-3">
                <label for="adminEmail" class="form-label">Select Admin</label>
                <select id="adminEmail" name="adminEmail" class="form-select" required>
                    <option value="">Choose an admin</option>
                    <?php while ($row = mysqli_fetch_assoc($adminResult)) : ?>
                        <option value="<?php echo $row['email']; ?>"><?php echo $row['full_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Remarks -->
            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Transfer Hardware</button>
        </form>
    </div>

    <!-- Display SweetAlert based on the message -->
    <?php
    if ($successMessage != "") {
        echo "<script>Swal.fire('Success', '$successMessage', 'success');</script>";
    }
    if ($errorMessage != "") {
        echo "<script>Swal.fire('Error', '$errorMessage', 'error');</script>";
    }
    ?>
</body>
</html>

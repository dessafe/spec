<?php
session_start();
include "db_connection.php";

// Fetch all laboratories for the dropdown menu
$query = "SELECT labID, labname, labLoc FROM laboratory";
$labs_result = mysqli_query($link, $query);

$selectedLabName = 'All';
$selectedLabLoc = '';

// Check if a lab is selected
if (isset($_POST['labID'])) {
    $labID = $_POST['labID'];
    // Fetch hardware details for the selected lab
    $query = "SELECT hardwares.deviceID, hardwares.name, hardwares.labID, hardwares.doAcquisition, hardwares.status, faculty.fname, faculty.lname 
                FROM hardwares 
                INNER JOIN faculty ON hardwares.idno = faculty.idno 
                WHERE `labID` = '$labID'";
    $result = mysqli_query($link, $query);

    // Fetch the selected lab name and location
    $labNameQuery = "SELECT labname, labLoc FROM laboratory WHERE labID = '$labID'";
    $labNameResult = mysqli_query($link, $labNameQuery);
    $lab = mysqli_fetch_assoc($labNameResult);
    $selectedLabName = isset($lab['labname']) ? $lab['labname'] : '';
    $selectedLabLoc = isset($lab['labLoc']) ? $lab['labLoc'] : '';
} else {
    // Default query to fetch all hardware details if no lab is selected
    $query = "SELECT hardwares.deviceID, hardwares.name, hardwares.labID, hardwares.doAcquisition, hardwares.status, faculty.fname, faculty.lname 
                FROM hardwares 
                INNER JOIN faculty ON hardwares.idno = faculty.idno";
    $result = mysqli_query($link, $query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="assets/css/fac-adm-style.css">
	<!-- <link rel="stylesheet" href="assets/css/ad-modal.css"> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/78ae652187.js" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<title>SpecSnap</title>
	<link rel="icon" href="assets/img/logo1.png">
	<style>
        #sidebar ul {
            padding-left: 0;
        }
		a {
			text-decoration: none;
		}

        form select {
            background: none;
            padding: 5px 20px;
            border: 2px solid #3C91E6;
            border-radius: 20px;
            color: #3C91E6;
        }
	</style>
   <script>
$(document).ready(function() {
    // Delete hardware
    $('.delete-link').click(function() {
        var deviceID = $(this).data('deviceid');
        var labID = $(this).data('labid');

        // Debugging statement
        console.log('Delete link clicked. Device ID:', deviceID, 'Lab ID:', labID);

        // Set the device and lab IDs as data attributes of the modal
        $('#deleteModal').data('deviceid', deviceID);
        $('#deleteModal').data('labid', labID);

        // Show the delete confirmation modal
        $('#deleteModal').modal('show');
    });

    // Confirm delete when modal confirm button is clicked
    $('#confirmDeleteBtn').click(function() {
        var deviceID = $('#deleteModal').data('deviceid');
        var labID = $('#deleteModal').data('labid');

        // Send AJAX request to delete hardware
        $.ajax({
            url: 'hdelete.php',
            type: 'GET',
            data: {
                id: deviceID,
                labID: labID
            },
            success: function(response) {
                // Debugging statement
                console.log('Delete request successful. Response:', response);
                
                // Reload page with selected lab ID preserved
                window.location.href = 'lview.php?labID=' + labID;
            },
            error: function(xhr, status, error) {
                // Debugging statement
                console.error('Error deleting hardware:', error);
            }
        });

        // Hide the modal after delete confirmation
        $('#deleteModal').modal('hide');
    });
});
</script>
</head>
<body style="background-color: #eee">


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bx-qr'></i>
			<span class="text">SpecSnap</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="ahomepage.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li >
				<a href="fview.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Faculties</span>
				</a>
			</li>
			<li  class="active">
				<a href="lview.php">
					<i class='bx bxs-building-house'></i>
					<span class="text">Laboratories</span>
				</a>
			</li>
			<li>
				<a href="logz.php">
					<i class='bx bxs-time'></i>
					<span class="text">Login History</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Admin | <?php echo $_SESSION["user"] ?></a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
        <div class="head-title">
                <div class="left">
                    <h1>Laboratories</h1>
                </div>
                <div class="right">
                   <!-- Form to select lab -->
                   <form method="post">
                        <select name="labID" id="labID" onchange="this.form.submit()">
                            <option value="">Select Lab</option>
                            <?php
                            // Display lab options
                            while($lab = mysqli_fetch_assoc($labs_result)) {
                                echo "<option value=\"{$lab['labID']}\">{$lab['labname']}</option>";
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
            <!-- Table content -->
            <div class="table-data">
                <div class="order">
                <div class="head">
                <h3><?php echo $selectedLabName; ?></h3>
                <p><?php echo $selectedLabLoc; ?></p>
                </div>
                    <table>
                        <!-- Table header -->
                        <thead>
                            <tr>
                                <th>Device ID</th>
                                <th>Name</th>
                                <th>Date of Acquisition</th>
                                <th>Faculty Administrator</th>
                                <th>Status</th>
                                <th>Lab ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody>
                            <?php
                             // Display hardware details
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Output table rows
        echo '<tr>';
        echo '<td>' . (isset($row['deviceID']) ? $row['deviceID'] : '') . '</td>';
        echo '<td>' . (isset($row['name']) ? $row['name'] : '') . '</td>';
        echo '<td>' . (isset($row['doAcquisition']) ? $row['doAcquisition'] : '') . '</td>';
        echo '<td>' . (isset($row['fname']) ? $row['fname'] : '') . ' ' . (isset($row['lname']) ? $row['lname'] : '') . '</td>';
        echo '<td>' . (isset($row['status']) ? $row['status'] : '') . '</td>';
        echo '<td>' . (isset($row['labID']) ? $row['labID'] : '') . '</td>';
        // Action links
        echo '<td>';
        if ($row['status'] === 'Working') {
            echo '<a href="lchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a>';
        } else {
            echo '<a href="lchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-off fs-5"></i></a>';
        }
        echo '&nbsp;&nbsp;';
        echo '<a href="#" class="link-dark qr-link" data-bs-toggle="modal" data-bs-target="#qrModal" data-deviceid="'. $row['deviceID'] . '" data-name="' . urlencode($row['name']) . '" data-doacquisition="' . urlencode($row['doAcquisition']) . '" data-status="' . urlencode($row['status']) . '" data-labid="' . urlencode($row['labID']) . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a>';
        echo '&nbsp;&nbsp;'; echo '&nbsp;&nbsp;';
        echo '<a href="javascript:void(0);" class="link-danger delete-link" data-deviceid="' . $row['deviceID'] . '" data-labid="' . $row['labID'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
        echo '</td>';    
        echo '</tr>';
    }
} else {
    echo "<tr><td colspan='6'>No results found.</td></tr>";
}

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

    <!-- Modal markup -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                    <p class="mt-2 text-center">
                        <a href="#" id="printLink">Print QR Code</a> <!-- Print link -->
                    </p>
                </div>
            </div>
        </div>
    </div>

<!-- Modal markup -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this hardware?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
 <!-- Include Bootstrap JS -->
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Print QR code when print link is clicked
        $('#printLink').click(function(e) {
            e.preventDefault();
            // Trigger print dialog
            window.print();
        });

        // Other JavaScript functionality
        $('.qr-link').click(function(e) {
            e.preventDefault();
            var deviceID = $(this).data('deviceid');
            var name = decodeURIComponent($(this).data('name'));
            var doAcquisition = decodeURIComponent($(this).data('doacquisition'));
            var status = decodeURIComponent($(this).data('status'));
            var labID = decodeURIComponent($(this).data('labid'));

            var hardwareDetails = "Device ID: " + deviceID + "\nName: " + name + "\nDate of Acquisition: " + doAcquisition + "\nStatus: " + status + "\nLab ID: " + labID;

            var qrCodeUrl = 'qrcode.php?details=' + encodeURIComponent(hardwareDetails);

            // Set the QR code image source
            $('#qrCodeImage').attr('src', qrCodeUrl);

            // Show the modal
            $('#qrModal').modal('show');
        });

        $('.status-link').click(function(e) {
            e.preventDefault();
            var deviceID = $(this).data('deviceid');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'Working') ? 'Not Working' : 'Working';

            // Perform AJAX request to update status in database
            $.ajax({
                type: "POST",
                url: "update_status.php",
                data: { deviceID: deviceID, status: newStatus },
                success: function(response) {
                    // Reload the page after successful update
                    location.reload();
                }
            });
        });
    });
</script>
	<script src="assets/js/script.js"></script>
</body>
</html>
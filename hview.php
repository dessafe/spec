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

?>

<!-- HARDWARE DASHBOARD -->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <link rel="stylesheet" href="assets/css/hview.css">
	<title>SpecSnap</title>
	<link rel="icon" href="assets/img/logo1.png">
	<style>
		#sidebar ul {
			padding-left: 0;
		}
        /* input[type="search"] {
            background-color: transparent;
            outline: none;
        } */
	</style>    
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "lengthMenu": [[10, 25, 50, -1], ["10", "25", "50", "All"]],
            "language": {
                "search": '<span style="font-size: 16px; color: #342E37;">Search:</span>',
                "lengthMenu": '<span style="font-size: 16px; color: #342E37;">Show entries:</span> _MENU_'
            }
            
        });
    });
</script>
    <script>
        $(document).ready(function() {
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

        // Set the download link to the QR code image URL
        $('#downloadLink').attr('href', qrCodeUrl);

        // Show the modal
        $('#qrModal').modal('show');
    });

    // Handle QR code download
    $('#downloadLink').click(function(e) {
        var qrCodeUrl = $('#qrCodeImage').attr('src');
        var a = document.createElement('a');
        a.href = qrCodeUrl;
        a.download = 'QRCode.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});

    </script>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bx-qr'></i>
			<span class="text">SpecSnap</span>
		</a>
		<ul class="side-menu top" style="">
			<li>
				<a href="faculty.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="hview.php">
					<i class='bx bxs-book-content'></i>
					<span class="text">Hardware</span>
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
			<a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Hardware List</h1>
				</div>
			</div>

            <?php
    display($result);

    function display($result)
    {
        if ($result && $result->num_rows > 0) {
            echo '
            <div class="table-responsive">
                <table id="example" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Device Id</th>
                            <th>Name</th>
                            <th>Date of Acquisition</th>
                            <th>Status</th>
                            <th>LabId</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . (isset($row['deviceID']) ? $row['deviceID'] : '') . '</td>';
                echo '<td>' . (isset($row['name']) ? $row['name'] : '') . '</td>';
                echo '<td>' . (isset($row['doAcquisition']) ? $row['doAcquisition'] : '') . '</td>';
                if ($row['status'] == "Working") {
                    echo '<td><span style="background: #5cb85c; font-size: 11px; padding: 6px 16px; border-radius: 20px; color: #F9F9F9">' . (isset($row['status']) ? $row['status'] : '') . '</span></td>';
                } else {
                    echo '<td><span style="background: #dd423d; font-size: 11px; padding: 6px 16px; border-radius: 20px; color: #F9F9F9;">' . (isset($row['status']) ? $row['status'] : '') . '</span></td>';
    
                }
                echo '<td>' . (isset($row['labID']) ? $row['labID'] : '') . '</td>';
                echo '<td>';
                if ($row['status'] === 'Working') {
                    echo '<a href="hchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a>';
                } else {
                    echo '<a href="hchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-off fs-5"></i></a>';
                }
                echo '&nbsp;&nbsp;';
                echo '<a href="#" class="link-dark qr-link" data-deviceid="' . $row['deviceID'] . '" data-name="' . urlencode($row['name']) . '" data-doacquisition="' . urlencode($row['doAcquisition']) . '" data-status="' . urlencode($row['status']) . '" data-labid="' . urlencode($row['labID']) . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a></td>';
                echo '</tr>';
            }
            echo '
                    </tbody>
                </table>
            </div>';
        } else {
            echo "No results found.";
        }
    }
    ?>
        </main>
    </section>

	 <!-- Modal Container -->
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
                    <a href="#" id="downloadLink" download="QRCode.png">Download QR Code</a> <!-- Download link -->
                </p>
            </div>
        </div>
    </div>
</div>



<!-- JavaScript for the modal -->
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
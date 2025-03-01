<?php
session_start();
include "db_connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/78ae652187.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <link rel="stylesheet" href="assets/css/hview.css">
	<title>SpecSnap</title>
	<link rel="icon" href="assets/img/logo1.png">
	<style>
		#sidebar ul {
			padding-left: 0;
		}
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
			<li  class="active">
				<a href="fview.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Faculties</span>
				</a>
			</li>
			<li>
				<a href="lview.php">
					<i class='bx bxs-building-house'></i>
					<span class="text">Laboratories</span>
				</a>
			</li>
            <li>
                <a href="ahardware.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Hardware</span>
                </a>
            </li>
            <li>
                <a href="transferHistory.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Transfer History</span>
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
					<h1>Faculties</h1>
				</div>
			</div>

            <?php
require "db_connection.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM `faculty` WHERE 1=1";
if ($search) {
    $sql .= " AND (fname LIKE '%$search' OR lname LIKE '%$search%' OR department LIKE '%$search%' OR position LIKE '%$search%' OR sex LIKE '%$search')";
}

$result = $link->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo '
        <div class="table-responsive">
        <table id="example" class="table table-hover" >
            <thead>
                <tr>
                    <th>ID No</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['idno'] . '</td>';
                echo '<td>' . $row['fname'] . " " . $row['lname'] . '</td>';
                echo '<td>' . $row['department'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . $row['faculty_stat'] . '</td>';
                echo '<td>';
                // Toggle Active/Inactive status
                if ($row['faculty_stat'] === 'Active') {
                    echo '<a href="fchange.php?id=' . $row['idno'] . '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a>';
                } else {
                    echo '<a href="fchange.php?id=' . $row['idno'] . '" class="link-primary"><i class="fa fa-toggle-off fs-5"></i></a>';
                }
                // View details button
                echo '<i style="margin-left: 10px; color: #0a58ca;" class="fa-solid fa-eye view-faculty-details" data-id="' . $row['idno'] . '"></i>';
                // Edit button - Redirects to edit_faculty.php
                echo '<a href="edit_faculty.php?id=' . $row['idno'] . '" class="link-primary" style="margin-left: 10px;">
                        <i class="fa fa-edit fs-5"></i>
                      </a>';
                echo '</td>';
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

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

			<!-- Modal for displaying faculty details -->
            <div class="modal fade" id="facultyModal" tabindex="-1" role="dialog" aria-labelledby="facultyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facultyModalLabel">Faculty Details</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body" id="facultyDetails">
                    <!-- Faculty details will be displayed here -->
                </div>
            </div>
        </div>
    </div>
	
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listener to the eye icons
        document.querySelectorAll('.view-faculty-details').forEach(function(icon) {
            icon.addEventListener('click', function() {
                var facultyId = this.dataset.id;
                // AJAX call to fetch faculty details
                fetch('fetch_faculty_details.php?id=' + facultyId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('facultyDetails').innerHTML = data;
                        $('#facultyModal').modal('show');
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>

	<script src="assets/js/script.js"></script>
</body>
</html>
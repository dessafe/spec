<?php
    session_start();
    include "db_connection.php";

    $QR = $_SESSION['QR'];
    $sql = "SELECT *, 
            (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR') AS totalHardware,
            (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Working') AS totalWorking,
            (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Not Working') AS totalNotWorking
        FROM `hardwares` WHERE idno = '$QR' ORDER BY doAcquisition DESC LIMIT 5";


    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $totalHardware = $row['totalHardware'];
    $totalWorking = $row['totalWorking'];
    $totalNotWorking = $row['totalNotWorking'];
?>

<!-- FACULTY DASHBOARD -->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="assets/css/fac-adm-style.css">
	<link rel="stylesheet" href="assets/css/fac-modal.css">
	<title>SpecSnap</title>
	<link rel="icon" href="assets/img/logo1.png">
	<style>
		#sidebar ul {
			padding-left: 0;
		}
	</style>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bx-qr'></i>
			<span class="text">SpecSnap</span>
		</a>
		<ul class="side-menu top" style="">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
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
					<h1>Dashboard</h1>
				</div>
				<button id="myBtn" class="btn-download" style="border: none;">
					<i class='bx bx-plus'></i>
					<span class="text">Add Hardware</span> 

				</button>
			</div>

			<ul class="box-info">
                <li>
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    <span class="text">
                        <h3><?php echo $totalHardware; ?></h3>
                        <p>Total Hardware</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-check-circle' ></i>
                    <span class="text">
                        <h3><?php echo $totalWorking; ?></h3>
                        <p>Working</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-x-circle' ></i>
                    <span class="text">
                        <h3><?php echo $totalNotWorking; ?></h3>
                        <p>Not working</p>
                    </span>
                </li>
            </ul>
         

<!-- Your HTML code -->

<?php
display($result);

function display($result)
{
    if ($result && $result->num_rows > 0) {
        echo '
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Recently Added</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Device Id</th>
                            <th>Name</th>
                            <th>Date of Acquisition</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Fetch and display each row
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . (isset($row['deviceID']) ? $row['deviceID'] : '') . '</td>';
            echo '<td>' . (isset($row['name']) ? $row['name'] : '') . '</td>';
            echo '<td>' . (isset($row['doAcquisition']) ? $row['doAcquisition'] : '') . '</td>';
            if ($row['status'] == "Working") {
                echo '<td><span class="status process">' . (isset($row['status']) ? $row['status'] : '') . '</span></td>';
            } else {
                echo '<td><span class="status pending">' . (isset($row['status']) ? $row['status'] : '') . '</span></td>';

            }
            echo '</tr>';
        }

        echo '
                    </tbody>
                </table>
            </div>
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

       <!-- add hardware modal form -->
       <div id="myModal" class="modal">
                <div class="modal-content">
                    <div class="hardware-form">
                        <h3>Add Hardware</h3>
						<form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Device Id:</p>
                                    </label>
                                    <input type="text" name="deviceID" class="form-control" required>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label">
                                        <p class="h5">Device Name:</p>
                                    </label>
                                    <input type="text" name="name" class="form-control" required>

                                </div>
                            </div>
                    
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">
                                    <p class="h5">Status:</p>
                                </label>
                                <select class="form-select" name="status" aria-label="Default select example">
                                    <option selected>Select--</option>
                                    <option value="Working">Working</option>
                                    <option value="Not Working">Not Working</option>
                                </select>

                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Date of Acquisition:</p>
                                    </label>
                                    <input type="date" name="doAcquisition" class="form-control" required>

                                </div>
                            </div>
                        </div>
                        <div class="row">

                        <div class="mb-3">
                        <label class="form-label">
                        <p class="h5">Faculty Id:</p>
                        </label>
                       <input type="text" name="idno" class="form-control readonly-input" value="<?php echo isset($_SESSION["QR"]) ? $_SESSION["QR"] : ''; ?>" readonly required>
                        </div>



                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Lab Id:</p>
                                    </label>
                                    <input type="text" name="labID" class="form-control" required>

                                </div>
                            </div>
                            <div class="sp4"></div>
                            <div class="d-flex flex-column align-items-center">
								<button type="submit" name="add" id="code" class="btn rounded-pill btn-primary w-50">Add</button>
								<p class="close" style="cursor: pointer; margin-top: 10px;">Cancel</p>
							</div>
                    </form>        
                    </div>
                </div>
            </div>

	<?php
if (isset($_POST['add']) && $_POST['deviceID'] != "") {
    $deviceID = mysqli_real_escape_string($link, $_POST['deviceID']);
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $doAcquisition = mysqli_real_escape_string($link, $_POST['doAcquisition']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $idno = mysqli_real_escape_string($link, $_POST['idno']);
    $labID = mysqli_real_escape_string($link, $_POST['labID']);

    // Check if the provided labID exists in the laboratory table
    $lab_query = "SELECT * FROM `laboratory` WHERE `labID` = '$labID'";
    $lab_result = mysqli_query($link, $lab_query);
    if (mysqli_num_rows($lab_result) == 0) {
        echo "<script>Swal.fire({
                  icon: 'error',
                  title: 'Invalid lab ID. Please provide a valid lab ID.',
                  confirmButtonColor: '#bf5b5b',
                });
                var modal = document.getElementById('myModal');
                modal.style.display = 'block';
                </script>";
    } else {
        $device_query = "SELECT * FROM `hardwares` WHERE `deviceID` = '$deviceID'";
        $device_result = mysqli_query($link, $device_query);
        if (mysqli_num_rows($device_result) > 0) {
            echo "<script>Swal.fire({
                icon: 'error',
                title: 'The device already exists in the database.',
                confirmButtonColor: '#bf5b5b',
              });
              var modal = document.getElementById('myModal');
              modal.style.display = 'block';
              </script>";
        } else {
            $sql = "INSERT INTO `hardwares`(`deviceID`, `name`, `doAcquisition`, `status`, `idno`, `labID`) 
                    VALUES ('$deviceID', '$name', '$doAcquisition', '$status', '$idno', '$labID')";
            if ($link->query($sql) === TRUE) {
                echo "<script>Swal.fire({
                    icon: 'success',
                    title: 'Data inserted successfully!',
                    confirmButtonColor: '#a5dc86',
                  }).then(function() {
                    window.location = 'faculty.php';
                });
                  </script>"; 
            } else {
                echo "
                <script>Swal.fire({
                    icon: 'error',
                    title: 'Unable to insert data.',
                    confirmButtonColor: '#bf5b5b',
                  });
                  var modal = document.getElementById('myModal');
                  modal.style.display = 'block';
                  </script>
                ";
                error_log("Error inserting data into database: " . $link->error);
            }
        }
    }
    $link->close();
}
?>

	
	
		<script>
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("myBtn");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            // close button
            span.onclick = function() {
                modal.style.display = "none";
            }
		</script>

	<script src="assets/js/script.js"></script>
</body>
</html>
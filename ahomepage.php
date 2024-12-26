<?php
	session_start();
	include "db_connection.php";

	// Fetch total faculties count
	$facultyCountQuery = "SELECT COUNT(*) as totalFaculties FROM faculty";
	$facultyCountResult = mysqli_query($link, $facultyCountQuery);
	$facultyCount = mysqli_fetch_assoc($facultyCountResult)['totalFaculties'];

	// Fetch total laboratories count
	$laboratoryCountQuery = "SELECT COUNT(*) as totalLaboratories FROM laboratory";
	$laboratoryCountResult = mysqli_query($link, $laboratoryCountQuery);
	$laboratoryCount = mysqli_fetch_assoc($laboratoryCountResult)['totalLaboratories'];

	// Fetch total hardwares count
	$hardwareCountQuery = "SELECT COUNT(*) as totalHardwares FROM hardwares";
	$hardwareCountResult = mysqli_query($link, $hardwareCountQuery);
$hardwareCount = mysqli_fetch_assoc($hardwareCountResult)['totalHardwares'];
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
	<link rel="stylesheet" href="assets/css/ad-modal.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
	</style>
</head>
<body style="background-color: #eee">


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bx-qr'></i>
			<span class="text">SpecSnap</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="ahomepage.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
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
					<h1>Dashboard</h1>
				</div>
				<div class="right">
					<button id="myBtn" class="btn-download" style="border: none; margin-right: 10px">
						<i class='bx bx-plus'></i>
						<span class="text">Create Faculty</span> 
					</button>
                    <button id="myBtn2" class="btn-download" style="border: none;">
						<i class='bx bx-plus'></i>
						<span class="text">Create Laboratory</span> 
					</button>
                </div>
			</div>

			<ul class="box-info">
			<li>
				<i class='bx bxs-group'></i>
				<span class="text">
					<h3><?php echo $facultyCount; ?></h3>
					<p>Total faculties</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-building-house'></i>
				<span class="text">
					<h3><?php echo $laboratoryCount; ?></h3>
					<p>Total laboratories</p>
				</span>
			</li>
			<li>
				<i class='bx bx-wrench'></i>
				<span class="text">
					<h3><?php echo $hardwareCount; ?></h3>
					<p>Total hardware</p>
				</span>
			</li>
			</ul>


			<!-- <div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Login</h3>
					</div>
					<table>
						<thead>
							<tr>
								<th>User</th>
								<th>Date Order</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="img/people.png">
									<p>John Doe</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status completed">Completed</span></td>
							</tr>
							<tr>
								<td>
									<img src="img/people.png">
									<p>John Doe</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status pending">Pending</span></td>
							</tr>
							<tr>
								<td>
									<img src="img/people.png">
									<p>John Doe</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status process">Process</span></td>
							</tr>
							<tr>
								<td>
									<img src="img/people.png">
									<p>John Doe</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status pending">Pending</span></td>
							</tr>
							<tr>
								<td>
									<img src="img/people.png">
									<p>John Doe</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status completed">Completed</span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div> -->
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	       <!-- create faculty modal form -->
		   <div id="myModal" class="modal">
                <div class="modal-content">
                    <div class="hardware-form">
                        <h5>Create faculty account</h5>
						<form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Last Name:</p>
                                    </label>
                                    <input type="text" name="lname" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">First Name:</p>
                                    </label>
                                    <input type="text" name="fname" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Middle Name:</p>
                                    </label>
                                    <input type="text" name="mname" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">
                                    <p class="h5">Sex:</p>
                                </label>
                                <select class="form-select" name="sex" aria-label="Default select example" required>
                                    <option selected disabled>Select--</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-6 ">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Phone Number</p>
                                    </label>
                                    <input type="text" name="cpno" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Address:</p>
                                    </label>
                                    <input type="text" name="addrs" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">ID No:</p>
                                    </label>
                                    <input type="text" name="idno" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Department:</p>
                                    </label>
                                    <input type="text" name="dept" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <P class="h5">Position:</P>
                                    </label>
                                    <input type="text" name="position" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Email Address: </p>
                                    </label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Password:</p>
                                    </label>
                                    <input type="password" name="pas" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Re-Enter Password:</p>
                                    </label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="sp4"></div>
                            <div class="d-flex flex-column align-items-center">
								<button type="submit" name="signup" id="code" class="btn rounded-pill btn-primary w-50">ADD FACULTY</button>
								<p class="close" style="cursor: pointer; margin: 10px 0 0 0 ">Cancel</p>
							</div>
                    </form>
					</div>
                </div>
            </div>

			<!-- create laboratory modal form -->
			<div id="myModal2" class="modal">
                <div class="modal-content">
                    <div class="hardware-form">
                        <h5>Create Laboratory</h5>
						<form action="" method="post" enctype="multipart/form-data">
                      
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Lab Id:</p>
                                    </label>
                                    <input type="text" name="labID" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <p class="h5">Lab Name:</p>
                                    </label>
                                    <input type="text" name="labname" class="form-control" required>
                                </div>
                    
        
                                    <label class="form-label">
                                        <p class="h5">Location:</p>
                                    </label>
                                    <input type="text" name="labLoc" class="form-control" required>

               
                        </div>
                        <div class="sp4"></div>
                            <div class="d-flex flex-column align-items-center">
								<button type="submit" name="create" id="code" class="btn rounded-pill btn-primary w-50">ADD LABORATORY</button>
								<p class="close" style="cursor: pointer; margin: 15px 0 ">Cancel</p>
							</div>
                    </form>
                
					</div>
                </div>
            </div>

			<?php

			if (isset($_POST['signup']) && $_POST['idno'] != "") {

				$message =  '';
				$error_email = '';
				$error_pas = '';

				$fname = $_POST['fname'];
				$lname = $_POST['lname'];
				$mname = $_POST['mname'];
				$sex = $_POST['sex'];
				$addrs = $_POST['addrs'];
				$email = $_POST['email'];
				$cpno = $_POST['cpno'];
				$idno = $_POST['idno'];
				$dept = $_POST['dept'];
				$position = $_POST['position'];
				$pass = $_POST['password'];

				$name_query = "SELECT * FROM `faculty` WHERE `fname` = '$fname' AND `mname` = '$mname' AND `lname` = '$lname'";
				$name_result = mysqli_query($link, $name_query);
				if (mysqli_num_rows($name_result) > 0) {
					echo "
						The faculty is already in the database.<script>Swal.fire({
							icon: 'error',
							title: 'The faculty is already in the database.',
							confirmButtonColor: '#bf5b5b',
						  });
						  var modal = document.getElementById('myModal');
						  modal.style.display = 'block';
						  </script>";
				} else {
					$email_query = "SELECT * FROM `faculty` WHERE `email` = '$email'";
					$email_result = mysqli_query($link, $email_query);
					if (mysqli_num_rows($email_result) > 0) {
						echo "
						<script>Swal.fire({
							icon: 'error',
							title: 'Faculty inputed is already in the database.',
							confirmButtonColor: '#bf5b5b',
						  });
						  var modal = document.getElementById('myModal');
						  modal.style.display = 'block';
						  </script>";
					} else {
						$sql = "INSERT INTO `faculty`(`idno`, `fname`, `mname`, `lname`, `sex`, `addrs`, `cpno`,`department`,`position`,`faculty_stat`,`email`, `passw`) VALUES ('$idno', '$fname', '$mname', '$lname', '$sex', '$addrs', '$cpno', '$dept', '$position','Active', '$email', '$pass')";
						if ($link->query($sql) === TRUE) {
							echo "<script>Swal.fire({
								icon: 'success',
								title: 'Data inserted successfully!',
								confirmButtonColor: '#a5dc86',
							  });
							  </script>"; 
						} else {
							echo "Error: " . $sql . "<br>" . $link->error;
						}
					}
				}
			}

			?>

<?php
if (isset($_POST['create']) && $_POST['labID'] != "") {

    $message =  '';

    $labID = $_POST['labID'];
    $labname = $_POST['labname'];
    $labLoc = $_POST['labLoc'];

    require "db_connection.php";
    $name_query = "SELECT * FROM `laboratory` WHERE `labID` = '$labID' AND `labname` = '$labname' AND `labLoc` = '$labLoc'";
    $name_result = mysqli_query($link, $name_query);
    if (mysqli_num_rows($name_result) > 0) {
        echo "<script>Swal.fire({
			icon: 'error',
			title: 'The Laboratory already exists in the database..',
			confirmButtonColor: '#bf5b5b',
		  });
		  var modal = document.getElementById('myModal');
		  modal.style.display = 'block';
		  </script>";
    } else {
        $insert_query = "INSERT INTO `laboratory`(`labID`, `labname`, `labLoc`) VALUES ('$labID', '$labname', '$labLoc')";
        if (mysqli_query($link, $insert_query)) {
            echo "<script>Swal.fire({
				icon: 'success',
				title: 'Data inserted successfully!',
				confirmButtonColor: '#a5dc86',
			  });
			  </script>"; 
            exit;
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($link);
        }
    }
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

			// JavaScript for opening and closing the create laboratory modal
			var modal2 = document.getElementById("myModal2");
			var btn2 = document.getElementById("myBtn2");
			var span2 = document.getElementsByClassName("close")[1]; // Note: Use index 1 for the close button of the second modal

			btn2.onclick = function() {
				modal2.style.display = "block";
			}

			// Close button for the create laboratory modal
			span2.onclick = function() {
				modal2.style.display = "none";
			}

		</script>

	<script src="assets/js/script.js"></script>
</body>
</html>
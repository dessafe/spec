<?php
// Start the session to use $_SESSION
session_start();

// Include database connection
require "db_connection.php";

// Fetch faculty for the dropdown
$facultyQuery = "SELECT idno, fname, lname FROM faculty";
$facultyResult = mysqli_query($link, $facultyQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['labname'], $_POST['idno'], $_POST['dept'])) {
    $idno = $_POST['idno'];
    $labname = $_POST['labname'];
    $labLoc = $_POST['labLoc'];
    $dept = $_POST['dept'];
    $labStatus = "Active";  // Set the lab status to Active automatically
    
    // Insert the new laboratory data into the database
    $sql = "INSERT INTO `laboratory`(`idno`, `labname`, `labLoc`, `labStatus`, `depLocation`) 
            VALUES ('$idno', '$labname', '$labLoc', '$labStatus', '$dept')";
    if (mysqli_query($link, $sql)) {
        // Trigger success modal
        echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                });
              </script>";
    } else {
        // Trigger error modal
        echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    new bootstrap.Modal(document.getElementById('errorModal')).show();
                });
              </script>";
    }
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
    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .form-group input, .form-group select {
        width: 48%;
    }
    .form-group input, .form-group select {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #007bff;
        outline: none;
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }
    button:hover {
        background-color: #0056b3;
    }
    .container {
        padding: 0;
    }
</style>
<title>Add Laboratory</title>
</head>
<body style="background-color: #eee">

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bx-qr'></i>
        <span class="text">SpecSnap</span>
    </a>
    <ul class="side-menu top">
        <li><a href="ahomepage.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
        <li><a href="add_faculty.php"><i class='bx bxs-group'></i><span class="text">Add Faculty</span></a></li>
        <li class="active"><a href="add_laboratory.php"><i class='bx bxs-building-house'></i><span class="text">Add Laboratory</span></a></li>
    </ul>
    <ul class="side-menu">
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
    </ul>
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Admin | <?php echo $_SESSION["user"]; ?></a>
    </nav>

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Add Laboratory</h1>
            </div>
        </div>

        <div class="container">
            <form action="" method="post">
                <!-- Faculty and Name -->
                <div class="form-group">
                    <input type="text" name="labname" placeholder="Laboratory Name" required>
                    <select name="idno" required>
                        <option value="">--Select Faculty--</option>
                        <?php while ($row = mysqli_fetch_assoc($facultyResult)) : ?>
                            <option value="<?php echo $row['idno']; ?>">
                                <?php echo $row['fname'] . " " . $row['lname']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="labLoc" placeholder="Location" required class="form-control">
                    <select name="dept" class="form-control" required>
                        <option value="">--Select Department--</option>
                        <option value="Engineering Department">Engineering Department</option>
                        <option value="IT Department">IT Department</option>
                        <option value="Math Department">Math Department</option>
                    </select>
                </div>
                <button type="submit">Add Laboratory</button>
            </form>
        </div>
    </main>
</section>
<!-- CONTENT -->

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Laboratory added successfully!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ERROR MODAL -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Error adding laboratory.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Try Again</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>

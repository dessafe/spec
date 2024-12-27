<?php
require "db_connection.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idno']) && !empty($_POST['idno'])) {
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
    $confirm_pass = $_POST['confirm_password'];

    // Check if ID number already exists
    $idno_query = "SELECT * FROM `faculty` WHERE `idno` = '$idno'";
    $idno_result = mysqli_query($link, $idno_query);

    if (mysqli_num_rows($idno_result) > 0) {
        $errorMessage = 'Faculty ID number already exists.';
    } else {
        // Proceed with adding the faculty if ID number does not exist
        if ($stmt = mysqli_prepare($link, "INSERT INTO `faculty`(`idno`, `fname`, `mname`, `lname`, `sex`, `addrs`, `cpno`, `department`, `position`, `faculty_stat`, `email`, `passw`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', ?, ?)")) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $idno, $fname, $mname, $lname, $sex, $addrs, $cpno, $dept, $position, $email, $pass);

            if (mysqli_stmt_execute($stmt)) {
                $successMessage = 'Faculty added successfully!';
            } else {
                $errorMessage = 'Error adding faculty.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $errorMessage = 'Prepared statement failed.';
        }
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<title>SpecSnap</title>
<link rel="icon" href="assets/img/logo1.png">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Password Validation AJAX -->
<script>
$(document).ready(function(){
    $("#confirm_password").on("input", function() {
        var password = $("#password").val();
        var confirmPassword = $(this).val();
        
        if(password !== confirmPassword) {
            $("#password-mismatch-message").show();
        } else {
            $("#password-mismatch-message").hide();
        }
    });
});
</script>

<style>
/* Ensure no underline in the sidebar */
.side-menu li a {
    text-decoration: none;
}

.side-menu {
    padding: 0;
}

/* Adjust layout for form fields */
.row .col {
    padding-right: 10px;
}

/* Ensure proper form layout */
input[type="text"], input[type="email"], input[type="password"], select {
    width: 100%;
    padding: 10px;
}

input[type="text"], input[type="email"], input[type="password"] {
    margin-bottom: 10px;
}

/* Make the submit button more visible */
button[type="submit"] {
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    width: 100%;
}

#password-mismatch-message {
    color: red;
    font-size: 12px;
    display: none;
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
        <li>
            <a href="ahomepage.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="active">
            <a href="add_faculty.php">
                <i class='bx bxs-group'></i>
                <span class="text">Add Faculty</span>
            </a>
        </li>
        <li>
            <a href="add_laboratory.php">
                <i class='bx bxs-building-house'></i>
                <span class="text">Add Laboratory</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
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
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Admin | <?php echo $_SESSION["user"]; ?></a>
    </nav>
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Add Faculty</h1>
            </div>
        </div>

        <div class="container">
            <form action="" method="post">
                <!-- ID Number (First Field) -->
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="idno" placeholder="ID Number" class="form-control" required>
                    </div>
                </div>

                <!-- First Name, Middle Name, Last Name, Gender -->
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="fname" placeholder="First Name" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="text" name="mname" placeholder="Middle Name" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="text" name="lname" placeholder="Last Name" class="form-control" required>
                    </div>
                    <div class="col">
                        <select name="sex" class="form-control" required>
                            <option value="">Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <!-- Address, Email, Contact Number -->
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="addrs" placeholder="Address" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="text" name="cpno" placeholder="Contact Number" class="form-control" required>
                    </div>
                </div>

                <!-- Department, Position -->
                <div class="row mb-3">
                    <div class="col">
                        <select name="dept" class="form-control" required>
                            <option value="">--Select Department--</option>
                            <option value="Engineering Department">Engineering Department</option>
                            <option value="IT Department">IT Department</option>
                            <option value="Math Department">Math Department</option>
                        </select>
                    </div>
                    <div class="col">
                        <select name="position" class="form-control" required>
                            <option value="">--Select Position--</option>
                            <option value="Dean">Dean</option>
                            <option value="Department Head">Department Head</option>
                            <option value="Faculty">Faculty</option>
                        </select>
                    </div>
                </div>

                <!-- Password and Confirm Password -->
                <div class="row mb-3">
                    <div class="col">
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control" required>
                        <div id="password-mismatch-message">Passwords do not match</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Add Faculty</button>
            </form>
        </div>

        <!-- Success or Error Modal -->
        <?php if ($successMessage): ?>
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog d-flex align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo $successMessage; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            </script>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog d-flex align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalLabel">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo $errorMessage; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            </script>
        <?php endif; ?>

    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->
</body>
</html>

<?php
session_start();
include "db_connection.php";

$successMessage = "";
$errorMessage = "";

if (isset($_POST['add']) && $_POST['name'] != "") {
    // Get input values from the form
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $brand = mysqli_real_escape_string($link, $_POST['brand']);
    $sponsorName = mysqli_real_escape_string($link, $_POST['sponsorName']);
    $category = mysqli_real_escape_string($link, $_POST['category']);
    $serialNo = mysqli_real_escape_string($link, $_POST['serialNo']);
    $doAcquisition = mysqli_real_escape_string($link, $_POST['doAcquisition']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $idno = $_SESSION['QR']; 
    $labID = mysqli_real_escape_string($link, $_POST['labID']);

    // Check if the provided labID exists in the laboratory table
    $lab_query = "SELECT * FROM `laboratory` WHERE `labID` = '$labID'";
    $lab_result = mysqli_query($link, $lab_query);
    if (mysqli_num_rows($lab_result) == 0) {
        $errorMessage = "Invalid lab ID. Please provide a valid lab ID.";
    } else {
        $device_query = "SELECT * FROM `hardwares` WHERE `serialNo` = '$serialNo'";
        $device_result = mysqli_query($link, $device_query);
        if (mysqli_num_rows($device_result) > 0) {
            $errorMessage = "The device already exists in the database.";
        } else {
            $sql = "INSERT INTO `hardwares`(`name`, `brand`, `sponsorName`, `category`, `serialNo`, `doAcquisition`, `status`, `idno`, `labID`) 
                    VALUES ('$name', '$brand', '$sponsorName', '$category', '$serialNo', '$doAcquisition', '$status', '$idno', '$labID')";
            if ($link->query($sql) === TRUE) {
                $successMessage = "Hardware successfully added.";
            } else {
                $errorMessage = "Error inserting data.";
            }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>SpecSnap</title>
    <link rel="icon" href="assets/img/logo1.png">
    <title>Add Hardware</title>
    <style>
        #sidebar ul {
            padding-left: 0;
        }
        a {
            text-decoration: none;
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
    </style>
</head>
<body style="background-color: #eee">
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-qr'></i>
            <span class="text">SpecSnap</span>
        </a>
        <ul class="side-menu top">
            <li><a href="faculty.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li class="active"><a href="add_hardware.php"><i class='bx bxs-building-house'></i><span class="text">Add Hardware</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
        </ul>
    </section>
    
    <section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">User | <?php echo $_SESSION["user"]; ?></a>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Add Hardware</h1>
            </div>
        </div>
    <div class="container">
        <form method="POST" action="">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="brand" class="form-label">Brand</label>
                    <input list="brand-options" class="form-control" id="brand" name="brand" required>
                    <datalist id="brand-options">
                        <option value="Acer">
                        <option value="Apple">
                        <option value="Dell">
                        <option value="HP">
                        <option value="Lenovo">
                        <option value="Samsung">
                    </datalist>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="sponsorName" class="form-label">Sponsor Name</label>
                    <input type="text" class="form-control" id="sponsorName" name="sponsorName" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <input list="category-options" class="form-control" id="category" name="category" required>
                    <datalist id="category-options">
                        <option value="Aircon">
                        <option value="External Hard Drive">
                        <option value="Keyboard">
                        <option value="Monitor">
                        <option value="Mouse">
                        <option value="Printer">
                        <option value="Projector">
                        <option value="RAM">
                        <option value="System Unit">
                        <option value="UPS">
                    </datalist>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="serialNo" class="form-label">Serial Number</label>
                    <input type="text" class="form-control" id="serialNo" name="serialNo" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="doAcquisition" class="form-label">Date of Acquisition</label>
                    <input type="date" class="form-control" id="doAcquisition" name="doAcquisition" required>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="" disabled selected>--Select--</option>
                        <option value="For Disposal">For Disposal</option>
                        <option value="Need Repair">Need Repair</option>
                        <option value="Working">Working</option>
                        <option value="Not Working">Not Working</option>
                    </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="labID" class="form-label">Lab ID</label>
                    <select class="form-control" id="labID" name="labID" required>
                        <option value="" disabled selected>--Select--</option>
                        <?php
                        // Fetch lab names and IDs from the laboratory table
                        $lab_query = "SELECT `labID`, `labName` FROM `laboratory`";
                        $lab_result = mysqli_query($link, $lab_query);

                        // Check if there are labs available
                        if (mysqli_num_rows($lab_result) > 0) {
                            while ($row = mysqli_fetch_assoc($lab_result)) {
                                // The 'value' attribute is the labID, the displayed text is labName
                                echo "<option value='" . $row['labID'] . "'>" . $row['labName'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No labs available</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="idno" class="form-label">Faculty ID</label>
                    <input type="text" class="form-control" id="idno" name="idno" value="<?php echo $_SESSION['QR']; ?>" disabled>
                </div>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Hardware</button>
        </form>
    </div>
    </main>
</section>

<!-- Success Modal -->
<?php if (!empty($successMessage)): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Added 'modal-dialog-centered' -->
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

<!-- Error Modal -->
<?php if (!empty($errorMessage)): ?>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Added 'modal-dialog-centered' -->
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

</body>
</html>

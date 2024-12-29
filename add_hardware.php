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
        // Check if the serial number already exists
        $device_query = "SELECT * FROM `hardwares` WHERE `serialNo` = '$serialNo'";
        $device_result = mysqli_query($link, $device_query);
        if (mysqli_num_rows($device_result) > 0) {
            $errorMessage = "The device already exists in the database.";
        } else {
            // If "Other" option for brand or category is selected, use custom values
            if ($brand === 'Other') {
                $brand = mysqli_real_escape_string($link, $_POST['brandOther']);
            }
            if ($category === 'Other') {
                $category = mysqli_real_escape_string($link, $_POST['categoryOther']);
            }

            // Insert the hardware record
            $sql = "INSERT INTO `hardwares`(`name`, `brand`, `sponsorName`, `category`, `serialNo`, `doAcquisition`, `status`, `idno`, `labID`) 
                    VALUES ('$name', '$brand', '$sponsorName', '$category', '$serialNo', '$doAcquisition', '$status', '$idno', '$labID')";
            if ($link->query($sql) === TRUE) {
                // Get the deviceID of the newly inserted hardware
                $deviceID = mysqli_insert_id($link);

                // Check if any images were uploaded
                if (isset($_FILES['hardwareImages'])) {
                    $files = $_FILES['hardwareImages'];
                    $fileCount = count($files['name']);
                    
                    // Loop through all uploaded files
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $files['name'][$i];
                        $fileTmpName = $files['tmp_name'][$i];
                        $fileError = $files['error'][$i];
                        $fileSize = $files['size'][$i];

                        if ($fileError === 0 && $fileSize <= 5000000) { // 5MB max size
                            // Generate a unique file name
                            $fileNewName = uniqid('', true) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
                            $fileDestination = 'uploads/hardware_images/' . $fileNewName;

                            // Move the uploaded file to the desired directory
                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                // Insert image record into hardware_images table
                                $imageQuery = "INSERT INTO `hardware_images` (`deviceID`, `imagePath`, `uploadDate`) 
                                                VALUES ('$deviceID', '$fileDestination', NOW())";
                                mysqli_query($link, $imageQuery);
                            }
                        }
                    }
                }
                $successMessage = "Hardware successfully added with images.";
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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-control" id="brand" name="brand" required>
                                <option value="" disabled selected>Select a brand</option>
                                <option value="Acer">Acer</option>
                                <option value="Apple">Apple</option>
                                <option value="Dell">Dell</option>
                                <option value="HP">HP</option>
                                <option value="Lenovo">Lenovo</option>
                                <option value="Samsung">Samsung</option>
                                <option value="Other">Other (type below)</option>
                            </select>
                            <input type="text" id="brand-other" class="form-control mt-2" name="brandOther" placeholder="Enter custom brand" style="display:none;" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="sponsorName" class="form-label">Sponsor Name</label>
                            <input type="text" class="form-control" id="sponsorName" name="sponsorName" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Aircon">Aircon</option>
                                <option value="External Hard Drive">External Hard Drive</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Printer">Printer</option>
                                <option value="Projector">Projector</option>
                                <option value="RAM">RAM</option>
                                <option value="System Unit">System Unit</option>
                                <option value="UPS">UPS</option>
                                <option value="Other">Other (type below)</option>
                            </select>
                            <input type="text" id="category-other" class="form-control mt-2" name="categoryOther" placeholder="Enter custom category" style="display:none;" />
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
                                <option value="Need Repair/Cleaning">Need Repair/Cleaning</option>
                                <option value="Working">Working</option>
                                <option value="Not Working">Not Working</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="labID" class="form-label">Lab ID</label>
                            <?php
                            $idno = $_SESSION['QR'];
                            $lab_query = "SELECT `labID`, `labName` FROM `laboratory` WHERE `idno` = '$idno'";
                            $lab_result = mysqli_query($link, $lab_query);
                            if (mysqli_num_rows($lab_result) > 0) {
                                echo "<select class='form-control' id='labID' name='labID' required>";
                                echo "<option value='' disabled selected>-- Select a Lab --</option>";
                                while ($row = mysqli_fetch_assoc($lab_result)) {
                                    $labID = $row['labID'];
                                    $labName = $row['labName'];
                                    echo "<option value='$labID'>$labName</option>";
                                }
                                echo "</select>";
                            } else {
                                echo "<input type='text' class='form-control' value='No labs available' disabled>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="idno" class="form-label">Faculty ID</label>
                            <input type="text" class="form-control" id="idno" name="idno" value="<?php echo $_SESSION['QR']; ?>" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="hardwareImages" class="form-label">Upload Images</label>
                            <input type="file" class="form-control" id="hardwareImages" name="hardwareImages[]" multiple>
                        </div>
                    </div>

                    <button type="submit" name="add" class="btn btn-primary">Add Hardware</button>
                </form>

                <?php
                if ($successMessage != "") {
                    echo "<script>Swal.fire('Success', '$successMessage', 'success');</script>";
                }
                if ($errorMessage != "") {
                    echo "<script>Swal.fire('Error', '$errorMessage', 'error');</script>";
                }
                ?>
            </div>
        </main>
    </section>

    <script>
        // Toggle visibility of custom brand and category inputs
        document.getElementById('brand').addEventListener('change', function() {
            document.getElementById('brand-other').style.display = (this.value === 'Other') ? 'block' : 'none';
        });
        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('category-other').style.display = (this.value === 'Other') ? 'block' : 'none';
        });
    </script>
</body>
</html>

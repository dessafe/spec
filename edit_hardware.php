<?php
session_start();
include "db_connection.php";

$successMessage = "";
$errorMessage = "";

// Get the hardwareID from the URL or form submission (e.g., via GET parameter)
if (isset($_GET['deviceID'])) {
    $hardwareID = $_GET['deviceID'];

    // Fetch the hardware details for the specified hardwareID
    $hardware_query = "SELECT * FROM `hardwares` WHERE `deviceID` = '$hardwareID'";
    $hardware_result = mysqli_query($link, $hardware_query);
    if (mysqli_num_rows($hardware_result) == 0) {
        $errorMessage = "Hardware not found.";
    } else {
        $hardware = mysqli_fetch_assoc($hardware_result);
    }
} else {
    $errorMessage = "Invalid hardware ID.";
}

// Process the form submission
if (isset($_POST['update']) && $_POST['name'] != "") {
    // Get input values from the form
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $brand = mysqli_real_escape_string($link, $_POST['brand']);
    $sponsorName = mysqli_real_escape_string($link, $_POST['sponsorName']);
    $category = mysqli_real_escape_string($link, $_POST['category']);
    $serialNo = mysqli_real_escape_string($link, $_POST['serialNo']);
    $doAcquisition = mysqli_real_escape_string($link, $_POST['doAcquisition']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $labID = mysqli_real_escape_string($link, $_POST['labID']);

    // Check if the labID exists
    $lab_query = "SELECT * FROM `laboratory` WHERE `labID` = '$labID'";
    $lab_result = mysqli_query($link, $lab_query);
    if (mysqli_num_rows($lab_result) == 0) {
        $errorMessage = "Invalid lab ID.";
    } else {
        // Check if the serial number already exists, except for the current hardwareID
        $hardware_check_query = "SELECT * FROM `hardwares` WHERE `serialNo` = '$serialNo' AND `deviceID` != '$hardwareID'";
        $hardware_check_result = mysqli_query($link, $hardware_check_query);
        if (mysqli_num_rows($hardware_check_result) > 0) {
            $errorMessage = "The device with this serial number already exists.";
        } else {
            // If "Other" option for brand or category is selected, use custom values
            if ($brand === 'Other') {
                $brand = mysqli_real_escape_string($link, $_POST['brandOther']);
            }
            if ($category === 'Other') {
                $category = mysqli_real_escape_string($link, $_POST['categoryOther']);
            }

            // Update the hardware record
            $update_query = "UPDATE `hardwares` 
                             SET `name` = '$name', `brand` = '$brand', `sponsorName` = '$sponsorName', `category` = '$category', 
                                 `serialNo` = '$serialNo', `doAcquisition` = '$doAcquisition', `status` = '$status', `labID` = '$labID' 
                             WHERE `deviceID` = '$hardwareID'";

            if (mysqli_query($link, $update_query)) {
                // Check if new images were uploaded
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
                                                VALUES ('$hardwareID', '$fileDestination', NOW())";
                                mysqli_query($link, $imageQuery);
                            }
                        }
                    }
                }
                $successMessage = "Hardware successfully updated with images.";
            } else {
                $errorMessage = "Error updating data: " . mysqli_error($link);  // Show SQL error message
            }
        }
    }
}
?>

<!-- Displaying Success or Error Messages -->
<?php if ($successMessage != ""): ?>
    <div class="success-message"><?php echo $successMessage; ?></div>
<?php endif; ?>

<?php if ($errorMessage != ""): ?>
    <div class="error-message"><?php echo $errorMessage; ?></div>
<?php endif; ?>

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
            <li class="active"><a href="add_hardware.php"><i class='bx bxs-building-house'></i><span class="text">Edit Hardware</span></a></li>
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
                    <h1>Edit Hardware</h1>
                </div>
            </div>
            <div class="container">
                <form method="POST" action="edit_hardware.php?deviceID=<?php echo $hardwareID; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $hardware['name']; ?>" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-control" id="brand" name="brand" required>
                                <option value="" disabled>Select a brand</option>
                                <option value="Acer" <?php if ($hardware['brand'] == 'Acer') echo 'selected'; ?>>Acer</option>
                                <option value="Apple" <?php if ($hardware['brand'] == 'Apple') echo 'selected'; ?>>Apple</option>
                                <option value="Dell" <?php if ($hardware['brand'] == 'Dell') echo 'selected'; ?>>Dell</option>
                                <option value="HP" <?php if ($hardware['brand'] == 'HP') echo 'selected'; ?>>HP</option>
                                <option value="Lenovo" <?php if ($hardware['brand'] == 'Lenovo') echo 'selected'; ?>>Lenovo</option>
                                <option value="Samsung" <?php if ($hardware['brand'] == 'Samsung') echo 'selected'; ?>>Samsung</option>
                                <option value="Other" <?php if ($hardware['brand'] == 'Other') echo 'selected'; ?>>Other (type below)</option>
                            </select>
                            <input type="text" id="brand-other" class="form-control mt-2" name="brandOther" value="<?php echo ($hardware['brand'] == 'Other') ? $hardware['brandOther'] : ''; ?>" placeholder="Enter custom brand" style="display: <?php echo ($hardware['brand'] == 'Other') ? 'block' : 'none'; ?>;" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="sponsorName" class="form-label">Sponsor Name</label>
                            <input type="text" class="form-control" id="sponsorName" name="sponsorName" value="<?php echo $hardware['sponsorName']; ?>" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="" disabled>Select a category</option>
                                <option value="Aircon" <?php if ($hardware['category'] == 'Aircon') echo 'selected'; ?>>Aircon</option>
                                <option value="External Hard Drive" <?php if ($hardware['category'] == 'External Hard Drive') echo 'selected'; ?>>External Hard Drive</option>
                                <option value="Keyboard" <?php if ($hardware['category'] == 'Keyboard') echo 'selected'; ?>>Keyboard</option>
                                <option value="Monitor" <?php if ($hardware['category'] == 'Monitor') echo 'selected'; ?>>Monitor</option>
                                <option value="Mouse" <?php if ($hardware['category'] == 'Mouse') echo 'selected'; ?>>Mouse</option>
                                <option value="Printer" <?php if ($hardware['category'] == 'Printer') echo 'selected'; ?>>Printer</option>
                                <option value="Projector" <?php if ($hardware['category'] == 'Projector') echo 'selected'; ?>>Projector</option>
                                <option value="RAM" <?php if ($hardware['category'] == 'RAM') echo 'selected'; ?>>RAM</option>
                                <option value="System Unit" <?php if ($hardware['category'] == 'System Unit') echo 'selected'; ?>>System Unit</option>
                                <option value="UPS" <?php if ($hardware['category'] == 'UPS') echo 'selected'; ?>>UPS</option>
                                <option value="Other" <?php if ($hardware['category'] == 'Other') echo 'selected'; ?>>Other (type below)</option>
                            </select>
                            <input type="text" id="category-other" class="form-control mt-2" name="categoryOther" value="<?php echo ($hardware['category'] == 'Other') ? $hardware['categoryOther'] : ''; ?>" placeholder="Enter custom category" style="display: <?php echo ($hardware['category'] == 'Other') ? 'block' : 'none'; ?>;" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="serialNo" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serialNo" name="serialNo" value="<?php echo $hardware['serialNo']; ?>" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="doAcquisition" class="form-label">Date of Acquisition</label>
                            <input type="date" class="form-control" id="doAcquisition" name="doAcquisition" value="<?php echo $hardware['doAcquisition']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="For Disposal" <?php if ($hardware['status'] == 'For Disposal') echo 'selected'; ?>>For Disposal</option>
                                <option value="Need Repair/Cleaning" <?php if ($hardware['status'] == 'Need Repair/Cleaning') echo 'selected'; ?>>Need Repair/Cleaning</option>
                                <option value="Working" <?php if ($hardware['status'] == 'Working') echo 'selected'; ?>>Working</option>
                                <option value="Not Working" <?php if ($hardware['status'] == 'Not Working') echo 'selected'; ?>>Not Working</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="labID" class="form-label">Lab ID</label>
                            <select class="form-control" id="labID" name="labID" required>
                                <option value="" disabled>Select a Lab</option>
                                <!-- Dynamically populate labs -->
                                <?php
                                $idno = $_SESSION['QR'];
                                $lab_query = "SELECT `labID`, `labName` FROM `laboratory` WHERE `idno` = '$idno'";
                                $lab_result = mysqli_query($link, $lab_query);
                                while ($row = mysqli_fetch_assoc($lab_result)) {
                                    $labID = $row['labID'];
                                    $labName = $row['labName'];
                                    echo "<option value='$labID'" . ($hardware['labID'] == $labID ? ' selected' : '') . ">$labName</option>";
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

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="hardwareImages" class="form-label">Upload Images</label>
                            <input type="file" class="form-control" id="hardwareImages" name="hardwareImages[]" multiple>
                        </div>
                    </div>

                    <button type="submit" name="update">Update Hardware</button>
                </form>

                <?php if ($successMessage) : ?>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '<?php echo $successMessage; ?>',
                            willClose: () => {
                                // Redirect after SweetAlert closes
                                window.location.href = "hview.php";
                            }
                        });
                    </script>
                <?php endif; ?>
                <?php
                if ($errorMessage != "") {
                    echo "<script>Swal.fire('Error', '$errorMessage', 'error');</script>";
                }
                ?>
            </div>
        </main>
    </section>

    <script>
        // Toggle visibility of custom brand and category inputs based on the selected value
        document.getElementById('brand').addEventListener('change', function() {
            document.getElementById('brand-other').style.display = (this.value === 'Other') ? 'block' : 'none';
        });
        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('category-other').style.display = (this.value === 'Other') ? 'block' : 'none';
        });
    </script>
</body>
</html>

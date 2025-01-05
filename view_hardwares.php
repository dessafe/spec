<?php
// Include database connection
include "db_connection.php";

// Check if deviceID is provided
if (isset($_GET['deviceID'])) {
    $deviceID = $_GET['deviceID'];

    // Fetch hardware details from the hardwares table
    $hardware_query = "SELECT * FROM `hardwares` WHERE `deviceID` = '$deviceID'";
    $hardware_result = mysqli_query($link, $hardware_query);
    
    if (mysqli_num_rows($hardware_result) > 0) {
        $hardware = mysqli_fetch_assoc($hardware_result);
        
        // Fetch images associated with this hardware from the hardware_images table
        $image_query = "SELECT * FROM `hardware_images` WHERE `deviceID` = '$deviceID'";
        $image_result = mysqli_query($link, $image_query);
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <link rel="stylesheet" href="assets/css/fac-modal.css">
    <title>SpecSnap | Hardware Details</title>
    <link rel="icon" href="assets/img/logo1.png">
    <style>
        #sidebar ul { padding-left: 0; }
        .hardware-details {
            margin: 20px;
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .hardware-images img {
            display: inline-block;
            width: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bx-qr'></i><span class="text">SpecSnap</span></a>
        <ul class="side-menu top">
            <li><a href="faculty.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="hview.php" class="active"><i class='bx bxs-book-content'></i><span class="text">Hardware</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
        </ul>
    </section>

    <!-- Content Section -->
    <section id="content">
    <!-- <nav><i class='bx bx-menu'></i><a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a></nav> -->
        <main>
            <div class="head-title">
                <div class="left"><h1>Hardware Details</h1></div>
            </div>

            <div class="hardware-details">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($hardware['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Brand</th>
                        <td><?php echo htmlspecialchars($hardware['brand']); ?></td>
                    </tr>
                    <tr>
                        <th>Sponsor Name</th>
                        <td><?php echo htmlspecialchars($hardware['sponsorName']); ?></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td><?php echo htmlspecialchars($hardware['category']); ?></td>
                    </tr>
                    <tr>
                        <th>Serial No</th>
                        <td><?php echo htmlspecialchars($hardware['serialNo']); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Acquisition</th>
                        <td><?php echo htmlspecialchars($hardware['doAcquisition']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($hardware['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Lab ID</th>
                        <td><?php echo htmlspecialchars($hardware['labID']); ?></td>
                    </tr>
                </table>

                <h3>Images</h3>
                <div class="hardware-images">
                    <?php
                    if (mysqli_num_rows($image_result) > 0) {
                        while ($image = mysqli_fetch_assoc($image_result)) {
                            echo '<img src="' . $image['imagePath'] . '" alt="Hardware Image">';
                        }
                    } else {
                        echo "No images available for this hardware.";
                    }
                    ?>
                </div>
            </div>
        </main>
    </section>

    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
    } else {
        echo "Hardware not found.";
    }
} else {
    echo "Invalid device ID.";
}
?>

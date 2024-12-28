<?php
session_start();
include "db_connection.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT h.*, hi.imagePath FROM `hardwares` h
        LEFT JOIN `hardware_images` hi ON h.deviceID = hi.deviceID";
if (isset($_SESSION["QR"]) && $_SESSION["QR"] !== '') {
    $sql .= " WHERE h.idno = '{$_SESSION["QR"]}'";
}
if ($search) {
    $sql .= " AND (h.deviceID LIKE '%$search%' OR h.name LIKE '%$search%' OR h.doAcquisition LIKE '%$search%' OR h.status LIKE '%$search%' OR h.labID LIKE '%$search%')";
}

// Correct fetching of result rows
$result = $link->query($sql);
$hardwareData = []; // Initialize the hardware data array

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $deviceID = $row['deviceID']; // Fetch the deviceID for the current row
        $hardwareData[$deviceID] = [
            'deviceID' => $row['deviceID'],
            'name' => $row['name'],
            'doAcquisition' => $row['doAcquisition'],
            'status' => $row['status'],
            'labID' => $row['labID'],
            'images' => [] // Initialize empty array to store image paths
        ];

        if ($row['imagePath']) {
            $hardwareData[$deviceID]['images'][] = $row['imagePath'];
        }
    }
}
?>

<!-- HTML FRONTEND (Displaying Hardware List) -->

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
    </style>    
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

                $('#qrCodeImage').attr('src', qrCodeUrl);
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

            $('#example').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [[10, 25, 50, -1], ["10", "25", "50", "All"]]
            });
            $('.view-images-link').click(function(e) {
                e.preventDefault();
                var deviceID = $(this).data('deviceid');

                // Clear previous carousel items
                $('#carouselImages').empty();

                // Get the image paths for the selected device
                var images = hardwareData[deviceID].images;

                // Check if there are images for the device
                if (images.length > 0) {
                    // Loop through images and add them to the carousel
                    images.forEach(function(imagePath, index) {
                        var activeClass = (index === 0) ? 'active' : '';  // Make the first image active
                        $('#carouselImages').append(`
                            <div class="carousel-item ${activeClass}">
                                <img src="${imagePath}" class="d-block w-100" alt="Hardware Image ${index + 1}">
                            </div>
                        `);
                    });
                    // Ensure the carousel is initialized correctly
                    $('#imageCarousel').carousel('dispose'); // Dispose of any previous instance
                    $('#imageCarousel').carousel({ interval: false }); // Reinitialize carousel

                } else {
                    // If no images, display a "No Image" message
                    $('#carouselImages').append(`
                        <div class="carousel-item active">
                            <div class="d-block w-100 text-center">
                                <p>No images available for this device.</p>
                            </div>
                        </div>
                    `);
                }

                // Show the modal
                $('#viewImagesModal').modal('show');
            });
            const toggleIcon = $(this).find('i');
    const deviceID = $(this).attr('href').split('id=')[1]; // Extract the device ID from the URL
    const isToggled = toggleIcon.hasClass('fa-toggle-on');

    // Toggle classes for on/off visually
    toggleIcon.toggleClass('fa-toggle-on fa-toggle-off');

    // Send AJAX request to update the status on the server
    $.ajax({
        url: 'hchange.php',
        type: 'POST',
        data: {
            id: deviceID,
            status: isToggled ? 'off' : 'on', // Send the opposite of the current state
        },
        success: function (response) {
            // Handle server response if needed
            console.log(response);
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            // Revert the toggle state visually in case of failure
            toggleIcon.toggleClass('fa-toggle-on fa-toggle-off');
        },
    });
});

        // Prepare the hardwareData in JavaScript
        var hardwareData = <?php echo json_encode(value: $hardwareData); ?>;
    </script>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-qr'></i> <!-- QR code icon here -->
            <span class="text">SpecSnap</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="faculty.php">
                    <i class='bx bxs-dashboard'></i>
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
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a>
        </nav>

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Hardware List</h1>
                </div>
            </div>

            <!-- Hardware Table -->
            <?php
                if ($result && $result->num_rows > 0) {
                    echo '<div class="table-responsive">
                            <table id="example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Device Id</th>
                                        <th>Name</th>
                                        <th>Date of Acquisition</th>
                                        <th>Status</th>
                                        <th>LabId</th>
                                        <th>Action</th>
                                        <th>View Images</th>
                                        <th>QR Code</th> <!-- New column for QR Code -->
                                    </tr>
                                </thead>
                                <tbody>';
                    foreach ($hardwareData as $deviceID => $device) {
                        $imageUrl = !empty($device['images']) ? $device['images'][0] : 'assets/img/default-image.jpg';
                        echo '<tr>';
                        echo '<td>' . $device['deviceID'] . '</td>';
                        echo '<td>' . $device['name'] . '</td>';
                        echo '<td>' . $device['doAcquisition'] . '</td>';
                        echo '<td>';
                        if ($device['status'] === 'Working') {
                            echo '<span style="background: #4CAF50; color: white; padding: 6px 16px; border-radius: 20px;">' . $device['status'] . '</span>';
                        } else {
                            echo '<span style="background: #FF6347; color: white; padding: 6px 16px; border-radius: 20px;">' . $device['status'] . '</span>';
                        }
                        echo '</td>';
                        echo '<td>' . $device['labID'] . '</td>';

                        // Action link (toggle switch)
                        echo '<td><a href="hchange.php?id=' . $device['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a></td>';

                        // View images link
                        echo '<td><a href="#" class="view-images-link" data-deviceid="' . $device['deviceID'] . '"><i class="fa fa-eye"></i> View Images</a></td>';

                        // QR code link (Move to the last column)
                        echo '<td><a href="#" class="link-dark qr-link" data-deviceid="' . $device['deviceID'] . '" data-name="' . urlencode($device['name']) . '" data-doacquisition="' . urlencode($device['doAcquisition']) . '" data-status="' . urlencode($device['status']) . '" data-labid="' . urlencode($device['labID']) . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a></td>';

                        echo '</tr>';
                    }
                    echo '</tbody></table></div>';
                } else {
                    echo "<p>No hardware records found.</p>";
                }
            ?>
        </main>
    </section>

    <!-- View Images Modal -->
     <!-- View Images Modal -->
     <div class="modal fade" id="viewImagesModal" tabindex="-1" aria-labelledby="viewImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewImagesModalLabel">Hardware Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carouselImages">
                            <!-- Images will be dynamically added here -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid" style="max-width: 100%; height: auto;">
                    <div class="mt-3">
                        <a id="downloadLink" href="#" class="btn btn-success">Download QR Code</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>

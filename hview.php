<?php
    session_start();
    include "db_connection.php";

    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Fetch the logged-in faculty's `idno`
    $faculty_idno = $_SESSION["QR"];  // Assuming the faculty `idno` is stored in session

    // SQL to get hardware from labs managed by the faculty member
    $sql = "SELECT h.*, hi.imagePath 
            FROM `hardwares` h
            LEFT JOIN `hardware_images` hi ON h.deviceID = hi.deviceID
            JOIN `laboratory` l ON h.labID = l.labID
            WHERE l.idno = '$faculty_idno'";  // Only get hardware for the faculty's managed labs

    // Apply search filter if provided
    if ($search) {
        $sql .= " AND (h.deviceID LIKE '%$search%' OR h.name LIKE '%$search%' OR h.doAcquisition LIKE '%$search%' OR h.status LIKE '%$search%' OR h.labID LIKE '%$search%')";
    }

    // Correct fetching of result rows
    $result = $link->query($sql);
    $hardwareData = [];  // Initialize the hardware data array

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $deviceID = $row['deviceID'];  // Fetch the deviceID for the current row
            $hardwareData[$deviceID] = [
                'deviceID' => $row['deviceID'],
                'name' => $row['name'],
                'doAcquisition' => $row['doAcquisition'],
                'status' => $row['status'],
                'labID' => $row['labID'],
                'images' => []  // Initialize empty array to store image paths
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.19/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

            // Send an AJAX request to fetch lab and faculty details
            $.ajax({
                url: 'get_lab_and_faculty.php',
                type: 'GET',
                data: { deviceID: deviceID },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.error) {
                        alert("Error: " + data.error);
                    } else {
                        var labName = data.labName;
                        var facultyName = data.facultyName;

                        // Create the hardware details string
                        var hardwareDetails = "Name: " + name + "\nBrand: " + data.brand + "\nSponsor Name: " + data.sponsorName + "\nCategory: " + data.category +
                                            "\nSerial No: " + data.serialNo + "\nDate of Acquisition: " + doAcquisition + "\nStatus: " + status + 
                                            "\nLab: " + labName + "\nHandled by: " + facultyName;

                        // Generate the QR code URL
                        var qrCodeUrl = 'qrcode.php?details=' + encodeURIComponent(hardwareDetails);

                        // Update the QR code image and download link
                        $('#qrCodeImage').attr('src', qrCodeUrl);
                        $('#downloadLink').attr('href', qrCodeUrl);

                        // Show the modal
                        $('#qrModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX Error: " + error);
                }
            });
        });

        // Handle QR code download
        $('#downloadLink').click(function(e) {
            e.preventDefault(); // Prevent default action
            
            // Get the QR code image source URL
            var qrCodeUrl = $('#qrCodeImage').attr('src');
            
            // Create an anchor element
            var a = document.createElement('a');
            a.href = qrCodeUrl;
            a.download = 'QRCode.png';  // Set download filename
            
            // Append the anchor element to the body and trigger a click event to start the download
            document.body.appendChild(a);
            a.click();
            
            // Remove the anchor element from the DOM after triggering the click
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

        // Prepare the hardwareData in JavaScript
        var hardwareData = <?php echo json_encode(value: $hardwareData); ?>;
    });

    $(document).on('change', '.status-dropdown', function() {
        var deviceId = $(this).data('deviceid');
        var status = $(this).val();

        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update the status of this device?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, proceed with the status update
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: {
                        deviceID: deviceId,
                        status: status
                    },
                    success: function(response) {
                        if (response.trim() === 'success') {
                            // Success alert after update
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: 'The device status has been updated successfully!',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Error alert if something went wrong
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the status: ' + response,
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        Swal.fire({
                            icon: 'error',
                            title: 'AJAX Error',
                            text: 'An error occurred: ' + error,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                // If user cancels, do nothing
                Swal.fire({
                    icon: 'info',
                    title: 'Cancelled',
                    text: 'The status update has been cancelled.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
    var deviceId = $(this).data('deviceid');

    // SweetAlert2 confirmation for delete
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this hardware item?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, proceed with the delete action
            $.ajax({
                url: 'delete_hardware.php',
                type: 'POST',
                data: { deviceID: deviceId },
                success: function(response) {
                    // Use trim to remove any unwanted spaces or newlines
                    if (response.trim() === 'success') {
                        // Success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The hardware item has been deleted.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload page after deletion
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting the item.',
                            confirmButtonText: 'Try Again'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: 'An error occurred: ' + error,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});



    // For Edit action, you can show a modal or redirect to an edit page
    $(document).on('click', '.edit-btn', function() {
        var deviceId = $(this).data('deviceid');
        // Redirect to edit page with the deviceID as a URL parameter or show a modal for editing
        window.location.href = 'edit_hardware.php?deviceID=' + deviceId;
    });


</script>

</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-qr'></i>
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
                                        <th>View Images</th>
                                        <th>QR Code</th>
                                        <th>Actions</th> <!-- New Actions Column -->
                                    </tr>
                                </thead>
                                <tbody>';
                    foreach ($hardwareData as $deviceID => $device) {
                        $imageUrl = !empty($device['images']) ? $device['images'][0] : 'assets/img/default-image.jpg';
                        echo '<tr>';
                        echo '<td>' . $device['deviceID'] . '</td>';
                        echo '<td>' . $device['name'] . '</td>';
                        echo '<td>' . $device['doAcquisition'] . '</td>';

                        // Dropdown for status
                        echo '<td>';
                        echo '<select class="form-select status-dropdown" data-deviceid="' . $device['deviceID'] . '">';
                        $statusOptions = ['Working', 'Not Working', 'For Disposal', 'Need Repair/Cleaning'];
                        foreach ($statusOptions as $option) {
                            $selected = $device['status'] === $option ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        echo '</select>';
                        echo '</td>';

                        // Fetch the lab name based on the labID from the laboratory table
                        $lab_query = "SELECT `labName` FROM `laboratory` WHERE `labID` = '" . $device['labID'] . "'";
                        $lab_result = mysqli_query($link, $lab_query);

                        // Check if the lab exists and get the lab name
                        $labName = "";
                        if (mysqli_num_rows($lab_result) > 0) {
                            $lab_data = mysqli_fetch_assoc($lab_result);
                            $labName = $lab_data['labName'];
                        } else {
                            $labName = "Lab not found"; // If labID doesn't exist in laboratory table
                        }

                        // Display the lab name instead of labID in the table
                        echo '<td>' . $labName . '</td>';


                        // View images link
                        echo '<td><a href="#" class="view-images-link" data-deviceid="' . $device['deviceID'] . '"><i class="fa fa-eye"></i> View Images</a></td>';

                        // QR code link
                        echo '<td><a href="#" class="link-dark qr-link" data-deviceid="' . $device['deviceID'] . '" data-name="' . urlencode($device['name']) . '" data-doacquisition="' . urlencode($device['doAcquisition']) . '" data-status="' . urlencode($device['status']) . '" data-labid="' . urlencode($device['labID']) . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a></td>';

                        // Actions column with Edit and Delete icons
                        echo '<td>
                            <a href="view_hardwares.php?deviceID=' . $device['deviceID'] . '" class="view-btn"><i class="fa fa-eye"></i></a> | 
                            <a href="#" class="edit-btn" data-deviceid="' . $device['deviceID'] . '"><i class="fa fa-edit"></i></a> | 
                            <a href="#" class="delete-btn" data-deviceid="' . $device['deviceID'] . '"><i class="fa fa-trash"></i></a>
                        </td>';

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

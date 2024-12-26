<?php
session_start();
include "db_connection.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM `hardwares`";
if (isset($_SESSION["QR"]) && $_SESSION["QR"] !== '') {
    $sql .= " WHERE idno = '{$_SESSION["QR"]}'";
}
if ($search) {
    $sql .= " AND (deviceID LIKE '%$search%' OR name LIKE '%$search%' OR doAcquisition LIKE '%$search%' OR status LIKE '%$search%' OR labID LIKE '%$search%')";
}

$result = $link->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <!-- <link rel="stylesheet" href="assets/css/fac-modal.css"> -->
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

                // Set the QR code image source
                $('#qrCodeImage').attr('src', qrCodeUrl);

                // Show the modal
                $('#qrModal').modal('show');
            });

            $('.status-link').click(function(e) {
                e.preventDefault();
                var deviceID = $(this).data('deviceid');
                var currentStatus = $(this).data('status');
                var newStatus = (currentStatus === 'Working') ? 'Not Working' : 'Working';

                // Perform AJAX request to update status in database
                $.ajax({
                    type: "POST",
                    url: "update_status.php",
                    data: { deviceID: deviceID, status: newStatus },
                    success: function(response) {
                        // Reload the page after successful update
                        location.reload();
                    }
                });
            });
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
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
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
                    <h1>Hardwares</h1>
                </div>
                <div class="right">
                    <form>
                        <div class="search">
                            <input type="search" id="searchInput" placeholder="Search...">
                        </div>
                    </form>
                    <!-- <button id="myBtn" class="btn-download" style="border: none;">
                        <i class='bx bx-plus'></i>
                        <span class="text"> Hardware</span>
                    </button> -->
                </div>
            </div>

            
    <!-- Modal Container -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                <p class="mt-2 text-center">
                    <a href="#" id="printLink">Print QR Code</a> 
                </p>
            </div>
        </div>
    </div>
</div>

            <div id="searchResults"></div>

            <div class="table-data">
                <div class="order">
                    <table>
                        <thead>
                            <tr>
                                <th>Device Id</th>
                                <th>Name</th>
                                <th>Date of Acquisition</th>
                                <th>Status</th>
                                <th>LabId</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            display($result);

                            function display($result)
                            {
                                if ($result && $result->num_rows > 0) {
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
                                        echo '<td>' . (isset($row['labID']) ? $row['labID'] : '') . '</td>';
                                        echo '<td>';
                                        if ($row['status'] === 'Working') {
                                            echo '<a href="hchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a>';
                                        } else {
                                            echo '<a href="hchange.php?id=' . $row['deviceID'] . '" class="link-primary"><i class="fa fa-toggle-off fs-5"></i></a>';
                                        }
                                        echo '&nbsp;&nbsp;';
                                        echo '<a href="#" class="link-dark qr-link" data-deviceid="' . $row['deviceID'] . '" data-name="' . urlencode($row['name']) . '" data-doacquisition="' . urlencode($row['doAcquisition']) . '" data-status="' . urlencode($row['status']) . '" data-labid="' . urlencode($row['labID']) . '"><i class="fa fa-qrcode" aria-hidden="true"></i></a></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No results found.</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- add hardware modal form -->
    <!-- <div id="myModal" class="modal">
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
            </div> -->

            <!-- for adding -->
<?php
// if (isset($_POST['add']) && $_POST['deviceID'] != "") {
//     $deviceID = mysqli_real_escape_string($link, $_POST['deviceID']);
//     $name = mysqli_real_escape_string($link, $_POST['name']);
//     $doAcquisition = mysqli_real_escape_string($link, $_POST['doAcquisition']);
//     $status = mysqli_real_escape_string($link, $_POST['status']);
//     $idno = mysqli_real_escape_string($link, $_POST['idno']);
//     $labID = mysqli_real_escape_string($link, $_POST['labID']);

//     $lab_query = "SELECT * FROM `laboratory` WHERE `labID` = '$labID'";
//     $lab_result = mysqli_query($link, $lab_query);
//     if (mysqli_num_rows($lab_result) == 0) {
//         echo "<script>Swal.fire({
//                   icon: 'error',
//                   title: 'Invalid lab ID. Please provide a valid lab ID.',
//                   confirmButtonColor: '#bf5b5b',
//                 });
//                 var modal = document.getElementById('myModal');
//                 modal.style.display = 'block';
//                 </script>";
//     } else {
//         $device_query = "SELECT * FROM `hardwares` WHERE `deviceID` = '$deviceID'";
//         $device_result = mysqli_query($link, $device_query);
//         if (mysqli_num_rows($device_result) > 0) {
//             echo "<script>Swal.fire({
//                 icon: 'error',
//                 title: 'The device already exists in the database.',
//                 confirmButtonColor: '#bf5b5b',
//               });
//               var modal = document.getElementById('myModal');
//               modal.style.display = 'block';
//               </script>";
//         } else {
//             $sql = "INSERT INTO `hardwares`(`deviceID`, `name`, `doAcquisition`, `status`, `idno`, `labID`, `dateAdded`) 
//                     VALUES ('$deviceID', '$name', '$doAcquisition', '$status', '$idno', '$labID', NOW())";
//             if ($link->query($sql) === TRUE) {
//                 echo "<script>Swal.fire({
//                     icon: 'success',
//                     title: 'Data successfully inserted!',
//                     confirmButtonColor: '#3c91e6',
//                   });
//                   </script>"; 
//             } else {
//                 echo "
//                 <script>Swal.fire({
//                     icon: 'error',
//                     title: 'Unable to insert data.',
//                     confirmButtonColor: '#bf5b5b',
//                   });
//                   var modal = document.getElementById('myModal');
//                   modal.style.display = 'block';
//                   </script>
//                 ";
//                 error_log("Error inserting data into database: " . $link->error);
//             }
//         }
//     }
//     $link->close();
// }
?>

<!-- JavaScript for the modal -->
<script>
    $(document).ready(function() {
        // Print QR code when print link is clicked
        $('#printLink').click(function(e) {
            e.preventDefault();
            // Trigger print dialog
            window.print();
        });

        // Other JavaScript functionality
        $('.qr-link').click(function(e) {
            e.preventDefault();
            var deviceID = $(this).data('deviceid');
            var name = decodeURIComponent($(this).data('name'));
            var doAcquisition = decodeURIComponent($(this).data('doacquisition'));
            var status = decodeURIComponent($(this).data('status'));
            var labID = decodeURIComponent($(this).data('labid'));

            var hardwareDetails = "Device ID: " + deviceID + "\nName: " + name + "\nDate of Acquisition: " + doAcquisition + "\nStatus: " + status + "\nLab ID: " + labID;

            var qrCodeUrl = 'qrcode.php?details=' + encodeURIComponent(hardwareDetails);

            // Set the QR code image source
            $('#qrCodeImage').attr('src', qrCodeUrl);

            // Show the modal
            $('#qrModal').modal('show');
        });

        $('.status-link').click(function(e) {
            e.preventDefault();
            var deviceID = $(this).data('deviceid');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'Working') ? 'Not Working' : 'Working';

            // Perform AJAX request to update status in database
            $.ajax({
                type: "POST",
                url: "update_status.php",
                data: { deviceID: deviceID, status: newStatus },
                success: function(response) {
                    // Reload the page after successful update
                    location.reload();
                }
            });
        });
    });
</script>

   <!-- JavaScript for search functionality -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchQuery = this.value.trim();

 
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search.php?search=' + searchQuery, true);
        xhr.onload = function() {
            if (xhr.status === 200) {

                var data = JSON.parse(xhr.responseText);

                var tbody = document.querySelector('.table-data .order table tbody');
                tbody.innerHTML = ''; 

                

                if (data.length > 0) {
                    data.forEach(function(row) {
                        var newRow = '<tr>';
                        newRow += '<td>' + row.deviceID + '</td>';
                        newRow += '<td>' + row.name + '</td>';
                        newRow += '<td>' + row.doAcquisition + '</td>';
                        if (row.status === 'Working') {
                            newRow += '<td><span class="status process">' + row.status + '</span></td>';
                        } else {
                            newRow += '<td><span class="status pending">' + row.status + '</span></td>';
                        }
                        newRow += '<td>' + row.labID + '</td>';
                        newRow += '<td>';
                        if (row.status === 'Working') {
                            newRow += '<a href="hchange.php?id=' + row.deviceID + '" class="link-primary"><i class="fa fa-toggle-on fs-5"></i></a>';
                        } else {
                            newRow += '<a href="hchange.php?id=' + row.deviceID + '" class="link-primary"><i class="fa fa-toggle-off fs-5"></i></a>';
                        }
                        newRow += '&nbsp;&nbsp;';
                        newRow += '<a href="#" class="link-dark qr-link" data-deviceid="' + row.deviceID + '" data-name="' + encodeURIComponent(row.name) + '" data-doacquisition="' + encodeURIComponent(row.doAcquisition) + '" data-status="' + encodeURIComponent(row.status) + '" data-labid="' + encodeURIComponent(row.labID) + '"><i class="fa fa-qrcode" aria-hidden="true"></i></a></td>';
                        newRow += '</tr>';
                        tbody.innerHTML += newRow;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">No results found.</td></tr>';
                }
            } else {
                console.error('Error fetching search results. Status:', xhr.status);
            }
        };
        xhr.send();
    });
</script>

    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        // close button
        span.onclick = function() {
            modal.style.display = 'none';
        }
    </script>

    <script src="assets/js/script.js"></script>
</body>
</html>

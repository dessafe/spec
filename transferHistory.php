<?php
session_start();
include "db_connection.php";

// Fetch transfer history data
$query = "SELECT ht.transferID, h.deviceID, h.name as deviceName, f1.fname as fromFaculty, f2.fname as toFaculty,
                l1.labname as fromLab, l2.labname as toLab, ht.transferDate, a.fname as adminName, 
                ht.remarks
          FROM hardware_transfer_history ht
          JOIN hardwares h ON ht.deviceID = h.deviceID
          JOIN laboratory l1 ON ht.fromLabID = l1.labID
          JOIN laboratory l2 ON ht.toLabID = l2.labID
          JOIN administ a ON ht.adminEmail = a.email
          JOIN faculty f1 ON ht.fromFaculty = f1.idno
          JOIN faculty f2 ON ht.toFaculty = f2.idno";

$result = mysqli_query($link, $query);

// Fetch all data into an array for later use in JavaScript
$transferData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transferData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/78ae652187.js" crossorigin="anonymous"></script>
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
            $('#example').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [[10, 25, 50, -1], ["10", "25", "50", "All"]],
                "language": {
                    "search": '<span style="font-size: 16px; color: #342E37;">Search:</span>',
                    "lengthMenu": '<span style="font-size: 16px; color: #342E37;">Show entries:</span> _MENU_'
                }
            });
        });

        // Function to trigger CSV download
        function downloadCSV() {
            let csvData = 'TransferID, DeviceID, DeviceName, From Lab, To Lab, Transfer Date, Admin, Remarks, From Faculty, To Faculty\n';
            
            // Use the PHP variable that contains all the data as a JSON object
            const transferData = <?php echo json_encode($transferData); ?>;
            
            // Loop through the data and add it to the CSV
            transferData.forEach(function(row) {
                csvData += row.transferID + ',' + row.deviceID + ',' + row.deviceName + ',' + row.fromLab + ',' + row.toLab + ',' + row.transferDate + ',' + row.adminName + ',' + row.remarks + ',' + row.fromFaculty + ',' + row.toFaculty + '\n';
            });
            
            let hiddenElement = document.createElement('a');
            hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvData);
            hiddenElement.target = '_blank';
            hiddenElement.download = 'hardware_transfer_history.csv';
            hiddenElement.click();
        }
    </script>
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
                <a href="ahardware.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Hardware</span>
                </a>
            </li>
            <li class="active">
                <a href="transferHistory.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Transfer History</span>
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
                <h1>Hardware Transfer History</h1>
            </div>
            <div class="right">
                <button class="btn btn-primary" onclick="downloadCSV()">Download as CSV</button>
            </div>
        </div>

        <!-- DataTable for Hardware Transfer History -->
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Transfer ID</th>
                    <th>Device ID</th>
                    <th>Device Name</th>
                    <th>From Lab</th>
                    <th>To Lab</th>
                    <th>Transfer Date</th>
                    <th>Admin</th>
                    <th>Remarks</th>
                    <th>From Faculty</th>
                    <th>To Faculty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display fetched records in table
                foreach ($transferData as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['transferID'] . "</td>";
                    echo "<td>" . $row['deviceID'] . "</td>";
                    echo "<td>" . $row['deviceName'] . "</td>";
                    echo "<td>" . $row['fromLab'] . "</td>";
                    echo "<td>" . $row['toLab'] . "</td>";
                    echo "<td>" . $row['transferDate'] . "</td>";
                    echo "<td>" . $row['adminName'] . "</td>";
                    echo "<td>" . $row['remarks'] . "</td>";
                    echo "<td>" . $row['fromFaculty'] . "</td>";
                    echo "<td>" . $row['toFaculty'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->

<script src="assets/js/script.js"></script>
</body>
</html>

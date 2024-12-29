<?php
session_start();
include "db_connection.php";

// Check if the "Download as CSV" button was clicked
if (isset($_POST['download_csv'])) {
    // Query to fetch the hardware data
    $query = "
        SELECT h.deviceID, h.name, h.brand, h.sponsorName, h.category, h.serialNo, 
            h.doAcquisition, h.status, CONCAT(f.fname, ' ', f.lname) AS facultyName, 
            l.labName
        FROM hardwares h
        LEFT JOIN faculty f ON h.idno = f.idno
        LEFT JOIN laboratory l ON h.labId = l.labId
    ";
    $result = mysqli_query($link, $query);

    // Open a file for writing the CSV
    $filename = "hardwares_data.csv";
    $output = fopen('php://output', 'w');

    // Set the headers for the CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Add the header row
    $header = ['Device ID', 'Name', 'Brand', 'Sponsor Name', 'Category', 'Serial No', 'Acquisition Date', 'Status', 'Faculty', 'Laboratory'];
    fputcsv($output, $header);

    // Fetch the data and write to the CSV
    while ($row = mysqli_fetch_assoc($result)) {
        // Adjust the status if necessary
        $status = $row['status'] == "Need Repair/Cleaning" ? "Repair or Cleaning" : $row['status'];

        // Prepare the row data
        $data = [
            $row['deviceID'], 
            $row['name'], 
            $row['brand'], 
            $row['sponsorName'], 
            $row['category'], 
            $row['serialNo'], 
            $row['doAcquisition'], 
            $status, 
            $row['facultyName'], 
            $row['labName']
        ];

        // Write the row to the CSV
        fputcsv($output, $data);
    }

    // Close the file
    fclose($output);
    exit();
}

// db_connection.php must include the database connection setup
$query = "
    SELECT h.deviceID, h.name, h.brand, h.sponsorName, h.category, h.serialNo, 
        h.doAcquisition, h.status, CONCAT(f.fname, ' ', f.lname) AS facultyName, 
        l.labName
    FROM hardwares h
    LEFT JOIN faculty f ON h.idno = f.idno
    LEFT JOIN laboratory l ON h.labId = l.labId
";
$result = mysqli_query($link, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/78ae652187.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <link rel="stylesheet" href="assets/css/hview.css">
    <title>SpecSnap</title>
    <link rel="icon" href="assets/img/logo1.png">
    <style>
        #sidebar ul {
            padding-left: 0;
        }
        .table-container {
            width: 100%;            /* Make the container fill available width */
            overflow: hidden;       /* Hide horizontal overflow */
        }

        #example {
            width: 100% !important; /* Ensure the table uses 100% width */
            table-layout: fixed;    /* Table columns will have equal width */
        }

        .dataTables_wrapper {
            overflow: hidden !important;  /* Ensure no extra scroll bars are added by the DataTable wrapper */
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
                "autoWidth": false,  /* Disable auto width adjustment */
                "responsive": true,  /* Ensure table adjusts to screen size */
                "scrollX": false,    /* Disable horizontal scrolling */
                "lengthMenu": [[10, 25, 50, -1], ["10", "25", "50", "All"]],
                "language": {
                    "search": '<span style="font-size: 16px; color: #342E37;">Search:</span>',
                    "lengthMenu": '<span style="font-size: 16px; color: #342E37;">Show entries:</span> _MENU_'
                }
            });
        });
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
            <li class="active">
                <a href="ahardware.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Hardware</span>
                </a>
            </li>
            <li>
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
                    <h1>Hardwares</h1>
                </div>
                <div class="right">
                    <form method="post" action="ahardware.php">
                        <button type="submit" name="download_csv" class="btn btn-primary">Download as CSV</button>
                    </form>
                </div>
            </div>


            <div class="table-container">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Sponsor Name</th>
                            <th>Category</th>
                            <th>Serial No</th>
                            <th>Acquisition Date</th>
                            <th>Status</th>
                            <th>Faculty</th>
                            <th>Laboratory</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Check if status is "Need Repair/Cleaning" and change the text
                            $status = $row['status'] == "Need Repair/Cleaning" ? "Repair or Cleaning" : $row['status'];

                            echo "<tr>";
                            echo "<td>" . $row['deviceID'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['brand'] . "</td>";
                            echo "<td>" . $row['sponsorName'] . "</td>";
                            echo "<td>" . $row['category'] . "</td>";
                            echo "<td>" . $row['serialNo'] . "</td>";
                            echo "<td>" . $row['doAcquisition'] . "</td>";
                            echo "<td>" . $status . "</td>";
                            echo "<td>" . $row['facultyName'] . "</td>";
                            echo "<td>" . $row['labName'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
</body>
</html>

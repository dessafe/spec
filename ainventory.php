<?php
session_start();
include "db_connection.php";

// Query to get count of devices based on category, status, and the brands
$query = "
    SELECT 
        category, 
        GROUP_CONCAT(DISTINCT brand ORDER BY brand ASC) AS brands,  -- Concatenate brands separated by commas
        COUNT(*) AS totalCount, 
        SUM(CASE WHEN status = 'Need Repair/Cleaning' THEN 1 ELSE 0 END) AS forRepair,
        SUM(CASE WHEN status = 'Working' THEN 1 ELSE 0 END) AS working,
        SUM(CASE WHEN status = 'Not Working' THEN 1 ELSE 0 END) AS notWorking,
        SUM(CASE WHEN status = 'For Disposal' THEN 1 ELSE 0 END) AS forDisposal
    FROM 
        hardwares
    GROUP BY 
        category;
";

$result = mysqli_query($link, $query);
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
    <style>
        #sidebar ul {
            padding-left: 0;
        }
        a {
            text-decoration: none;
        }

        .department-info {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .department-info h2 {
            margin-bottom: 15px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table td {
            background-color: #ffffff;
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
        /* Table container adjustments */
.table-container {
    width: 100%;
    overflow: hidden;
    margin-top: 20px;
}

/* DataTable styling */
#inventoryTable {
    width: 100% !important;
    border-collapse: collapse;
    border-spacing: 0;
}

/* Table header and column adjustments */
#inventoryTable th, #inventoryTable td {
    padding: 12px 15px;  /* Add padding for better readability */
    border: 1px solid #ddd; /* Light borders for separation */
    text-align: center;
}

#inventoryTable th {
    background-color: #007bff; /* Blue background for header */
    color: white;
    font-size: 16px;
    font-weight: bold;
}

#inventoryTable td {
    background-color: #fff; /* White background for cells */
    font-size: 14px;
}

#inventoryTable tr:nth-child(even) {
    background-color: #f8f9fa; /* Light grey for even rows */
}

#inventoryTable tr:hover {
    background-color: #f1f1f1; /* Hover effect for rows */
}

/* Adjust DataTable pagination and search bar styles */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 4px;
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #0056b3;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 4px;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ddd;
}

.dataTables_wrapper .dataTables_length select {
    border-radius: 4px;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ddd;
}
    </style>
    <script>
        $(document).ready(function() {
    $('#inventoryTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "scrollX": false,
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
            <!-- New Hardware link -->
            <li>
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
            <li class="active">
                <a href="ainventory.php">
                    <i class='bx bx-wrench'></i>
                    <span class="text">Inventory</span>
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
                    <h1>Inventory</h1>
                </div>
                <div class="right">
                    <button onclick="downloadCSV()">Download CSV</button>
                </div>
            </div>

            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Brands</th> <!-- New column for brands -->
                        <th>Total Devices</th>
                        <th>For Repair/Cleaning</th>
                        <th>Working</th>
                        <th>Not Working</th>
                        <th>For Disposal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display data from the query result
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . $row['category'] . "</td>
                                <td>" . $row['brands'] . "</td>  <!-- Display brands in the new column -->
                                <td>" . $row['totalCount'] . "</td>
                                <td>" . $row['forRepair'] . "</td>
                                <td>" . $row['working'] . "</td>
                                <td>" . $row['notWorking'] . "</td>
                                <td>" . $row['forDisposal'] . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Script for CSV Download -->
    <script>
        function downloadCSV() {
            var table = document.getElementById('inventoryTable');
            var rows = table.rows;
            var csvContent = "";

            // Loop through the rows and columns to create CSV data
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].cells;
                var rowContent = [];
                for (var j = 0; j < cells.length; j++) {
                    rowContent.push(cells[j].innerText);
                }
                csvContent += rowContent.join(",") + "\n";
            }

            // Create a temporary link to download the file
            var link = document.createElement("a");
            link.setAttribute("href", "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent));
            link.setAttribute("download", "inventory_report.csv");
            link.click();
        }
    </script>
</body>
</html>
<?php
session_start();
include "db_connection.php";

// Fetch hardware analytics from the database
$QR = $_SESSION['QR'];

// Fetch the lab name for the user
$labName = '';
$sqlLab = "SELECT labname FROM `laboratory` WHERE idno = '$QR' LIMIT 1";
$resultLab = $link->query($sqlLab);

if ($resultLab && $resultLab->num_rows > 0) {
    $labRow = $resultLab->fetch_assoc();
    $labName = $labRow['labname'];
}

$sql = "SELECT 
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR') AS totalHardware,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Working') AS totalWorking,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Not Working') AS totalNotWorking,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'For Disposal') AS totalDisposal,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Need Repair/Cleaning') AS totalRepair"; // Updated status

$result = $link->query($sql);
$row = $result->fetch_assoc();
$totalHardware = $row['totalHardware'];
$totalWorking = $row['totalWorking'];
$totalNotWorking = $row['totalNotWorking'];
$totalDisposal = $row['totalDisposal'];
$totalRepair = $row['totalRepair'];

echo "<script>console.log('Debug Values: Total: $totalHardware, Working: $totalWorking, Not Working: $totalNotWorking, Disposal: $totalDisposal, Repair/Cleaning: $totalRepair');</script>";
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
    <title>SpecSnap</title>
    <link rel="icon" href="assets/img/logo1.png">
    <style>
        #sidebar ul { padding-left: 0; }
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        #barchart {
            width: 100%;
            height: 400px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .box-info {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .box-info li {
            flex: 1 1 18%;
            background: #f4f4f4;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .box-info li i {
            font-size: 30px;
            margin-bottom: 10px;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load Google Charts library
            google.charts.load('current', { packages: ['corechart', 'bar'] });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                console.log('Drawing bar chart...'); // Debugging

                // Prepare data for the chart
                var data = google.visualization.arrayToDataTable([
                    ['Status', 'Count', { role: 'style' }],  // Adding role for colors
                    ['Working', <?php echo $totalWorking; ?>, '#4caf50'],   // Green
                    ['Not Working', <?php echo $totalNotWorking; ?>, '#f44336'], // Red
                    ['Disposal', <?php echo $totalDisposal; ?>, '#ffc107'],   // Yellow
                    ['Need Repair/Cleaning', <?php echo $totalRepair; ?>, '#2196f3']    // Blue
                ]);

                // Chart options for Bar Chart
                var options = {
                    title: 'Hardware Status Distribution',
                    chartArea: { width: '60%' },  // Setting chart area width
                    hAxis: {
                        title: 'Count',
                        minValue: 0,
                        gridlines: { count: 5 }, // Adjusting the gridlines
                        textStyle: { color: '#333' }  // Text color for clarity
                    },
                    vAxis: {
                        title: 'Status',
                        textStyle: { color: '#333' }  // Text color for clarity
                    },
                    seriesType: 'bars',  // Ensures it's a bar chart
                    series: {
                        0: { color: '#4caf50' },  // Working -> Green
                        1: { color: '#f44336' },  // Not Working -> Red
                        2: { color: '#ffc107' },  // Disposal -> Yellow
                        3: { color: '#2196f3' }   // Need Repair -> Blue
                    },
                    colors: ['#4caf50', '#f44336', '#ffc107', '#2196f3'], // Ensure the colors match with the series
                    legend: { position: 'top' },  // Adjust legend position
                    bars: 'vertical' // Ensures bars are vertical
                };

                // Draw the chart in the div with id 'barchart'
                var chart = new google.visualization.BarChart(document.getElementById('barchart'));
                chart.draw(data, options);
            }
        });

    </script>
</head>
<body>
<section id="sidebar">
        <a href="#" class="brand"><i class='bx bx-qr'></i><span class="text">SpecSnap</span></a>
        <ul class="side-menu top">
            <li class="active"><a href="#"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="hview.php"><i class='bx bxs-book-content'></i><span class="text">Hardware</span></a></li>
            <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Inventory</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
        </ul>
    </section>

    <section id="content">
        <nav><i class='bx bx-menu'></i><a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a></nav>
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard - <?php echo htmlspecialchars($labName, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <a href="add_hardware.php" class="btn-download" style="border: none;"><i class='bx bx-plus'></i><span class="text">Add Hardware</span></a>
            </div>

            <ul class="box-info">
                <li><i class='bx bxs-bar-chart-alt-2'></i><span class="text"><h3><?php echo $totalHardware; ?></h3><p>Total Hardware</p></span></li>
                <li><i class='bx bxs-check-circle'></i><span class="text"><h3><?php echo $totalWorking; ?></h3><p>Working</p></span></li>
                <li><i class='bx bxs-x-circle'></i><span class="text"><h3><?php echo $totalNotWorking; ?></h3><p>Not Working</p></span></li>
                <li><i class='bx bxs-trash'></i><span class="text"><h3><?php echo $totalDisposal; ?></h3><p>For Disposal</p></span></li>
                <li><i class='bx bxs-wrench'></i><span class="text"><h3><?php echo $totalRepair; ?></h3><p>Need Repair/Cleaning</p></span></li>
            </ul>

            <div class="chart-container">
                <div id="barchart" class="card shadow-sm"></div>
            </div>
        </main>
    </section>

    <script src="assets/js/script.js"></script>
</body>
</html>

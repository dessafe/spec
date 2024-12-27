<?php
    session_start();
    include "db_connection.php";

    $QR = $_SESSION['QR'];
    $sql = "SELECT 
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR') AS totalHardware,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Working') AS totalWorking,
    (SELECT COUNT(*) FROM `hardwares` WHERE idno = '$QR' AND status = 'Not Working') AS totalNotWorking";

    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $totalHardware = $row['totalHardware'];
    $totalWorking = $row['totalWorking'];
    $totalNotWorking = $row['totalNotWorking'];
?>

<!-- FACULTY DASHBOARD -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <link rel="stylesheet" href="assets/css/fac-modal.css">
    <title>SpecSnap</title>
    <link rel="icon" href="assets/img/logo1.png">
    <style>
        #sidebar ul {
            padding-left: 0;
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-qr'></i>
            <span class="text">SpecSnap</span>
        </a>
        <ul class="side-menu top" style="">
            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
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
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                </div>
                <a href="add_hardware.php" class="btn-download" style="border: none;">
                    <i class='bx bx-plus'></i>
                    <span class="text">Add Hardware</span>
                </a>
            </div>

            <ul class="box-info" style="padding-left: 0;">
                <li>
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    <span class="text">
                        <h3><?php echo $totalHardware; ?></h3>
                        <p>Total Hardware</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-check-circle'></i>
                    <span class="text">
                        <h3><?php echo $totalWorking; ?></h3>
                        <p>Working</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-x-circle'></i>
                    <span class="text">
                        <h3><?php echo $totalNotWorking; ?></h3>
                        <p>Not working</p>
                    </span>
                </li>
            </ul>

            <?php
            $display = "SELECT * FROM `hardwares` WHERE idno = '$QR' ORDER BY `hardwares`.`doAcquisition` DESC";
            $result2 = $link->query($display);
            $row2 = $result2->fetch_assoc();
            display($result2);

            function display($result2) {
                if ($result2 && $result2->num_rows > 0) {
                    echo '
                    <div class="table-data">
                        <div class="order">
                            <div class="head">
                                <h3>Recently Added</h3>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Device Id</th>
                                        <th>Name</th>
                                        <th>Date of Acquisition</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>';

                    // Fetch and display each row
                    while ($row2 = $result2->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . (isset($row2['deviceID']) ? $row2['deviceID'] : '') . '</td>';
                        echo '<td>' . (isset($row2['name']) ? $row2['name'] : '') . '</td>';
                        echo '<td>' . (isset($row2['doAcquisition']) ? $row2['doAcquisition'] : '') . '</td>';
                        if ($row2['status'] == "Working") {
                            echo '<td><span class="status process">' . (isset($row2['status']) ? $row2['status'] : '') . '</span></td>';
                        } else {
                            echo '<td><span class="status pending">' . (isset($row2['status']) ? $row2['status'] : '') . '</span></td>';
                        }
                        echo '</tr>';
                    }

                    echo '
                                </tbody>
                            </table>
                        </div>
                    </div>';
                } else {
                    echo "No results found.";
                }
            }
            ?>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="assets/js/script.js"></script>
</body>
</html>

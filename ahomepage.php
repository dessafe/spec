<?php
    session_start();
    include "db_connection.php";
    

    // Fetch total faculties count
    $facultyCountQuery = "SELECT COUNT(*) as totalFaculties FROM faculty";
    $facultyCountResult = mysqli_query($link, $facultyCountQuery);
    $facultyCount = mysqli_fetch_assoc($facultyCountResult)['totalFaculties'];

    // Fetch total laboratories count
    $laboratoryCountQuery = "SELECT COUNT(*) as totalLaboratories FROM laboratory";
    $laboratoryCountResult = mysqli_query($link, $laboratoryCountQuery);
    $laboratoryCount = mysqli_fetch_assoc($laboratoryCountResult)['totalLaboratories'];

    // Fetch total hardwares count
    $hardwareCountQuery = "SELECT COUNT(*) as totalHardwares FROM hardwares";
    $hardwareCountResult = mysqli_query($link, $hardwareCountQuery);
    $hardwareCount = mysqli_fetch_assoc($hardwareCountResult)['totalHardwares'];

    // Query to get department (depLocation) with the total number of laboratories and status breakdown (active and maintenance)
    $departmentQuery = "
        SELECT 
            depLocation AS department,
            COUNT(labID) AS total_laboratories,
            SUM(CASE WHEN labStatus = 'Active' THEN 1 ELSE 0 END) AS active_labs,
            SUM(CASE WHEN labStatus = 'Maintenance' THEN 1 ELSE 0 END) AS maintenance_labs
        FROM laboratory
        GROUP BY depLocation
    ";
    $departmentResult = mysqli_query($link, $departmentQuery);
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
    </style>
</head>
<body style="background-color: #eee">

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-qr'></i>
            <span class="text">SpecSnap</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
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
                    <h1>Dashboard</h1>
                </div>
                <div class="right">
                    <a href="add_faculty.php" class="btn btn-primary">Add Faculty</a>
                    <a href="add_laboratory.php" class="btn btn-primary">Add Laboratory</a>
                    <a href="transfer.php" class="btn btn-primary">Transfer</a>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?php echo $facultyCount; ?></h3>
                        <p>Total faculties</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-building-house'></i>
                    <span class="text">
                        <h3><?php echo $laboratoryCount; ?></h3>
                        <p>Total laboratories</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-wrench'></i>
                    <span class="text">
                        <h3><?php echo $hardwareCount; ?></h3>
                        <p>Total hardware</p>
                    </span>
                </li>
            </ul>

            <!-- Departments Info -->
            <div class="department-info">
                <h2>Departments Overview</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Department Location</th>
                            <th>Total Labs</th>
                            <th>Active Labs</th>
                            <th>Maintenance Labs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($departmentResult)) : ?>
                            <tr>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['total_laboratories']; ?></td>
                                <td><?php echo $row['active_labs']; ?></td>
                                <td><?php echo $row['maintenance_labs']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        // close button
        span.onclick = function() {
            modal.style.display = "none";
        }

        // JavaScript for opening and closing the create laboratory modal
        var modal2 = document.getElementById("myModal2");
        var btn2 = document.getElementById("myBtn2");
        var span2 = document.getElementsByClassName("close")[1]; // Note: Use index 1 for the close button of the second modal

        btn2.onclick = function() {
            modal2.style.display = "block";
        }

        // Close button for the create laboratory modal
        span2.onclick = function() {
            modal2.style.display = "none";
        }
    </script>

    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
session_start();
include "db_connection.php";

$faculty_idno = $_SESSION["QR"]; 
// Query to get count of devices based on category, status, and brands, grouped by labId and category
$sqlInventory = "
    SELECT 
        h.labId, 
        l.labname, 
        h.category, 
        GROUP_CONCAT(DISTINCT h.brand ORDER BY h.brand ASC) AS brands, 
        COUNT(*) AS totalCount, 
        SUM(CASE WHEN h.status = 'Need Repair/Cleaning' THEN 1 ELSE 0 END) AS forRepair,
        SUM(CASE WHEN h.status = 'Working' THEN 1 ELSE 0 END) AS working,
        SUM(CASE WHEN h.status = 'Not Working' THEN 1 ELSE 0 END) AS notWorking,
        SUM(CASE WHEN h.status = 'For Disposal' THEN 1 ELSE 0 END) AS forDisposal
    FROM 
        hardwares h
    LEFT JOIN 
        laboratory l ON h.labId = l.labId
    WHERE 
        l.idno = '$faculty_idno'  -- Filter by faculty id
    GROUP BY 
        h.labId, h.category
";

$resultInventory = $link->query($sqlInventory);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/fac-adm-style.css">
    <title>Inventory</title>
    <link rel="icon" href="assets/img/logo1.png">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .inventory-table {
            margin: 20px auto;
            width: 90%;
            border-collapse: collapse;
        }
        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .inventory-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .inventory-container {
            margin: 20px auto;
            padding: 20px;
            max-width: 90%;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bx-qr'></i><span class="text">SpecSnap</span></a>
        <ul class="side-menu top">
            <li><a href="faculty.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="hview.php"><i class='bx bxs-book-content'></i><span class="text">Hardware</span></a></li>
            <li class="active"><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Inventory</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Faculty | <?php echo $_SESSION["user"]; ?></a>
        </nav>
        <main>
            <div class="inventory-container">
                <h1 class="text-center">Hardware Inventory</h1>
                <?php
                if ($resultInventory && $resultInventory->num_rows > 0) {
                    $currentLabId = null;
                    while ($row = $resultInventory->fetch_assoc()) {
                        // Start a new table if the lab changes
                        if ($row['labId'] != $currentLabId) {
                            if ($currentLabId !== null) {
                                echo "</tbody></table><br>";  // Close the previous table
                            }
                            $currentLabId = $row['labId'];
                            echo "<table class='inventory-table'>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Brands</th>
                                        <th>Total Devices</th>
                                        <th>Working</th>
                                        <th>Not Working</th>
                                        <th>For Disposal</th>
                                        <th>Need Repair/Cleaning</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        }

                        // Display the data for the current category within the lab
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['brands'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . (int)$row['totalCount'] . "</td>";
                        echo "<td>" . (int)$row['working'] . "</td>";
                        echo "<td>" . (int)$row['notWorking'] . "</td>";
                        echo "<td>" . (int)$row['forDisposal'] . "</td>";
                        echo "<td>" . (int)$row['forRepair'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p>No hardware inventory available for the labs you manage.</p>";
                }
                ?>
            </div>
        </main>
    </section>

    <script src="assets/js/script.js"></script>
</body>
</html>

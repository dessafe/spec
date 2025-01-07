<?php
session_start();
include "db_connection.php";

// Fetch the hardware inventory details
$sqlInventory = "
    SELECT 
        name AS hardwareName,
        brand,
        category,
        COUNT(CASE WHEN status = 'Working' THEN 1 END) AS totalWorking,
        COUNT(CASE WHEN status = 'Not Working' THEN 1 END) AS totalNotWorking,
        COUNT(CASE WHEN status = 'For Disposal' THEN 1 END) AS totalDisposal,
        COUNT(CASE WHEN status = 'Need Repair/Cleaning' THEN 1 END) AS totalRepair
    FROM hardwares
    GROUP BY name, brand, category
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
            <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
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
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Hardware</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Working</th>
                            <th>Not Working</th>
                            <th>For Disposal</th>
                            <th>Need Repair/Cleaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultInventory && $resultInventory->num_rows > 0) {
                            while ($row = $resultInventory->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['hardwareName'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['brand'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . (int)$row['totalWorking'] . "</td>";
                                echo "<td>" . (int)$row['totalNotWorking'] . "</td>";
                                echo "<td>" . (int)$row['totalDisposal'] . "</td>";
                                echo "<td>" . (int)$row['totalRepair'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No hardware inventory available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </section>

    <script src="assets/js/script.js"></script>
</body>
</html>

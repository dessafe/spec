<?php
// Include database connection
include "db_connection.php";

// Check if deviceID is provided
if (isset($_GET['deviceID'])) {
    $deviceID = $_GET['deviceID'];

    // Fetch hardware details from the hardwares table
    $hardware_query = "SELECT * FROM `hardwares` WHERE `deviceID` = '$deviceID'";
    $hardware_result = mysqli_query($link, $hardware_query);
    
    if (mysqli_num_rows($hardware_result) > 0) {
        $hardware = mysqli_fetch_assoc($hardware_result);
        
        // Fetch images associated with this hardware from the hardware_images table
        $image_query = "SELECT * FROM `hardware_images` WHERE `deviceID` = '$deviceID'";
        $image_result = mysqli_query($link, $image_query);
        ?>
        
        <div class="hardware-details">
            <h2>Hardware Details</h2>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($hardware['name']); ?></td>
                </tr>
                <tr>
                    <th>Brand</th>
                    <td><?php echo htmlspecialchars($hardware['brand']); ?></td>
                </tr>
                <tr>
                    <th>Sponsor Name</th>
                    <td><?php echo htmlspecialchars($hardware['sponsorName']); ?></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?php echo htmlspecialchars($hardware['category']); ?></td>
                </tr>
                <tr>
                    <th>Serial No</th>
                    <td><?php echo htmlspecialchars($hardware['serialNo']); ?></td>
                </tr>
                <tr>
                    <th>Date of Acquisition</th>
                    <td><?php echo htmlspecialchars($hardware['doAcquisition']); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($hardware['status']); ?></td>
                </tr>
                <tr>
                    <th>Lab ID</th>
                    <td><?php echo htmlspecialchars($hardware['labID']); ?></td>
                </tr>
            </table>

            <h3>Images</h3>
            <div class="hardware-images">
                <?php
                if (mysqli_num_rows($image_result) > 0) {
                    while ($image = mysqli_fetch_assoc($image_result)) {
                        echo '<img src="' . $image['imagePath'] . '" alt="Hardware Image" class="hardware-image" style="width: 200px; margin: 10px;">';
                    }
                } else {
                    echo "No images available for this hardware.";
                }
                ?>
            </div>
        </div>

        <?php
    } else {
        echo "Hardware not found.";
    }
} else {
    echo "Invalid device ID.";
}
?>

<!-- Add some simple styling -->
<style>
    .hardware-details {
        margin: 20px;
        font-family: Arial, sans-serif;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    .hardware-images img {
        display: inline-block;
    }
</style>

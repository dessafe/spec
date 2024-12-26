<?php
// Retrieve hardware details from the query string
$hardwareDetails = isset($_GET['details']) ? urldecode($_GET['details']) : '';

// Include QR code library
require_once 'C:\xampp\htdocs\SpecSnap\phpqrcode\qrlib.php'; 

// Generate QR code with custom size
QRcode::png($hardwareDetails, false, QR_ECLEVEL_L, 10); // Adjust the size (10 in this example)
?>

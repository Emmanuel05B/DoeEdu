<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

use Dompdf\Dompdf;

require_once BASE_PATH . '/vendor/autoload.php';

// Initialize Dompdf
$dompdf = new Dompdf();


$html = "<img src='images/doe.png'>";

$dompdf->loadHtml($html);

// Set Paper size and Orientation
$dompdf->setPaper('A4', 'landscape');

// Render PDF
$dompdf->render();

// Stream the generated PDF to the browser
$dompdf->stream(); // Change to true to force download
?>

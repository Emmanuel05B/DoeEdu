<?php
use Dompdf\Dompdf;
require_once '../vendor/autoload.php';

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

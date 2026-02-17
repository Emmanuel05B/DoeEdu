<?php
require_once __DIR__ . '/../../common/config.php';

if (!isset($_GET['file'])) {
    exit('Invalid file');
}

$file = basename($_GET['file']); // security
$path = DOCUMENTS_PATH . '/' . $file;

if (!file_exists($path)) {
    exit('File not found');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;

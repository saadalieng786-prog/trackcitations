<?php
// public_html/download.php

$basePath = __DIR__ . '/sfdc_datasync/attachments/';
$file = $_GET['file'] ?? '';

$file = str_replace(['..', './', '\\'], '', $file); // prevent path traversal
$fullPath = realpath($basePath . $file);

if (!$fullPath || strpos($fullPath, realpath($basePath)) !== 0 || !file_exists($fullPath)) {
    http_response_code(404);
    exit('File not found');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));
readfile($fullPath);
exit;

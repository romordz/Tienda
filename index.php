<?php
// Serve static files directly without PHP processing
$requested_file = $_SERVER['REQUEST_URI'] ?? '/';
$requested_file = parse_url($requested_file, PHP_URL_PATH);
$static_extensions = ['js', 'css', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];

$file_extension = strtolower(pathinfo($requested_file, PATHINFO_EXTENSION));

if (in_array($file_extension, $static_extensions)) {
    $file_path = __DIR__ . $requested_file;
    if (file_exists($file_path) && is_file($file_path)) {
        // Set appropriate content type
        $mime_types = [
            'js' => 'application/javascript',
            'css' => 'text/css',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon'
        ];
        
        header('Content-Type: ' . ($mime_types[$file_extension] ?? 'application/octet-stream'));
        readfile($file_path);
        exit;
    }
}

require_once __DIR__ . '/php/config.php';
require __DIR__ . '/pantallas/Principal.php';
?>
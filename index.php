<?php
// Get the requested path
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Extract file extension
$file_extension = strtolower(pathinfo($request_path, PATHINFO_EXTENSION));

// List of static file extensions
$static_extensions = ['js', 'css', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];

// If requesting a static file, try to serve it
if (!empty($file_extension) && in_array($file_extension, $static_extensions)) {
    $file_path = __DIR__ . $request_path;
    
    // Check if file exists
    if (file_exists($file_path) && is_file($file_path) && is_readable($file_path)) {
        // Set appropriate content type
        $mime_types = [
            'js' => 'application/javascript; charset=utf-8',
            'css' => 'text/css; charset=utf-8',
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
        header('Cache-Control: public, max-age=31536000');
        readfile($file_path);
        exit;
    }
}

// Otherwise, serve the main application
require_once __DIR__ . '/php/config.php';
require __DIR__ . '/pantallas/Principal.php';
?>
<?php
require __DIR__ . '/php/config.php';

if ($_SERVER['SCRIPT_NAME'] === '/index.php' && empty($_SERVER['PATH_INFO'])) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    header("Location: " . $protocol . '://' . $host . '/');
    exit;
}

require __DIR__ . '/pantallas/Principal.php';
?>
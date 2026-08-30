<?php
function appBasePath(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    
    // Handle index.php case
    if ($script === '/index.php' || $script === '/') {
        return '';
    }
    
    // Remove pantallas or php path segments from the end
    $base = preg_replace('#/(?:pantallas|php)(?:/.*)?$#', '', $script);

    return $base === '' ? '' : $base;
}

function urlFor(string $path): string
{
    $path = ltrim($path, '/');
    $base = appBasePath();

    return $base === '' ? '/' . $path : $base . '/' . $path;
}
?>

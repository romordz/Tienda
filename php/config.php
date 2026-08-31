<?php
if (!function_exists('appBasePath')) {
    function appBasePath(): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/';
        if ($script === '/index.php' || $script === '/') {
            return '';
        }
        $base = preg_replace('#/(?:pantallas|php)(?:/.*)?$#', '', $script);
        return $base === '' ? '' : $base;
    }
}

if (!function_exists('urlFor')) {
    function urlFor(string $path): string
    {
        $path = ltrim($path, '/');
        $base = appBasePath();

        return $base === '' ? '/' . $path : $base . '/' . $path;
    }
}
?>
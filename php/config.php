<?php
function appBasePath(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
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

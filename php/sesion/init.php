<?php
session_start();

if (isset($_SESSION['user_id'])) {
    return; 
}

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page === 'Login.php' || $current_page === 'process_login.php') {
    return;
}

header("Location: /pantallas/Login.php");
exit();
?>
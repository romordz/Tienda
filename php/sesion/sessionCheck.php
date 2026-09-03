<?php
require __DIR__ . '/../sesion/init.php'; 
echo isset($_SESSION['user_id']) ? 'true' : 'false';
?>

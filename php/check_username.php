<?php
require 'conexion.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $checkUserSql = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :username";
    $checkUserStmt = $pdo->prepare($checkUserSql);
    $checkUserStmt->bindParam(':username', $username);
    $checkUserStmt->execute();

    if ($checkUserStmt->fetchColumn() > 0) {
        echo "El nombre de usuario ya está en uso. Elige otro.";
    } else {
        echo "available";
    }
}
?>

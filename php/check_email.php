<?php
require 'conexion.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $checkEmailSql = "SELECT COUNT(*) FROM usuarios WHERE correo = :email";
    $checkEmailStmt = $pdo->prepare($checkEmailSql);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->execute();

    if ($checkEmailStmt->fetchColumn() > 0) {
        echo "Este correo electrónico ya está registrado.";
    } else {
        echo "available"; 
    }
}
?>

<?php
include 'php/conexion.php';
session_start();

if (empty($_SESSION['carrito'])) {
    header('Location: carrito.php');
    exit();
}

$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

$page_title = "Pago";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SCart.css"> 
    <title>Pagar</title>
</head>
<body>
    <?php require 'componentes/Header/Header.php'; ?>

    <div class="payment-section">
        <h2>Total a Pagar: $<?php echo number_format($total, 2); ?></h2>
        <p>Gracias por tu compra, <?php echo $_SESSION['username']; ?>! 🎉</p>
        <p>Tu pedido será procesado.</p> <br>
        <a href="Principal.php" class="btn-add">Volver a Inicio</a>
    </div>

    <?php require 'componentes/Footer/Footer.php'; ?>
</body>
</html>

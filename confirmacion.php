<?php
session_start();

$page_title = "Gracias por tu compra!";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SConfirmacion.css">
    <title>Confirmación de Compra</title>
</head>
<body>
     <?php require 'componentes/Header/Header.php'; ?>

    <div class="confirmation-section">
        <h2>Tu compra ha sido registrada correctamente. 🎉</h2>
        <p>Gracias por confiar en nosotros. Tu compra será procesada y te enviaremos una notificación cuando esté lista.</p>
        <br>
        <a href="Productos.php" class="btn-back">Volver a la tienda</a>
    </div>

    <!-- <footer>
        <p>&copy; 2024 Tu Tienda. Todos los derechos reservados.</p>
    </footer> -->
</body>
</html>

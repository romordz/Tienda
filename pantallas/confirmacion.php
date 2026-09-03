<?php
require __DIR__ . '/../php/sesion/init.php'; 
require __DIR__ . '/../php/config.php';

$page_title = "Gracias por tu compra!";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SConfirmacion.css') ?>">
    <title>Confirmación de Compra</title>
</head>
<body>
     <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <div class="confirmation-section">
        <h2>Tu compra ha sido registrada correctamente. 🎉</h2>
        <p>Gracias por confiar en nosotros. Tu compra será procesada y te enviaremos una notificación cuando esté lista.</p>
        <br>
        <a href="<?= urlFor('pantallas/Productos.php') ?>" class="btn-back">Volver a la tienda</a>
    </div>

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>
</body>
</html>

<?php
session_start();

$page_title = "";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/Scategorias.css">
    <title>Categorías</title>
</head>

<body>
    <?php require 'componentes/Header/Header.php'; ?>

    <section class="category-list">
        <h2>Explora nuestras categorías</h2>
        <div id="categories-container" class="categories"></div>
    </section>


    <footer>
        <p>© 2024 Tu Tienda. Todos los derechos reservados.</p>
    </footer>
    <script src="js/getcategorias.js"></script>
    <script src="js/sessionCheck.js"></script>
</body>

</html>
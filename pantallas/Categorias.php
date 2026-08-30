<?php
session_start();
require __DIR__ . '/../php/config.php';

$page_title = "Categorias";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SCategorias.css') ?>">
    <title>Categorías</title>
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <section class="category-list">
        <h2>Explora nuestras categorías</h2>
        <div id="categories-container" class="categories"></div>
    </section>


    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>
    
    <script src="<?= urlFor('js/getCategorias.js') ?>"></script>
    <script src="<?= urlFor('js/sessionCheck.js') ?>"></script>
</body>

</html>
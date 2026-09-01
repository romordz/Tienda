<?php
session_start();
require_once __DIR__ . '/../php/config.php';

$page_title = "Bienvenido a nuestra tienda";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('componentes/ProductoCard/ProductoCard.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <main>
        <section class="product-list">
            <button id="mostrar-vendedores-button" onclick="obtenerVendedores()">Mostrar Vendedores</button>
            <div id="vendedores-container">
                <!-- Aquí se mostrarán los vendedores -->
            </div>
            <h2 id="search-heading" style="display: none;">Resultados de la busqueda</h2>
            <div id="search-results" class="search-results">
                <!-- Los resultados de búsqueda se cargarán aquí -->
            </div>
            <h2>productos Destacados</h2>
            <div class="products_destacados">
                <!-- Los productos destacados se cargarán aquí -->
            </div>
            <h2>productos Recientes</h2>
            <div class="products_recientes">
                <!-- Los productos recientes se cargarán aquí -->
            </div>

        </section>
    </main>
    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>

    <script src="<?= urlFor('js/sessionCheck.js') ?>"></script>
    <script src="<?= urlFor('js/productosObtener.js') ?>"></script>
    <script src="<?= urlFor('js/buscarProducto.js') ?>"></script>
    <script src="<?= urlFor('js/obtenerVendedores.js') ?>"></script>
</body>
</html>
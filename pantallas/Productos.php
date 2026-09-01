<?php
session_start();
require __DIR__ . '/../php/config.php';
include __DIR__ . '/../php/productos/get_productos.php';
require_once __DIR__ . '/../componentes/ProductCard/ProductCard.php';

$page_title = "Productos";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SProductos.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('componentes/ProductCard/ProductCard.css') ?>">
    <title>productos</title>
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <?php if (!empty($mensaje_confirmacion)): ?>
        <div class="confirmacion">
            <p><?php echo $mensaje_confirmacion; ?></p>
        </div>
    <?php endif; ?>

    <section class="product-list">
    <?php if (!empty($productos)): ?>
        <?php
        $categoriaActual = '';
        foreach ($productos as $producto):
            if ($producto['categoria_nombre'] != $categoriaActual):
                if ($categoriaActual != ''): ?>
                    </div>
                <?php endif; ?>
                <h2 data-categoria-id="<?php echo htmlspecialchars($producto['categoria_id']); ?>">
                    <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
                </h2>
                <div class="products">
                    <?php $categoriaActual = $producto['categoria_nombre']; ?>
                <?php endif; ?>

                <?php renderProductoCard($producto, 'thumb'); ?>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos disponibles en este momento.</p>
    <?php endif; ?>
</section>

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const categoriaId = params.get('categoria_id');
            if (categoriaId) {
                const categoriaElement = document.querySelector(`h2[data-categoria-id="${categoriaId}"]`);
                if (categoriaElement) {
                    categoriaElement.scrollIntoView({ behavior: "smooth" });
                }
            }
        });
    </script>
    <script src="/js/sessionCheck.js"></script>
</body>

</html>
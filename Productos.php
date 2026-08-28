<?php
session_start();
include 'php/get_productos.php';

$page_title = "Productos";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Sproductos.css">
    <title>productos</title>
</head>

<body>
    <?php require 'componentes/Header/Header.php'; ?>

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

                <div class="product-item" onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                    <a href="javascript:void(0);"
                        >
                        <?php
                        $imagenes = json_decode($producto['imagenes_json'], true);
                        if (!empty($imagenes) && isset($imagenes[0])): ?>
                            <img src="data:image/jpeg;base64,<?php echo $imagenes[0]; ?>"
                                alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <?php else: ?>
                            <img src="Recursos/default.jpg" alt="Imagen no disponible">
                        <?php endif; ?>
                    </a>

                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                    <?php if ($producto['para_cotizar'] == 1): ?>
                        <p class="price">Cotización disponible</p>
                    <?php else: ?>
                        <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos disponibles en este momento.</p>
    <?php endif; ?>
</section>

    <footer>
        <p>© 2024 Tu Tienda. Todos los derechos reservados.</p>
    </footer>

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
    <script src="js/sessionCheck.js"></script>
</body>

</html>
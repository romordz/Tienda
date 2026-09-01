<?php
session_start();
require __DIR__ . '/../php/config.php';
include __DIR__ . '/../php/productos/get_producto_detalle.php';
require_once __DIR__ . '/../componentes/ProductCard/ProductCard.php';

$producto_id = $_GET['id'] ?? null;
$detalles_producto = obtener_producto_detalle($producto_id);

if (!$detalles_producto) {
    echo "<script>alert('Error: No se pudo encontrar el producto.'); window.location.href = 'Productos.php';</script>";
    exit();
}

$producto = $detalles_producto['producto'];
$productos_vendedor = $detalles_producto['productos_vendedor'];
$productos_categoria = $detalles_producto['productos_categoria'];
$productos_likes = $detalles_producto['productos_likes'];

$listas = [];
if (isset($_SESSION['user_id'])) {
    include __DIR__ . '/../php/listas/obtener_listas_producto.php';
}
$imagenesArray = !empty($producto['imagenes_json']) ? json_decode($producto['imagenes_json'], true) : [];

$page_title = "Detalle del Producto";

if (!function_exists('urlFor')) {
    require_once __DIR__ . '/../../php/config.php';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SProductoDetalle.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('componentes/ProductCard/ProductCard.css') ?>">
    <title>Detalle del Producto</title>
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <section class="product-detail">
        <div class="seller-info">
            <h3>Vendedor</h3>
            <div class="seller-profile" onclick="irAPerfil(<?php echo $producto['vendedor_id']; ?>)"
                style="cursor: pointer;">
                <?php
                $avatarVendedor = $producto['vendedor_avatar'] ?? '';
                $avatarSrc = !empty($avatarVendedor)
                    ? (str_starts_with($avatarVendedor, 'http')
                        ? htmlspecialchars($avatarVendedor)
                        : 'data:image/jpeg;base64,' . htmlspecialchars($avatarVendedor))
                    : urlFor('/Recursos/default.jpg');
                ?>
                <img src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($producto['vendedor_nombre']) ?>"
                    class="seller-avatar">
                <p><?php echo htmlspecialchars($producto['vendedor_nombre']); ?></p>
            </div>
        </div>

        <div class="product-image-gallery">
            <?php if (!empty($imagenesArray)): ?>
                <div class="carousel-container">
                    <div class="carousel-inner">
                        <?php foreach ($imagenesArray as $index => $imagenBase64): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo str_starts_with($imagenBase64, 'http') ? htmlspecialchars($imagenBase64) : 'data:image/jpeg;base64,' . htmlspecialchars($imagenBase64); ?>"
                                    alt="Imagen del Producto">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn prev" onclick="moveCarousel(-1)">&#10094;</button>
                    <button class="carousel-btn next" onclick="moveCarousel(1)">&#10095;</button>
                </div>
            <?php else: ?>
                <p>No hay imágenes disponibles para este producto.</p>
            <?php endif; ?>
        </div>
        <div class="product-info">
            <h2><?php echo isset($producto['nombre']) ? htmlspecialchars($producto['nombre']) : 'Producto no encontrado'; ?>
            </h2>
            <p class="description">
                <?php echo isset($producto['descripcion']) ? htmlspecialchars($producto['descripcion']) : 'Sin descripción disponible'; ?>
            </p>

            <?php if (isset($producto['para_cotizar']) && $producto['para_cotizar'] == 1): ?>
                <p class="price">Este producto está disponible solo para cotización.</p>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'cliente'): ?>
                    <form method="post" action="<?= urlFor('pantallas/mensaje_vendedor.php') ?>">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <button type="submit" class="btn-message">Solicitar Cotización</button>
                    </form>
                <?php else: ?>
                    <p>Solo los clientes pueden solicitar una cotización.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="quantity-available">Cantidad disponible: <?php echo $producto['cantidad_disponible'] ?? 'N/A'; ?>
                </p>
                <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
                <form method="post" action="<?= urlFor('php/pago/agregar_carrito.php') ?>">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <button type="submit" name="add_to_cart" class="btn-add">Añadir al Carrito</button>
                </form>
            <?php endif; ?>

            <button id="btn-agregar-lista" class="btn-add-list">Agregar a lista</button>

            <!-- Popup para seleccionar la lista -->
            <div id="popup-seleccionar-lista" class="popup" style="display: none;">
                <div class="popup-content">
                    <span class="close" id="close-popup">&times;</span>
                    <h2>Selecciona una lista</h2>
                    <form id="form-agregar-lista" method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <label for="lista">Lista:</label>
                        <select id="lista" name="lista_id" required>
                            <?php foreach ($listas as $lista): ?>
                                <option value="<?php echo $lista['id']; ?>">
                                    <?php echo htmlspecialchars($lista['nombre_lista']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" id="btn-agregar" class="btn-agregar">Agregar a Lista</button>
                    </form>
                </div>
            </div>

            <!--VIDEO -->
            <?php if (!empty($producto['video'])): ?>
                <?php
                $videoURL = json_decode($producto['video'], true);

                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $videoURL, $matches);
                $videoID = $matches[1];
                ?>
                <div class="product-video">
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoID); ?>" frameborder="0"
                        allowfullscreen></iframe>
                </div>
            <?php endif; ?>
            <div class="likes-dislikes">
                <p>👍 Likes: <?php echo isset($producto['likes']) ? $producto['likes'] : 0; ?></p>
                <p>👎 Dislikes: <?php echo isset($producto['dislikes']) ? $producto['dislikes'] : 0; ?></p>
            </div>
            <div class="rating-buttons">
                <button class="like-button" onclick="rateProduct(<?php echo $producto['id']; ?>, 'like')">👍
                    Like</button>
                <button class="dislike-button" onclick="rateProduct(<?php echo $producto['id']; ?>, 'dislike')">👎
                    Dislike</button>
            </div>
        </div>
    </section>

    <!--Comentar -->
    <section class="product-comments">
        <h3>comentarios</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="<?= urlFor('php/comentarios/agregar_comentario.php') ?>">
                <textarea name="comentario" rows="4" cols="50" placeholder="Escribe tu comentario aquí..."
                    required></textarea>
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <button type="submit" class="btn-add-comment">Agregar comentario</button>
            </form>
        <?php else: ?>
            <p>Inicia sesión para dejar un comentario.</p>
        <?php endif; ?>

        <!--comentarios -->
        <div class="comments-list">
            <?php
            $comentarios = obtener_comentarios_producto($producto['id']);
            if (!empty($comentarios)) {
                foreach ($comentarios as $comentario) {
                    echo "<div class='comment'>";

                    $avatarComentario = $comentario['avatar'] ?? '';
                    $avatarSrc = !empty($avatarComentario)
                        ? (str_starts_with($avatarComentario, 'http')
                            ? htmlspecialchars($avatarComentario)
                            : 'data:image/jpeg;base64,' . htmlspecialchars($avatarComentario))
                        : urlFor('/Recursos/default.jpg');

                    $nombreUsuario = htmlspecialchars($comentario['nombre_usuario']);

                    echo "<img src='" . $avatarSrc . "' alt='" . $nombreUsuario . "' class='comment-avatar' onclick='irAPerfil(" . $comentario['usuario_id'] . ")' style='cursor: pointer;'>";
                    echo "<strong>" . $nombreUsuario . "</strong> <p> <br>" . htmlspecialchars($comentario['comentario']) . "</p>";
                    echo "<p class='comment-date'>" . $comentario['fecha'] . "</p>";

                    if ($_SESSION['role'] == 'administrador') {
                        echo "<form method='post' action='" . urlFor('php/comentarios/eliminar_comentario.php') . "'>";
                        echo "<input type='hidden' name='comentario_id' value='" . $comentario['id'] . "'>";
                        echo "<button type='submit' class='btn-delete'>Eliminar</button>";
                        echo "</form>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No hay comentarios aún. Sé el primero en comentar.</p>";
            }
            ?>
        </div>
    </section>

    <!-- productos relacionados -->
    <section class="related-products">
        <h3>productos del mismo vendedor</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_vendedor)): ?>
                <?php foreach ($productos_vendedor as $producto): ?>
                    <?php renderProductoCard($producto, 'grid'); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos relacionados del mismo vendedor.</p>
            <?php endif; ?>
        </div>

        <h3>productos de la misma categoría</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_categoria)): ?>
                <?php foreach ($productos_categoria as $producto): ?>
                    <?php renderProductoCard($producto, 'grid'); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos relacionados en esta categoría.</p>
            <?php endif; ?>
        </div>

        <h3>productos con más likes</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_likes)): ?>
                <?php foreach ($productos_likes as $producto): ?>
                    <?php renderProductoCard($producto, 'grid'); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos con muchos likes disponibles.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>

    <script src="<?= urlFor('js/producto_detalle.js') ?>"></script>
    <script src="<?= urlFor('js/obtenerVendedores.js') ?>"></script>
    <script src="<?= urlFor('js/sessionCheck.js') ?>"></script>
</body>

</html>
<?php
session_start();
include 'php/get_producto_detalle.php';

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
    include 'php/obtener_listas_producto.php';
}
$imagenesArray = !empty($producto['imagenes_json']) ? json_decode($producto['imagenes_json'], true) : [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SProductoDetalle.css">
    <title>Detalle del Producto</title>
</head>

<body>
    <header>
        <h1>Detalle del Producto</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
                <li><a href="php/cerrar_sesion.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <div class="profile-container">
            <div class="user-profile" onclick="toggleDropdown(event)">
                <img src="<?php echo isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? 'data:image/jpeg;base64,' . $_SESSION['avatar'] : 'Recursos/default.jpg'; ?>"
                    alt="Avatar" class="profile-avatar">
                <div class="profile-info">
                    <p class="profile-name">
                        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Nombre'; ?>
                    </p>
                    <p class="profile-role"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Rol'; ?></p>
                </div>
            </div>
            <div id="profile-dropdown" class="profile-dropdown">
                <a href="Perfil.php" onclick="return checkSession('Perfil.php')">Revisar perfil</a>
                <a href="mensajes.php" onclick="return checkSession('mensajes.php')">Mensajes</a>
                <a href="php/cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <section class="product-detail">
        <div class="seller-info">
            <h3>Vendedor</h3>
            <div class="seller-profile" onclick="irAPerfil(<?php echo $producto['vendedor_id']; ?>)"
                style="cursor: pointer;">
                <?php if (!empty($producto['vendedor_avatar'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['vendedor_avatar']); ?>"
                        alt="Avatar del Vendedor" class="seller-avatar">
                <?php else: ?>
                    <img src="Recursos/default.jpg" alt="Avatar por Defecto" class="seller-avatar">
                <?php endif; ?>
                <p><?php echo htmlspecialchars($producto['vendedor_nombre']); ?></p>
            </div>
        </div>

        <div class="product-image-gallery">
            <?php if (!empty($imagenesArray)): ?>
                <div class="carousel-container">
                    <div class="carousel-inner">
                        <?php foreach ($imagenesArray as $index => $imagenBase64): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imagenBase64); ?>"
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
                    <form method="post" action="mensaje_vendedor.php">
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
                <form method="post" action="php/agregar_carrito.php">
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
        <h3>Comentarios</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="php/agregar_comentario.php">
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
                    if (!empty($comentario['avatar'])) {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($comentario['avatar']) . "' alt='Avatar del Usuario' class='comment-avatar' onclick='irAPerfil(" . $comentario['usuario_id'] . ")' style='cursor: pointer;'>";
                    } else {
                        echo "<img src='Recursos/default.jpg' alt='Avatar por Defecto' class='comment-avatar' onclick='irAPerfil(" . $comentario['usuario_id'] . ")' style='cursor: pointer;'>";
                    }
                    echo "<strong>" . htmlspecialchars($comentario['nombre_usuario']) . "</strong> <p> <br>" . htmlspecialchars($comentario['comentario']) . "</p>";
                    echo "<p class='comment-date'>" . $comentario['fecha'] . "</p>";
                    if ($_SESSION['role'] == 'administrador') {
                        echo "<form method='post' action='php/eliminar_comentario.php'>";
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

    <!-- Productos relacionados -->
    <section class="related-products">
        <h3>Productos del mismo vendedor</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_vendedor)): ?>
                <?php foreach ($productos_vendedor as $producto): ?>
                    <a href="javascript:void(0);"
                        onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                        <?php
                        $imagenes = json_decode($producto['imagenes_json'], true);
                        $imagenPrincipal = $imagenes[0] ?? 'Recursos/default.jpg';
                        ?>
                        <div class="related-product-item">
                            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imagenPrincipal); ?>"
                                alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>">
                    </a>
                    <p><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    <p>$<?php echo number_format($producto['precio'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos relacionados del mismo vendedor.</p>
        <?php endif; ?>
        </div>

        <h3>Productos de la misma categoría</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_categoria)): ?>
                <?php foreach ($productos_categoria as $producto): ?>
                    <a href="javascript:void(0);"
                        onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                        <?php
                        $imagenes = json_decode($producto['imagenes_json'], true);
                        $imagenPrincipal = $imagenes[0] ?? 'Recursos/default.jpg';
                        ?>
                        <div class="related-product-item">
                            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imagenPrincipal); ?>"
                                alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>">
                    </a>
                    <p><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    <p>$<?php echo number_format($producto['precio'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos relacionados en esta categoría.</p>
        <?php endif; ?>
        </div>

        <h3>Productos con más likes</h3>
        <div class="related-products-container">
            <?php if (!empty($productos_likes)): ?>
                <?php foreach ($productos_likes as $producto): ?>
                    <a href="javascript:void(0);"
                        onclick="checkSession('producto_detalle.php?id=<?php echo $producto['id']; ?>');">
                        <?php
                        $imagenes = json_decode($producto['imagenes_json'], true);
                        $imagenPrincipal = $imagenes[0] ?? 'Recursos/default.jpg';
                        ?>
                        <div class="related-product-item">
                            <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imagenPrincipal); ?>"
                                alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>">
                    </a>
                    <p><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    <p>$<?php echo number_format($producto['precio'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos con muchos likes disponibles.</p>
        <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>© 2024 Tu Tienda. Todos los derechos reservados.</p>
    </footer>
    <script src="js/producto_detalle.js"></script>
    <script src="js/obtenerVendedores.js"></script>
    <script src="js/sessionCheck.js"></script>
</body>

</html>
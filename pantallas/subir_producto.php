<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Producto</title>
    <link rel="stylesheet" href="../css/SPrincipal.css">
    <link rel="stylesheet" href="../css/SSubirProducto.css">
</head>

<body>
    <header>
        <h1>Subir Producto</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Productos.php">productos</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
                <li><a href="../php/sesion/cerrar_sesion.php">Cerrar Sesión</a></li>
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
                <a href="Mensajes.php" onclick="return checkSession('Mensajes.php')">mensajes</a>
                <a href="../php/sesion/cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main>
        <section class="upload-section">
            <div class="upload-card">
                <h2>Información del Producto</h2>
                <form action="../php/productos/process_subir_producto.php" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>

                    <label for="categoria">Categoría:</label>
                    <select id="categoria" name="categoria_id" required>
                        <option value="">Cargando categorías...</option>
                    </select>

                    <button id="btn-agregar-categoria" class="btn-agregar-categoria">Agregar Categoría</button>

                    <label for="imagenes">Imágenes (mínimo 3):</label>
                    <input type="file" id="imagenes" name="imagenes[]" accept="image/jpeg" multiple required onblur="validateImages()">
                    <span id="photo-error" class="error-message"></span>

                    <label for="video">Video (JSON):</label>
                    <input type="text" id="video" name="video" placeholder="URL del video (opcional)">

                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>

                    <label for="cantidad">Cantidad Disponible:</label>
                    <input type="number" id="cantidad" name="cantidad" required>

                    <label for="cotizacion">¿En cotización?</label>
                    <select id="cotizacion" name="para_cotizar">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>

                    <button type="submit" class="btn-submit">Subir Producto</button>
                </form>
            </div>
        </section>

        <!-- Popup para agregar categoría -->
        <div id="popup-agregar-categoria" class="popup">
            <div class="popup-content">
                <span class="close-popup" onclick="cerrarPopup()">&times;</span>
                <h3>Agregar Nueva Categoría</h3>
                <label for="nombre_categoria">Nombre:</label>
                <input type="text" id="nombre_categoria" required>
                <label for="descripcion_categoria">Descripción:</label>
                <textarea id="descripcion_categoria" required></textarea>
                <button id="btn-guardar-categoria">Guardar Categoría</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
    </footer>
    <script src="../js/getCategorias.js"></script>
    <script src="../js/agregarCategoria.js"></script>
    <script src="../js/sessionCheck.js"></script>
    <script src="../js/JSubirProducto.js"></script>
</body>

</html>
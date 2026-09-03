<?php
session_start();
require __DIR__ . '/../php/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Producto</title>
    <link rel="stylesheet" href="<?= urlFor('css/SPrincipal.css') ?>">
    <link rel="stylesheet" href="<?= urlFor('css/SSubirProducto.css') ?>">
</head>

<body>
    <?php require __DIR__ . '/../componentes/Header/Header.php'; ?>

    <main>
        <section class="upload-section">
            <div class="upload-card">
                <h2>Información del Producto</h2>
                <form action="<?= urlFor('php/productos/process_subir_producto.php') ?>" method="POST" enctype="multipart/form-data">
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
                    <input type="file" id="image" name="imagenes[]" accept="image/jpeg, image/png, image/webp" multiple required onblur="validateImage()">
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

    <?php require __DIR__ . '/../componentes/Footer/Footer.php'; ?>
    <script src="<?= urlFor('js/getCategorias.js') ?>"></script>
    <script src="<?= urlFor('js/agregarCategoria.js') ?>"></script>
    <script src="<?= urlFor('js/sessionCheck.js') ?>"></script>
    <script src="<?= urlFor('js/JSubirProducto.js') ?>"></script>
    <script src="<?= urlFor('js/JValidaciones.js') ?>"></script>
</body>
</html>
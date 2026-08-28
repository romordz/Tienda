<?php
session_start();
require __DIR__ . '/php/DB/conexion.php';

$producto_id = $_GET['id'] ?? null;

if ($producto_id) {
    $sql = "SELECT id, nombre, descripcion, precio, cantidad_disponible, para_cotizar, video, imagenes_json, categoria_id 
            FROM productos 
            WHERE id = :producto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}
$sqlcategorias = "SELECT id, nombre FROM categorias";
$stmtcategorias = $pdo->prepare($sqlcategorias);
$stmtcategorias->execute();
$categorias = $stmtcategorias->fetchAll(PDO::FETCH_ASSOC);

$imagenes = [];
if (!empty($producto['imagenes_json'])) {
    $imagenes = json_decode($producto['imagenes_json'], true);
    
}

$page_title = "Detalle del Producto";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/SPrincipal.css">
    <link rel="stylesheet" href="css/SSubirProducto.css">
</head>

<body>
    <?php require 'componentes/Header/Header.php'; ?>

    <?php
if (isset($_SESSION['mensaje'])) {
    echo "<div class='mensaje-exito'>" . $_SESSION['mensaje'] . "</div>";
    unset($_SESSION['mensaje']);
}
?>
    <main>
        <section class="upload-section">
            <div class="upload-card">
                <h2>Información del Producto</h2>
                <form action="php/productos/process_editar_producto.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
    <input type="hidden" name="imagenes_actuales" value='<?php echo json_encode($imagenes); ?>'>

    <label for="nombre">Nombre del Producto:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

    <label for="categoria">Categoría:</label>
    <select id="categoria" name="categoria_id" required>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id']; ?>" 
                <?php echo ($categoria['id'] == $producto['categoria_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($categoria['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" 
        value="<?php echo htmlspecialchars($producto['precio']); ?>" required>

    <label for="cantidad">Cantidad Disponible:</label>
    <input type="number" id="cantidad" name="cantidad" 
        value="<?php echo htmlspecialchars($producto['cantidad_disponible']); ?>" required>

    <label for="cotizacion">¿En cotización?</label>
    <select id="cotizacion" name="para_cotizar">
        <option value="0" <?php echo ($producto['para_cotizar'] == 0) ? 'selected' : ''; ?>>No</option>
        <option value="1" <?php echo ($producto['para_cotizar'] == 1) ? 'selected' : ''; ?>>Sí</option>
    </select>

    <label for="video">Video (URL):</label>
    <input type="text" id="video" name="video" value="<?php echo htmlspecialchars($producto['video']); ?>" placeholder="URL del video (opcional)">

    <label>Imágenes actuales:</label>
<div class="imagenes-actuales">
    <?php if (!empty($imagenes)): ?>
        <?php foreach ($imagenes as $index => $imagen): ?>
            <div class="imagen-container">
                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($imagen); ?>" alt="Imagen del producto" class="preview-imagen">
                
                <?php if ($index === 0 && count($imagenes) > 2): ?>
                    <!-- Solo mostrar el botón de eliminar si hay más de una imagen -->
                    <form action="php/productos/process_editar_producto.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                        <input type="hidden" name="imagen_a_eliminar" value="<?php echo $index; ?>">
                        <button type="submit" class="eliminar-imagen">Eliminar Imagen</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay imágenes disponibles.</p>
    <?php endif; ?>
</div>

    <label for="imagenes">Agregar Imágenes:</label>
<input type="file" id="imagenes" name="imagenes[]" accept="image/jpeg" multiple>

    <button type="submit" class="btn-submit">Editar Producto</button>
</form>

            </div>
        </section>
    </main>

    <?php require 'componentes/Footer/Footer.php'; ?>
    
    <script src="js/sessionCheck.js"></script>
    <script>
</script>
</body>
</html>
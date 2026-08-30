<?php
require 'php/productos/aprobar_producto.php';

if ($_SESSION['role'] !== 'administrador') {
    echo "Acceso denegado.";
    exit();
}

$page_title = "Estado de productos";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar productos</title>
    <link rel="stylesheet" href="css/Sprincipal.css">
    <link rel="stylesheet" href="css/SAprobar.css">
</head>

<body>
     <?php require 'componentes/Header/Header.php'; ?>
    <h1>productos Pendientes</h1>
    <table>
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>

            <td>
                <?php if (isset($producto['para_cotizar']) && $producto['para_cotizar'] == 1): ?>
                    <span>Cotizacion</span>
                <?php else: ?>
                    $<?php echo number_format($producto['precio'], 2); ?>
                <?php endif; ?>
            </td>

            <td>
                <?php if (!empty($producto['imagenes_json'])): ?>
                    <?php
                    $imagenesArray = json_decode($producto['imagenes_json'], true);
                    foreach ($imagenesArray as $imagen): ?>
                        <img src="data:image/jpeg;base64,<?php echo $imagen; ?>"
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                             style="width: 100px; height: auto; margin-right: 5px;">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No disponible</p>
                <?php endif; ?>
            </td>
            <td>
                <form method="post" action="php/productos/aprobar_producto.php">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <button type="submit" class="btn-approve">Aprobar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

    <script src="js/sessionCheck.js"></script>
</body>

</html>
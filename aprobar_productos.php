<?php
require 'php/aprobar_producto.php';

if ($_SESSION['role'] !== 'administrador') {
    echo "Acceso denegado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar Productos</title>
    <link rel="stylesheet" href="css/Sprincipal.css">
    <link rel="stylesheet" href="css/SAprobar.css">
</head>

<body>
    <header>
        <h1>Bienvenido a Nuestra Tienda</h1>
        <nav>
            <ul>
                <li><a href="Principal.php">Inicio</a></li>
                <li><a href="Productos.php">Productos</a></li>
                <li><a href="Categorias.php">Categorías</a></li>
                <li><a href="carrito.php" onclick="return checkSession('carrito.php');">Carrito</a></li>
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
    <h1>Productos Pendientes</h1>
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
                <form method="post" action="php/aprobar_producto.php">
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
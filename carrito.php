<?php
require __DIR__ . '/php/DB/conexion.php';
require __DIR__ . '/php/pago/actualizar_carrito.php';

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

$mensaje = "";
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'updated') {
        $mensaje = "¡La cantidad se ha actualizado correctamente! ✅";
    } elseif ($_GET['action'] === 'deleted') {
        $mensaje = "¡El producto se ha eliminado del carrito! 🗑️";
    }
}

$rol_usuario = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$page_title = "Tu carrito";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SCart.css">
    <link rel="stylesheet" href="css/SPrincipal.css">
    <title>Carrito de compras</title>
</head>

<body>
    <?php require 'componentes/Header/Header.php'; ?>

    <div class="cart-section">
        <?php if (!empty($mensaje))
            echo $mensaje; ?>
        <h2>productos en tu Carrito</h2>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($carrito)): ?>
                    <?php foreach ($carrito as $producto_id => $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td>
                                <form method="post" action="php/actualizar_carrito.php">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                    <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" min="1"
                                        class="quantity-input">
                                    <button type="submit" name="actualizar_cantidad" class="btn-add">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></td>
                            <td>
                                <form method="post" action="php/actualizar_carrito.php">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                    <button type="submit" name="eliminar_producto" class="btn-remove">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay productos en tu carrito.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="cart-total">
            <?php
            $total = 0;
            foreach ($carrito as $producto) {
                $total += $producto['precio'] * $producto['cantidad'];
            }
            ?>
            <h3>Total: $<?php echo number_format($total, 2); ?></h3>
            <button id="convertir-usd" class="btn-convert" onclick="convertirACurrency('<?php echo $total; ?>', 'USD')">Convertir a USD 💵</button>
            <p id="resultado-conversion" style="margin-top: 10px;"></p>

            <?php if ($rol_usuario === 'cliente'): ?>
                <button class="btn-checkout" onclick="verificarTarjeta()">Proceder al Pago</button>
            <?php else: ?>
                <p style="color: red;">Solo los usuarios con rol de cliente pueden realizar pagos.</p>
            <?php endif; ?>
        </div>
        <button id="agregar-tarjeta-btn" onclick="toggleFormularioTarjeta()">Agregar Tarjeta</button>

        <div id="formulario-tarjeta" style="display: none;">
            <h3>Agregar Tarjeta de Crédito</h3>
            <form method="post" action="php/agregar_tarjeta.php"
                onsubmit="return validateExpirationDate() && validateCVV()">
                <div>
                    <label for="numero_tarjeta">Número de tarjeta:</label>
                    <input type="text" name="numero_tarjeta" id="numero_tarjeta" maxlength="16" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div>
                    <label for="nombre_tarjeta">Nombre en la tarjeta:</label>
                    <input type="text" name="nombre_tarjeta" id="nombre_tarjeta" required>
                </div>
                <div>
                    <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                    <input type="month" name="fecha_vencimiento" id="fecha_vencimiento" required
                        onblur="validateExpirationDate()">
                    <span id="fecha-vencimiento-error" class="error-message"></span>
                </div>
                <div>
                    <label for="cvv">CVV:</label>
                    <input type="text" name="cvv" id="cvv" maxlength="3" required onblur="validateCVV()"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span id="cvv-error" class="error-message"></span>
                </div>
                <button type="submit">Agregar Tarjeta</button>
            </form>
        </div>

    </div>

    <?php require 'componentes/Footer/Footer.php'; ?>
    
    <script src="js/sessionCheck.js"></script>
    <script src="js/tarjeta.js"></script>
</body>

</html>
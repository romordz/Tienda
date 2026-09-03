<?php
require __DIR__ . '/../sesion/init.php'; 
include __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';

$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$rol_usuario = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$usuario_id || $rol_usuario !== 'cliente') {
    header('Location: ' . urlFor('pantallas/carrito.php?error=no_cliente'));
    exit;
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

if (empty($carrito)) {
    header('Location: ' . urlFor('pantallas/carrito.php'));
    exit;
}

$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

try {
    $pdo->beginTransaction();
    
    $sqlCompra = "INSERT INTO compras (usuario_id, total) VALUES (:usuario_id, :total)";
    $stmtCompra = $pdo->prepare($sqlCompra);
    $stmtCompra->bindParam(':usuario_id', $usuario_id);
    $stmtCompra->bindParam(':total', $total);
    $stmtCompra->execute();

    $compra_id = $pdo->lastInsertId();

    foreach ($carrito as $producto_id => $producto) {
        $sqlVerificarStock = "SELECT cantidad_disponible FROM productos WHERE id = :producto_id";
        $stmtVerificarStock = $pdo->prepare($sqlVerificarStock);
        $stmtVerificarStock->bindParam(':producto_id', $producto_id);
        $stmtVerificarStock->execute();
        $stockActual = $stmtVerificarStock->fetchColumn();

        if ($stockActual < $producto['cantidad']) {
            throw new Exception('No hay suficiente inventario para el producto ' . $producto['nombre']);
        }

        $sqlDetalle = "INSERT INTO detalles_compras (compra_id, producto_id, cantidad) VALUES (:compra_id, :producto_id, :cantidad)";
        $stmtDetalle = $pdo->prepare($sqlDetalle);
        $stmtDetalle->bindParam(':compra_id', $compra_id);
        $stmtDetalle->bindParam(':producto_id', $producto_id);
        $stmtDetalle->bindParam(':cantidad', $producto['cantidad']);
        $stmtDetalle->execute();

        $sqlActualizarStock = "UPDATE productos SET cantidad_disponible = cantidad_disponible - :cantidad WHERE id = :producto_id";
        $stmtActualizarStock = $pdo->prepare($sqlActualizarStock);
        $stmtActualizarStock->bindParam(':cantidad', $producto['cantidad']);
        $stmtActualizarStock->bindParam(':producto_id', $producto_id);
        $stmtActualizarStock->execute();
    }

    $pdo->commit();

    unset($_SESSION['carrito']);

    header('Location: ' . urlFor('pantallas/confirmacion.php'));
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error en la compra: ' . $e->getMessage());
}

<?php
session_start();
include '..php/DB/..php/DB/conexion.php';

if (!empty($_SESSION['carrito']) && isset($_SESSION['id'])) {
    try {
        $pdo->beginTransaction();

        $usuario_id = $_SESSION['id'];
        $total = 0;
        foreach ($_SESSION['carrito'] as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        $sqlCompra = "INSERT INTO compras (usuario_id, total) VALUES (?, ?)";
        $stmtCompra = $pdo->prepare($sqlCompra);
        $stmtCompra->execute([$usuario_id, $total]);

        $compra_id = $pdo->lastInsertId();

        foreach ($_SESSION['carrito'] as $producto_id => $producto) {
            $sqlDetalle = "INSERT INTO detalles_compras (compra_id, producto_id, cantidad) VALUES (?, ?, ?)";
            $stmtDetalle = $pdo->prepare($sqlDetalle);
            $stmtDetalle->execute([$compra_id, $producto_id, $producto['cantidad']]);
        }

        $pdo->commit();

        unset($_SESSION['carrito']);
        echo "¡Compra realizada con éxito!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al procesar la compra: " . $e->getMessage();
    }
} else {
    echo "No hay productos en el carrito o no has iniciado sesión.";
}
?>

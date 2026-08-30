<?php
session_start();

require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '..//php/productos/get_Productos.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$mensaje_confirmacion = "";

if (isset($_POST['add_to_cart'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = 1;

    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "<script>alert('Error: Producto no encontrado.'); window.location.href = '/Productos.php';</script>";
        exit();
    }

    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
        $mensaje_confirmacion = "¡La cantidad del producto se ha actualizado en el carrito! 🎉";
    } else {
        $_SESSION['carrito'][$producto_id] = [
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'imagenes' => $producto['imagenes']
        ];
        $mensaje_confirmacion = "¡El producto ha sido añadido al carrito con éxito! 🛒✨";
    }

    echo "<script>window.location.href = '/pantallas/producto_detalle.php?id=$producto_id';</script>";
}
?>

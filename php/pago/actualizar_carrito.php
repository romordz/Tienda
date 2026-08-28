<?php
session_start();

include '..php/DB/conexion.php';

if (isset($_POST['actualizar_cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad;
        $_SESSION['carrito'][$producto_id]['total'] = $_SESSION['carrito'][$producto_id]['precio'] * $cantidad;
    }
    header('Location: ../carrito.php?action=updated');
    exit();
}

if (isset($_POST['eliminar_producto'])) {
    $producto_id = $_POST['producto_id'];
    unset($_SESSION['carrito'][$producto_id]);

    header('Location: ../carrito.php?action=deleted');
    exit();
}

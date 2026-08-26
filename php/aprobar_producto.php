<?php 
session_start();
require 'conexion.php';

if ($_SESSION['role'] !== 'administrador') {
    echo "Acción no permitida.";
    exit();
}
//iba el asterisco
$sql = "SELECT id, nombre, estado, descripcion, precio, imagenes_json, para_cotizar FROM productos WHERE estado = 'pendiente'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $usuario_id = $_SESSION['user_id'];

    $sql = "UPDATE productos SET estado = 'aprobado', aprobado_por = :usuario_id WHERE id = :producto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Producto aprobado exitosamente.";
        header("Location: ../aprobar_Productos.php");
        exit();
    } else {
        echo "Error al aprobar el producto.";
    }
}
?>

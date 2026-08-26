<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario_id = $_POST['comentario_id'];

    $sql = "SELECT producto_id FROM comentarios WHERE id = :comentario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Error: No se pudo encontrar el producto.";
        exit();
    }

    $sql = "UPDATE comentarios SET estado = 'eliminado' WHERE id = :comentario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comentario_id', $comentario_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../producto_detalle.php?id=" . $producto['producto_id']);
        exit();
    } else {
        echo "Error al eliminar el comentario.";
    }
}
?>

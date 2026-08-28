<?php 
session_start();
include __DIR__ . '/../DB/conexion.php';

if (isset($_POST['comentario']) && isset($_SESSION['user_id'])) {
    $producto_id = $_POST['producto_id'];
    $usuario_id = $_SESSION['user_id'];
    $comentario = $_POST['comentario'];

    $sql = "INSERT INTO comentarios (producto_id, usuario_id, comentario) VALUES (:producto_id, :usuario_id, :comentario)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: ../producto_detalle.php?id=" . $producto_id);
        exit();
    } else {
        echo "Error: " . $pdo->errorInfo()[2];
    }
} else {
    echo "Debes iniciar sesión para comentar.";
}
?>

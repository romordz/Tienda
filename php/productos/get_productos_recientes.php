<?php 
session_start();
require __DIR__ . '/../DB/conexion.php';
$query = "SELECT id, nombre, descripcion, imagenes_json, precio, para_cotizar, likes FROM productos WHERE estado = 'aprobado' ORDER BY id DESC LIMIT 5";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $productos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($productos_recientes);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
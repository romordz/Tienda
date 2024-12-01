<?php 
session_start();
require 'conexion.php';
//iba el asterisco
$query = "SELECT id, nombre, descripcion, imagenes, imagenes_json, precio, para_cotizar, likes FROM productos WHERE estado = 'aprobado' ORDER BY id DESC LIMIT 5";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $productos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productos_recientes = array_map(function ($producto) {
        $producto['imagenes'] = base64_encode($producto['imagenes']);
        return $producto;
    }, $productos_recientes);

    header('Content-Type: application/json');
    echo json_encode($productos_recientes);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}

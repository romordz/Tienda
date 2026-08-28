<?php 
session_start();
require 'conexion.php';
$query = "SELECT id, nombre, descripcion, imagenes, imagenes_json, precio, para_cotizar, likes 
          FROM productos 
          WHERE estado = 'aprobado' 
          ORDER BY likes DESC 
          LIMIT 3";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $productos_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $productos_destacados = array_map(function ($producto) {
        $producto['imagenes'] = json_encode($producto['imagenes']);
        return $producto;
    }, $productos_destacados);

    header('Content-Type: application/json');
    echo json_encode($productos_destacados);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
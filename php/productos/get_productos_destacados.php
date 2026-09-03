<?php
require __DIR__ . '/../sesion/init.php'; 
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../../componentes/ProductCard/ProductCard.php';

$query = "SELECT id, nombre, descripcion, imagenes_json, precio, para_cotizar, likes 
          FROM productos 
          WHERE estado = 'aprobado' 
          ORDER BY likes DESC 
          LIMIT 3";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $productos_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: text/html; charset=utf-8');

    if (empty($productos_destacados)) {
        echo '<p>No hay productos destacados disponibles.</p>';
        exit();
    }

    foreach ($productos_destacados as $producto) {
        renderProductoCard($producto, 'grid');
    }
} catch (PDOException $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Error al cargar productos destacados.</p>';
}
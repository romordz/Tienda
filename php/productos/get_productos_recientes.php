<?php
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../../componentes/ProductCard/ProductCard.php';

$query = "SELECT id, nombre, descripcion, imagenes_json, precio, para_cotizar, likes 
          FROM productos 
          WHERE estado = 'aprobado' 
          ORDER BY id DESC 
          LIMIT 5";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $productos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: text/html; charset=utf-8');

    if (empty($productos_recientes)) {
        echo '<p>No hay productos recientes disponibles.</p>';
        exit();
    }

    foreach ($productos_recientes as $producto) {
        renderProductoCard($producto, 'grid');
    }
} catch (PDOException $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Error al cargar productos recientes.</p>';
}
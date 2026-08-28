<?php
require __DIR__ . '/../DB/conexion.php';

try {
    $sql = "
        SELECT p.*, c.nombre AS categoria_nombre 
        FROM productos p 
        JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.estado = 'aprobado'
        ORDER BY c.nombre, p.nombre
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

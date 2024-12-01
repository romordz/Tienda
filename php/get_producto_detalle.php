<?php
function obtener_producto_detalle($producto_id)
{
    include 'php/conexion.php';

    if (!$producto_id) {
        return null;
    }

    $stmt = $pdo->prepare("
        SELECT p.*, u.nombre_usuario AS vendedor_nombre, u.avatar AS vendedor_avatar, u.id AS vendedor_id
        FROM productos p 
        JOIN usuarios u ON p.vendedor_id = u.id 
        WHERE p.id = :id
    ");
    $stmt->bindParam(':id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT id, nombre, precio, imagenes_json 
                           FROM productos 
                           WHERE vendedor_id = :vendedor_id AND id != :producto_id 
                           AND estado = 'aprobado'
                           LIMIT 4");
    $stmt->bindParam(':vendedor_id', $producto['vendedor_id'], PDO::PARAM_INT);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $productos_vendedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, nombre, precio, imagenes_json 
                           FROM productos 
                           WHERE categoria_id = :categoria_id AND id != :producto_id 
                           AND estado = 'aprobado'
                           LIMIT 4");
    $stmt->bindParam(':categoria_id', $producto['categoria_id'], PDO::PARAM_INT);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $productos_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, nombre, precio, imagenes_json 
                           FROM productos 
                           WHERE estado = 'aprobado' 
                           ORDER BY likes DESC 
                           LIMIT 4");
    $stmt->execute();
    $productos_likes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'producto' => $producto,
        'productos_vendedor' => $productos_vendedor,
        'productos_categoria' => $productos_categoria,
        'productos_likes' => $productos_likes
    ];
}

function obtener_comentarios_producto($producto_id)
{
    include 'php/conexion.php';

    $sql = "SELECT c.id, c.comentario, u.nombre_usuario, u.avatar, c.fecha, c.usuario_id
            FROM Comentarios c 
            JOIN Usuarios u ON c.usuario_id = u.id 
            WHERE c.producto_id = :producto_id 
             AND c.estado = 'activo' 
            ORDER BY c.fecha DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>  
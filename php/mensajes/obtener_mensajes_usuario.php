<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '..php/DB/conexion.php';

$user_id = $_SESSION['user_id'] ?? null;

$sql = "
    SELECT m.id, m.producto_id, m.mensaje, m.fecha, p.nombre AS producto_nombre,
           r.nombre_usuario AS remitente_nombre, r.id AS usuario_id,
           c.comprador_id, c.vendedor_id, c.id AS conversacion_id  -- Aquí estamos seleccionando el conversacion_id
    FROM mensajes m
    INNER JOIN productos p ON m.producto_id = p.id
    INNER JOIN usuarios r ON m.usuario_id = r.id 
    INNER JOIN conversaciones c ON m.conversacion_id = c.id
    WHERE c.comprador_id = :user_id OR c.vendedor_id = :user_id
    ORDER BY m.producto_id, m.fecha ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$mensajesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensajesAgrupados = [];
foreach ($mensajesRaw as $mensaje) {
    $conversacionID = $mensaje['producto_id'] . '-' . $mensaje['conversacion_id'];
    $mensajesAgrupados[$conversacionID]['producto_nombre'] = $mensaje['producto_nombre'];
    $mensajesAgrupados[$conversacionID]['conversacion'][] = $mensaje;
}

return $mensajesAgrupados;
?>

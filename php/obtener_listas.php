<?php
include 'conexion.php';

$usuario_id = $profile_user_id;

$query = "
    SELECT l.id, l.nombre_lista, l.descripcion, l.privacidad, 
           (SELECT p.imagenes_json 
            FROM lista_productos lp 
            JOIN productos p ON lp.producto_id = p.id 
            WHERE lp.lista_id = l.id 
            LIMIT 1) AS imagen_preview
    FROM listas l
    WHERE l.usuario_id = :usuario_id
    AND (l.privacidad = 'pública' OR :is_owner = 1)
";
$stmt = $pdo->prepare($query);
$is_owner = ($usuario_id == $_SESSION['user_id']) ? 1 : 0;
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->bindParam(':is_owner', $is_owner, PDO::PARAM_INT);
$stmt->execute();

$listas = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $listas[] = $row;
}
?>

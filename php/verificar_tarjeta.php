<?php
session_start();
include 'conexion.php';

$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$usuario_id) {
    echo json_encode(['tiene_tarjeta' => false]);
    exit;
}

try {
    $sql = "SELECT id FROM tarjetas WHERE usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $tarjeta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tarjeta) {
        echo json_encode(['tiene_tarjeta' => true]);
    } else {
        echo json_encode(['tiene_tarjeta' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['tiene_tarjeta' => false, 'error' => $e->getMessage()]);
}
?>

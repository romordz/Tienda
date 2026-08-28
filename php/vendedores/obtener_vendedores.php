<?php
require __DIR__ . '/../DB/conexion.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, nombre_usuario, correo FROM usuarios WHERE rol = 'vendedor'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($vendedores) {
        echo json_encode(['success' => true, 'vendedores' => $vendedores]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No hay vendedores disponibles.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>

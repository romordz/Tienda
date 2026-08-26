<?php
session_start();
include 'conexion.php';

try {
    $sql = "SELECT id, nombre, descripcion FROM categorias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categorias);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $data['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $usuario_creador = $_SESSION['user_id'];

    if (empty($nombre) || empty($descripcion)) {
        echo json_encode(['success' => false, 'error' => 'Nombre y descripción son requeridos.']);
        exit();
    }

    try {
        $sql = "INSERT INTO Categorias (nombre, descripcion, usuario_creador) VALUES (:nombre, :descripcion, :usuario_creador)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':usuario_creador', $usuario_creador, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al agregar la categoría.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

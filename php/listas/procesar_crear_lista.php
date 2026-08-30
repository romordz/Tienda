<?php
session_start();
include __DIR__ . '/../DB/conexion.php';

if (!isset($_SESSION['user_id'])) {
    die("Usuario no autenticado.");
}

$usuario_id = $_SESSION['user_id'];
$nombre_lista = $_POST['nombre_lista'];
$descripcion = $_POST['descripcion'];
$privacidad = $_POST['privacidad'];

$query = "INSERT INTO listas (usuario_id, nombre_lista, descripcion, privacidad) VALUES (:usuario_id, :nombre_lista, :descripcion, :privacidad)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->bindParam(':nombre_lista', $nombre_lista, PDO::PARAM_STR);
$stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
$stmt->bindParam(':privacidad', $privacidad, PDO::PARAM_STR);

try {
    $stmt->execute();
    header("Location: /pantallas/Perfil.php");
} catch (PDOException $e) {
    echo "Error al crear la lista: " . $e->getMessage();
}
?>

<?php
require __DIR__ . '/../sesion/init.php';
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo 'error';
        exit();
    }
    header("Location: " . urlFor('pantallas/Perfil.php'));
    exit();
}

$lista_id = $_POST['lista_id'] ?? null;
$nombre = $_POST['nombre_lista'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$privacidad = $_POST['privacidad'] ?? 'publica';

if (empty($lista_id) || empty($nombre)) {
    echo 'error';
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_check = "SELECT id FROM listas WHERE id = :id AND usuario_id = :user_id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':id', $lista_id);
$stmt_check->bindParam(':user_id', $user_id);
$stmt_check->execute();

if ($stmt_check->rowCount() === 0) {
    echo 'error';
    exit();
}

$sql = "UPDATE listas SET nombre_lista = :nombre, descripcion = :descripcion, privacidad = :privacidad WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->bindParam(':privacidad', $privacidad);
$stmt->bindParam(':id', $lista_id);

try {
    if ($stmt->execute()) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo 'success';
        } else {
            header("Location: " . urlFor('pantallas/Perfil.php'));
        }
        exit();
    } else {
        echo 'error';
    }
} catch (PDOException $e) {
    error_log("Error en actualizar_lista.php: " . $e->getMessage());
    echo 'error';
}
?>
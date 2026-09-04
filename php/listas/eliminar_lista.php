<?php
require __DIR__ . '/../sesion/init.php';
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';

$lista_id = $_GET['id'] ?? $_POST['lista_id'] ?? null;

if (empty($lista_id)) {
    echo "Error: ID de lista no proporcionado.";
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_check = "SELECT id FROM listas WHERE id = :id AND usuario_id = :user_id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':id', $lista_id);
$stmt_check->bindParam(':user_id', $user_id);
$stmt_check->execute();

if ($stmt_check->rowCount() === 0) {
    echo "Error: No tienes permisos para borrar esta lista.";
    exit();
}

$sql = "DELETE FROM listas WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $lista_id);

try {
    if ($stmt->execute()) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo 'success';
        } else {
            header("Location: " . urlFor('pantallas/Perfil.php?delete=success'));
            exit();
        }
    } else {
        echo "Error al borrar la lista.";
    }
} catch (PDOException $e) {
    error_log("Error en eliminar_lista.php: " . $e->getMessage());
    echo "Error de base de datos.";
}
?>
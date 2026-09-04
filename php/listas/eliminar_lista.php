<?php
require __DIR__ . '/../sesion/init.php';
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';

$lista_id = $_GET['id'] ?? $_POST['lista_id'] ?? null;

if (empty($lista_id)) {
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

try {
    $sql_del_rel = "DELETE FROM lista_productos WHERE lista_id = :id";
    $stmt_rel = $pdo->prepare($sql_del_rel);
    $stmt_rel->bindParam(':id', $lista_id);
    $stmt_rel->execute();

    $sql = "DELETE FROM listas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $lista_id);
    $stmt->execute();

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo 'success';
    } else {
        header("Location: " . urlFor('pantallas/Perfil.php'));
    }
    exit();
} catch (PDOException $e) {
    error_log("Error en eliminar_lista.php: " . $e->getMessage());
    echo 'error';
}
?>
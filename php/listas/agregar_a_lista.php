<?php
require __DIR__ . '/../sesion/init.php'; 
include __DIR__ . '/../DB/conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para agregar productos a una lista.']);
    exit;
}

if (isset($_POST['producto_id']) && isset($_POST['lista_id'])) {
    $producto_id = $_POST['producto_id'];
    $lista_id = $_POST['lista_id'];
    $usuario_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT id FROM listas WHERE id = :lista_id AND usuario_id = :usuario_id");
        $stmt->execute(['lista_id' => $lista_id, 'usuario_id' => $usuario_id]);
        $lista = $stmt->fetch();

        if ($lista) {
            $stmt = $pdo->prepare("INSERT INTO lista_productos (lista_id, producto_id) VALUES (:lista_id, :producto_id)");
            $stmt->execute(['lista_id' => $lista_id, 'producto_id' => $producto_id]);

            echo json_encode(['success' => true, 'message' => 'Producto agregado a la lista correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para agregar productos a esta lista.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al agregar producto a la lista: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios para agregar el producto a la lista.']);
}
?>

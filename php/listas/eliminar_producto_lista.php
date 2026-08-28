<?php
require __DIR__ . '/../DB/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id']) && isset($_POST['lista_id'])) {
    $producto_id = $_POST['producto_id'];
    $lista_id = $_POST['lista_id'];

    try {
        $sql = "UPDATE lista_productos SET estado = 'eliminado' WHERE lista_id = :lista_id AND producto_id = :producto_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':lista_id', $lista_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'lista_id' => $lista_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'El producto no se encontró en la lista o ya ha sido eliminado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de producto o lista no proporcionado.']);
}
?>

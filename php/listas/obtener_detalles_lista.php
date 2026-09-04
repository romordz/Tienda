<?php
require __DIR__ . '/../sesion/init.php';
include __DIR__ . '/../DB/conexion.php';

if (!isset($_POST['lista_id'])) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID de la lista.']);
    exit;
}

$lista_id = $_POST['lista_id'];
$usuario_id = $_SESSION['user_id'];

try {
    error_log("Buscando lista con ID: $lista_id");
    $stmt = $pdo->prepare("SELECT id, usuario_id, nombre_lista, descripcion, privacidad FROM listas WHERE id = :lista_id");
    $stmt->execute(['lista_id' => $lista_id]);
    $lista = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lista) {
        echo json_encode(['success' => false, 'message' => 'Lista no encontrada.']);
        exit;
    }

    if ($lista['privacidad'] == 'privada' && $lista['usuario_id'] != $usuario_id) {
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para ver esta lista.']);
        exit;
    }


    $stmt = $pdo->prepare("SELECT p.id, p.nombre, p.descripcion, p.precio, p.imagenes_json 
                            FROM lista_productos lp
                            INNER JOIN productos p ON lp.producto_id = p.id
                            WHERE lp.lista_id = :lista_id
                            AND lp.estado = 'activo'");
    $stmt->execute(['lista_id' => $lista_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as &$producto) {
        $producto['imagenes_json'] = json_decode($producto['imagenes_json'], true);
    }

    echo json_encode([
        'success' => true,
        'lista' => $lista,
        'productos' => $productos
    ]);
} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener los detalles: ' . $e->getMessage()]);
}
?>
<?php
session_start();
include __DIR__ . '/../DB/conexion.php';

if (!isset($_POST['lista_id'])) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID de la lista.']);
    exit;
}

$lista_id = $_POST['lista_id'];
$usuario_id = $_SESSION['user_id'];

try {
    error_log("Buscando lista con ID: $lista_id");
    // iba el asterisco
    $stmt = $pdo->prepare("SELECT id, usuario_id, nombre_lista, descripcion, imagenes, privacidad FROM listas WHERE id = :lista_id");
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

    error_log("Detalles de la lista: " . print_r($lista, true));

    $stmt = $pdo->prepare("SELECT p.id, p.nombre, p.descripcion, p.imagenes, p.precio, p.imagenes_json 
                            FROM lista_productos lp
                            INNER JOIN productos p ON lp.producto_id = p.id
                            WHERE lp.lista_id = :lista_id
                            AND lp.estado = 'activo'");
    $stmt->execute(['lista_id' => $lista_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Cantidad de productos encontrados: " . count($productos));

    foreach ($productos as &$producto) {
        if ($producto['imagenes']) {
            $producto['imagenes'] = base64_encode($producto['imagenes']);
        }

        if ($producto['imagenes_json']) {
            $producto['imagenes_json'] = json_decode($producto['imagenes_json'], true);
            error_log("Imagenes JSON del producto ID " . $producto['id'] . ": " . print_r($producto['imagenes_json'], true));
        }
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

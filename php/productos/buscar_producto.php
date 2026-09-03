<?php
require __DIR__ . '/../DB/conexion.php';

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $sql = "SELECT id, nombre, descripcion, precio, imagenes_json FROM productos WHERE nombre LIKE :query OR descripcion LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            renderProductoCard($producto, 'grid');
        }
    } else {
        echo 'No se encontraron productos que coincidan con "' . htmlspecialchars($query) . '".';
    }
} else {
    echo 'No se recibió ningún término de búsqueda.';
}
?>

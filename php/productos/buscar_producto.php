<?php
require __DIR__ . '/../DB/conexion.php';

if (isset($_GET['q'])) {
    $query = $_GET['q'];
//iba el asterisco
    $sql = "SELECT id, nombre, descripcion, precio, imagenes_json FROM productos WHERE nombre LIKE :query OR descripcion LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="product-item">';
            echo '<div class="product-images">';
            if (!empty($producto['imagenes_json'])) {
                $imagenesArray = json_decode($producto['imagenes_json'], true);
                if (!empty($imagenesArray) && isset($imagenesArray[0])) {
                    $imagenPrincipal = $imagenesArray[0];
                    echo "<a href='javascript:void(0);' onclick=\"checkSession('producto_detalle.php?id={$producto['id']}');\">";
                    echo "<img src='data:image/jpeg;base64,{$imagenPrincipal}' alt='" . htmlspecialchars($producto['nombre']) . "' class='producto-imagen' style='max-width: 150px; max-height: 150px;'/>";
                    echo "</a>";
                } else {
                    echo "No hay imágenes disponibles.";
                }
            } else {
                echo "No hay imágenes disponibles.";
            }
            echo '</div>';
            echo '<h3>' . htmlspecialchars($producto['nombre']) . '</h3>';
            echo '<p>' . htmlspecialchars($producto['descripcion']) . '</p>';
            echo '<span class="price">$' . number_format($producto['precio'], 2) . '</span>';
            echo '</div>';
        }
    } else {
        echo 'No se encontraron productos que coincidan con "' . htmlspecialchars($query) . '".';
    }
} else {
    echo 'No se recibió ningún término de búsqueda.';
}
?>

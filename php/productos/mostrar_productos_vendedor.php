<?php
require __DIR__ . '/../DB/conexion.php';
require_once __DIR__ . '/../../componentes/ProductCard/ProductCard.php';

if (!isset($_SESSION['user_id'])) {
    echo "Usuario no autenticado.";
    exit();
}

$vendedor_id = $_GET['id'] ?? $_SESSION['user_id'];

$sql = "SELECT id, nombre, descripcion, precio, cantidad_disponible, likes, dislikes, imagenes_json, video, para_cotizar 
        FROM productos 
        WHERE vendedor_id = :vendedor_id AND estado = 'aprobado'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($productos) {
    echo "<table class='productos-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Nombre</th>";
    echo "<th>Descripción</th>";
    echo "<th>Precio</th>";
    echo "<th>Cantidad Disponible</th>";
    echo "<th>Likes</th>";
    echo "<th>Dislikes</th>";
    echo "<th>Imagenes</th>";
    echo "<th>Video</th>";
    echo "<th>Acciones</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($productos as $producto) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['descripcion']) . "</td>";
        echo "<td>";
        if (isset($producto['para_cotizar']) && $producto['para_cotizar'] == 1) {
            echo "Disponible solo para cotización";
        } else {
            echo "$" . htmlspecialchars($producto['precio']);
        }
        echo "</td>";
        echo "<td>" . htmlspecialchars($producto['cantidad_disponible']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['likes']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['dislikes']) . "</td>";

        echo "<td>";
        renderTodasImagenes($producto, 100);
        echo "</td>";

        echo "<td>";
        if (!empty($producto['video'])) {
            $video_data = json_decode($producto['video'], true);

            if (is_string($video_data)) {
                $videoURL = $video_data;
            } elseif (isset($video_data['url'])) {
                $videoURL = $video_data['url'];
            } else {
                $videoURL = null;
            }

            if ($videoURL) {
                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $videoURL, $matches);
                if (isset($matches[1])) {
                    $videoID = $matches[1];
                    echo "<iframe width='200' height='150' src='https://www.youtube.com/embed/" . htmlspecialchars($videoID) . "' frameborder='1' allowfullscreen></iframe>";
                } else {
                    echo "No se pudo mostrar el video.";
                }
            } else {
                echo "No hay video disponible";
            }
        } else {
            echo "No hay video disponible.";
        }
        echo "</td>";


        echo "<td><a href='editar_producto.php?id=" . $producto['id'] . "' class='btn-editar'>Editar</a></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='no-productos-container'><p>No hay productos publicados.</p></div>";
}
?>
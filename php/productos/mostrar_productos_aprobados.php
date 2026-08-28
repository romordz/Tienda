<?php
require '..php/DB/conexion.php';

$sql = "SELECT id, nombre, descripcion, precio, cantidad_disponible, likes, dislikes, imagenes_json, video, para_cotizar
FROM productos 
WHERE estado = 'aprobado' 
AND aprobado_por = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);

$productos = $stmt->fetchAll();

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
        if (!empty($producto['imagenes_json'])) {
            $imagenesArray = json_decode($producto['imagenes_json'], true);
            foreach ($imagenesArray as $imagen) {
                echo "<img src='data:image/jpeg;base64,{$imagen}' alt='" . htmlspecialchars($producto['nombre']) . "' class='producto-imagen' style='max-width: 100px; max-height: 100px; margin-right: 5px;'/>";
            }
        } else {
            echo "No hay imágenes disponibles.";
        }
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


        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='no-productos-container'><p>No hay productos aprobados.</p></div>";
}
?>
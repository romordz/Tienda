<?php
session_start();
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../cloudinary/CloudinaryUploader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['categoria_id'], $_POST['precio'], $_POST['cantidad'], $_POST['para_cotizar'], $_POST['video'])) {
        echo "Faltan datos obligatorios.";
        exit();
    }

    $producto_id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $para_cotizar = $_POST['para_cotizar'];
    $video = $_POST['video'];

    $imagenes_actuales = isset($_POST['imagenes_actuales']) ? json_decode($_POST['imagenes_actuales'], true) : [];

    if (isset($_POST['imagen_a_eliminar'])) {
        $indice_imagen_a_eliminar = $_POST['imagen_a_eliminar'];
        if (isset($imagenes_actuales[$indice_imagen_a_eliminar])) {
            unset($imagenes_actuales[$indice_imagen_a_eliminar]);
            $imagenes_actuales = array_values($imagenes_actuales);
        }
    }

    $imagenes_nuevas = [];
    if (!empty($_FILES['imagenes']['name'][0])) {
        $uploader = new CloudinaryUploader();
        foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmp_name) {
            $imagenes_nuevas[] = $uploader->subirImagen($tmp_name);
        }
    }

    $imagenes_finales = array_merge($imagenes_actuales, $imagenes_nuevas);

    if (empty($imagenes_nuevas) && empty($_POST['imagen_a_eliminar'])) {
        $imagenes_json = json_encode($imagenes_actuales);
    } else {
        $imagenes_json = json_encode($imagenes_finales);
    }

    $sql = "UPDATE productos 
            SET nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id, 
                precio = :precio, cantidad_disponible = :cantidad, para_cotizar = :para_cotizar, 
                video = :video, imagenes_json = :imagenes_json 
            WHERE id = :producto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':categoria_id', $categoria_id);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->bindParam(':para_cotizar', $para_cotizar);
    $stmt->bindParam(':video', $video);
    $stmt->bindParam(':imagenes_json', $imagenes_json);
    $stmt->bindParam(':producto_id', $producto_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = 'Producto actualizado correctamente';
        header('Location: ../pantallas/editar_producto.php?id=' . $producto_id);
    } else {
        echo "Error al actualizar el producto.";
    }
}
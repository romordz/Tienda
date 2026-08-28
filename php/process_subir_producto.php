<?php
session_start();
require '../php/conexion.php';
require '../php/CloudinaryUploader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $video = isset($_POST['video']) && !empty(trim($_POST['video'])) ? json_encode(trim($_POST['video'])) : null;
    $categoria_id = $_POST['categoria_id'] ?? null;
    $precio = isset($_POST['para_cotizar']) && $_POST['para_cotizar'] == '1' ? null : $_POST['precio'];
    $cantidad_disponible = $_POST['cantidad'] ?? null;
    $para_cotizar = isset($_POST['para_cotizar']) && $_POST['para_cotizar'] == '1' ? true : false;

    if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['tmp_name']) >= 3) {
        $imagenesArray = [];
        $uploader = new CloudinaryUploader();

        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                $imagenesArray[] = $uploader->subirImagen($tmp_name);
            }
        }

        $imagenesJSON = json_encode($imagenesArray);
    } else {
        echo "Error: Debes subir al menos 3 imágenes.";
        exit();
    }

    $usuario_creador = $_SESSION['user_id'];

    if (empty($nombre) || empty($descripcion) || empty($categoria_id) || empty($cantidad_disponible)) {
        echo "Por favor completa todos los campos requeridos.";
        exit();
    }

    $sql = "INSERT INTO productos (nombre, descripcion, imagenes_json, video, categoria_id, para_cotizar, precio, cantidad_disponible, vendedor_id) 
        VALUES (:nombre, :descripcion, :imagenes_json, :video, :categoria_id, :para_cotizar, :precio, :cantidad_disponible, :vendedor_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':imagenes_json', $imagenesJSON);
    $stmt->bindParam(':video', $video);
    $stmt->bindParam(':categoria_id', $categoria_id);
    $stmt->bindParam(':para_cotizar', $para_cotizar, PDO::PARAM_BOOL);
    if ($precio === null) {
        $stmt->bindValue(':precio', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':precio', $precio);
    }
    $stmt->bindParam(':cantidad_disponible', $cantidad_disponible);
    $stmt->bindParam(':vendedor_id', $usuario_creador, PDO::PARAM_INT);

    try {
        if ($stmt->execute()) {
            echo "Producto subido exitosamente. Redirigiendo...";
            header("Location: ../Productos.php");
            exit();
        } else {
            echo "Error al subir el producto.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
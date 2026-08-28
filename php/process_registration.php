<?php
require '../php/conexion.php';
require '../php/CloudinaryUploader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $photo_url = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            echo "El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.";
            exit();
        }

        $uploader = new CloudinaryUploader();
        $photo_url = $uploader->subirImagen($_FILES['photo']['tmp_name']);
    }

    $checkUserSql = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :username";
    $checkUserStmt = $pdo->prepare($checkUserSql);
    $checkUserStmt->bindParam(':username', $username);
    $checkUserStmt->execute();

    if ($checkUserStmt->fetchColumn() > 0) {
        echo "El nombre de usuario ya está en uso. Elige otro.";
        exit();
    }

    $sql = "INSERT INTO usuarios (nombre_usuario, nombre_completo, sexo, fecha_nacimiento, avatar_url, correo, contrasena, rol) 
            VALUES (:username, :full_name, :gender, :birthdate, :photo_url, :email, :password, :role)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':photo_url', $photo_url);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "Registro exitoso. Redirigiendo...";
        header("Location: Login.php");
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }
}
<?php
session_start();
require __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../cloudinary/CloudinaryUploader.php';
require __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . urlFor('pantallas/Login.php'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $privacy = $_POST['privacy'];

    if (!empty($_FILES['avatar']['tmp_name'])) {
        $uploader = new CloudinaryUploader();
        $avatar_url = $uploader->subirImagen($_FILES['avatar']['tmp_name']);
        $sql = "UPDATE usuarios SET nombre_usuario = :username, correo = :email, fecha_nacimiento = :birthdate, sexo = :gender, privacidad = :privacy, avatar_url = :avatar_url WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':avatar_url', $avatar_url);
    } else {
        $sql = "UPDATE usuarios SET nombre_usuario = :username, correo = :email, fecha_nacimiento = :birthdate, sexo = :gender, privacidad = :privacy WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':privacy', $privacy);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['birthdate'] = $birthdate;
        $_SESSION['gender'] = $gender;
        $_SESSION['privacy'] = $privacy;

        if (isset($avatar_url)) {
            $_SESSION['avatar'] = $avatar_url;
        }

        header("Location: " . urlFor('pantallas/Perfil.php?success=1'));
    } else {
        echo "Error al actualizar el perfil.";
    }
}
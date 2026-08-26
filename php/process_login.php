<?php
session_start();
require '../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user'];
    $password = $_POST['password'];

    $sql = "SELECT id, correo, nombre_usuario, contraseña, rol, avatar, nombre_completo, fecha_nacimiento, sexo, privacidad 
            FROM usuarios 
            WHERE correo = :email 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre_usuario'];
            $_SESSION['full_name'] = $user['nombre_completo'];
            $_SESSION['email'] = $user['correo'];
            $_SESSION['birthdate'] = $user['fecha_nacimiento'];
            $_SESSION['gender'] = $user['sexo'];
            $_SESSION['role'] = $user['rol'];
            $_SESSION['privacy'] = $user['privacidad'];

            if ($user['avatar']) {
                $_SESSION['avatar'] = base64_encode($user['avatar']);
            } else {
                $_SESSION['avatar'] = null;
            }

            if (isset($_GET['redirect'])) {
                $redirect_url = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);

                if (strpos($redirect_url, 'http') === false) {
                    $redirect_url = '/Tienda/' . ltrim($redirect_url, '/');
                }
                header("Location: " . $redirect_url);
            } else {
                header("Location: ../Principal.php");
            }

            exit();
        } else {
            header("Location: ../Login.php?error=wrong_password");
            exit();
        }
    } else {
        header("Location: ../Login.php?error=user_not_found");
        exit();
    }
}
?>
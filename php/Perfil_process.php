<?php
require 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$session_user_id = $_SESSION['user_id'];
$profile_user_id = $_GET['id'] ?? $session_user_id;

$sql = "SELECT nombre_usuario, nombre_completo, sexo, fecha_nacimiento, avatar_url, correo, rol, privacidad FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_user_id]);

$profile_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile_data) {
    $profile_username = $profile_data['nombre_usuario'];
    $profile_fullname = $profile_data['nombre_completo'];
    $profile_gender = $profile_data['sexo'];
    $profile_birthdate = $profile_data['fecha_nacimiento'];
    $profile_avatar = base64_encode($profile_data['avatar_url']);
    $profile_email = $profile_data['correo'];
    $profile_role = $profile_data['rol'];
    $profile_privacy = $profile_data['privacidad'];
} else {
    $_SESSION['error'] = 'No se encontraron datos para el usuario.';
}
?>

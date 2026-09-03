<?php
require __DIR__ . '/../sesion/init.php'; 
include __DIR__ . '/../DB/conexion.php';
require __DIR__ . '/../config.php';

$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$rol_usuario = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if (!$usuario_id || $rol_usuario !== 'cliente') {
    header('Location: ' . urlFor('pantallas/carrito.php?error=no_cliente'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $nombre_tarjeta = $_POST['nombre_tarjeta'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $cvv = $_POST['cvv'];

    $fecha_vencimiento .= '-01';
    $fecha_vencimiento = date('Y-m-t', strtotime($fecha_vencimiento));

    try {
        $sqlTarjeta = "INSERT INTO tarjetas (usuario_id, numero_tarjeta, nombre_tarjeta, fecha_vencimiento, cvv) 
                       VALUES (:usuario_id, :numero_tarjeta, :nombre_tarjeta, :fecha_vencimiento, :cvv)";
        $stmtTarjeta = $pdo->prepare($sqlTarjeta);
        $stmtTarjeta->bindParam(':usuario_id', $usuario_id);
        $stmtTarjeta->bindParam(':numero_tarjeta', $numero_tarjeta);
        $stmtTarjeta->bindParam(':nombre_tarjeta', $nombre_tarjeta);
        $stmtTarjeta->bindParam(':fecha_vencimiento', $fecha_vencimiento);
        $stmtTarjeta->bindParam(':cvv', $cvv);
        $stmtTarjeta->execute();

        header('Location: ' . urlFor('pantallas/carrito.php?action=tarjeta_agregada'));
        exit;
    } catch (Exception $e) {
        die('Error al agregar la tarjeta: ' . $e->getMessage());
    }
}
?>

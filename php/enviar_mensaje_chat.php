<?php
session_start();
require '../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $producto_id = $_POST['producto_id'];
    $mensaje = $_POST['mensaje'];

    if (!empty($mensaje)) {
        $sql = "SELECT vendedor_id FROM Productos WHERE id = :producto_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->execute();
        $vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vendedor) {
            $vendedor_id = $vendedor['vendedor_id'];

            if ($usuario_id == $vendedor_id) {
                $sql_check_convo = "SELECT id FROM Conversaciones WHERE producto_id = :producto_id 
                                    AND vendedor_id = :vendedor_id";
                $stmt_check_convo = $pdo->prepare($sql_check_convo);
                $stmt_check_convo->bindParam(':producto_id', $producto_id);
                $stmt_check_convo->bindParam(':vendedor_id', $vendedor_id);
            } else {
                $sql_check_convo = "SELECT id FROM Conversaciones WHERE producto_id = :producto_id 
                                    AND comprador_id = :comprador_id AND vendedor_id = :vendedor_id";
                $stmt_check_convo = $pdo->prepare($sql_check_convo);
                $stmt_check_convo->bindParam(':producto_id', $producto_id);
                $stmt_check_convo->bindParam(':comprador_id', $usuario_id);
                $stmt_check_convo->bindParam(':vendedor_id', $vendedor_id);
            }

            $stmt_check_convo->execute();
            $convo = $stmt_check_convo->fetch(PDO::FETCH_ASSOC);

            if (!$convo) {
                $sql_insert_convo = "INSERT INTO Conversaciones (producto_id, comprador_id, vendedor_id) 
                                     VALUES (:producto_id, :comprador_id, :vendedor_id)";
                $stmt_insert_convo = $pdo->prepare($sql_insert_convo);
                $stmt_insert_convo->bindParam(':producto_id', $producto_id);
                
                if ($usuario_id == $vendedor_id) {
                    $stmt_insert_convo->bindParam(':comprador_id', $comprador_id);
                    $stmt_insert_convo->bindParam(':vendedor_id', $usuario_id);
                } else {
                    $stmt_insert_convo->bindParam(':comprador_id', $usuario_id);
                    $stmt_insert_convo->bindParam(':vendedor_id', $vendedor_id);
                }
                
                $stmt_insert_convo->execute();
                $conversacion_id = $pdo->lastInsertId();
            } else {
                $conversacion_id = $convo['id'];
            }

            $sql_insert_msg = "INSERT INTO Mensajes (conversacion_id, usuario_id, mensaje, producto_id) 
                               VALUES (:conversacion_id, :usuario_id, :mensaje, :producto_id)";
            $stmt_insert_msg = $pdo->prepare($sql_insert_msg);
            $stmt_insert_msg->bindParam(':conversacion_id', $conversacion_id);
            $stmt_insert_msg->bindParam(':usuario_id', $usuario_id);
            $stmt_insert_msg->bindParam(':mensaje', $mensaje);
            $stmt_insert_msg->bindParam(':producto_id', $producto_id);
            $stmt_insert_msg->execute();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se encontró el vendedor para el producto especificado.']);
        }
    }
}
?>

<?php
session_start();
require __DIR__ . '/../DB/conexion.php';

$producto_id = $_GET['producto_id'] ?? null;

if ($producto_id) {
    $sql = "SELECT vendedor_id FROM productos WHERE id = :producto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id);
    $stmt->execute();
    $vendedor = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($vendedor) {
        $vendedor_id = $vendedor['vendedor_id'];
        $usuario_id = $_SESSION['user_id'];

        if ($usuario_id === $vendedor_id) {
            $sql_convo = "SELECT id FROM conversaciones WHERE producto_id = :producto_id 
                          AND comprador_id != :usuario_id 
                          AND vendedor_id = :usuario_id";
        } else {
            $sql_convo = "SELECT id FROM conversaciones WHERE producto_id = :producto_id 
                          AND comprador_id = :usuario_id 
                          AND vendedor_id = :vendedor_id";
        }

        $stmt_convo = $pdo->prepare($sql_convo);
        $stmt_convo->bindParam(':producto_id', $producto_id);
        $stmt_convo->bindParam(':usuario_id', $usuario_id);
        $stmt_convo->bindParam(':vendedor_id', $vendedor_id);
        $stmt_convo->execute();
        $convo = $stmt_convo->fetch(PDO::FETCH_ASSOC);

        if ($convo) {
            $sql = "SELECT m.mensaje, m.fecha, u.nombre_usuario
                    FROM mensajes m
                    JOIN usuarios u ON m.usuario_id = u.id
                    WHERE m.conversacion_id = :conversacion_id
                    ORDER BY m.fecha ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':conversacion_id', $convo['id']);
            $stmt->execute();
            $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($mensajes) {
                foreach ($mensajes as $mensaje) {
                    echo '<div class="chat-message">';
                    echo '<strong>' . htmlspecialchars($mensaje['nombre_usuario']) . ':</strong> ';
                    echo htmlspecialchars($mensaje['mensaje']);
                    echo '<br><small>' . $mensaje['fecha'] . '</small>';
                    echo '</div>';
                }
            } else {
                echo "No hay mensajes en esta conversación.";
            }
        } else {
            echo "No se ha encontrado la conversación.";
        }
    } else {
        echo "Error: El vendedor no está disponible.";
    }
} else {
    echo "Error: No se ha proporcionado un ID de producto.";
}
?>

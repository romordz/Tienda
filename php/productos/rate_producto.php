<?php
require __DIR__ . '/../DB/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    $usuarioId = $_SESSION['user_id'];
    $productoId = $_POST['id'];
    $rating = $_POST['rating'];

    $stmt = $pdo->prepare("SELECT * FROM valoraciones WHERE usuario_id = :usuario_id AND producto_id = :producto_id");
    $stmt->bindParam(':usuario_id', $usuarioId);
    $stmt->bindParam(':producto_id', $productoId);
    $stmt->execute();
    $valoracionExistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($valoracionExistente) {
        if ($valoracionExistente['valoracion'] == $rating) {
            $deleteStmt = $pdo->prepare("DELETE FROM valoraciones WHERE usuario_id = :usuario_id AND producto_id = :producto_id");
            $deleteStmt->bindParam(':usuario_id', $usuarioId);
            $deleteStmt->bindParam(':producto_id', $productoId);
            $deleteStmt->execute();
            
            if ($rating == 'like') {
                $sqlUpdate = "UPDATE productos SET likes = likes - 1 WHERE id = :id";
            } else {
                $sqlUpdate = "UPDATE productos SET dislikes = dislikes - 1 WHERE id = :id";
            }
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id', $productoId);
            $stmtUpdate->execute();

            $response['success'] = true;
            $response['message'] = "¡Se ha quitado tu valoración!";
        } else {
            $updateStmt = $pdo->prepare("UPDATE valoraciones SET valoracion = :valoracion WHERE usuario_id = :usuario_id AND producto_id = :producto_id");
            $updateStmt->bindParam(':valoracion', $rating);
            $updateStmt->bindParam(':usuario_id', $usuarioId);
            $updateStmt->bindParam(':producto_id', $productoId);
            $updateStmt->execute();

            if ($rating == 'like') {
                $sqlUpdate = "UPDATE productos SET likes = likes + 1, dislikes = dislikes - 1 WHERE id = :id";
            } else {
                $sqlUpdate = "UPDATE productos SET dislikes = dislikes + 1, likes = likes - 1 WHERE id = :id";
            }
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id', $productoId);
            $stmtUpdate->execute();

            $response['success'] = true;
            $response['message'] = "¡Gracias por actualizar tu valoración!";
        }
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO valoraciones (usuario_id, producto_id, valoracion) VALUES (:usuario_id, :producto_id, :valoracion)");
        $insertStmt->bindParam(':usuario_id', $usuarioId);
        $insertStmt->bindParam(':producto_id', $productoId);
        $insertStmt->bindParam(':valoracion', $rating);
        $insertStmt->execute();

        if ($rating == 'like') {
            $sqlUpdate = "UPDATE productos SET likes = likes + 1 WHERE id = :id";
        } else {
            $sqlUpdate = "UPDATE productos SET dislikes = dislikes + 1 WHERE id = :id";
        }
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':id', $productoId);
        $stmtUpdate->execute();

        $response['success'] = true;
        $response['message'] = "¡Gracias por tu valoración!";
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>

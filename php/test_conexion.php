// test_connection.php
<?php
require '../php/conexion.php';

try {
    $pdo->query("SELECT 1");
    echo "Conexión exitosa.";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
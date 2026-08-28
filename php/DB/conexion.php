<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'tienda';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'abc123';
$port = getenv('DB_PORT') ?: '3306';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    if (getenv('DB_HOST')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . '/../Config/ca.pem';
    }

    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
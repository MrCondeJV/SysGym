<?php

// Cargar las variables de entorno
include(__DIR__ . '../../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Si .env está en la raíz
$dotenv->load();

// Obtener las variables de entorno
if (!defined('SERVIDOR')) {
    define('SERVIDOR', $_ENV['DB_HOST'] ?? '');
}

if (!defined('USUARIO')) {
    define('USUARIO', $_ENV['DB_USER'] ?? '');
}

if (!defined('PASSWORD')) {
    define('PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
}

if (!defined('BD')) {
    define('BD', $_ENV['DB_NAME'] ?? '');
}

// Crear la cadena de conexión
$servidor = "mysql:dbname=" . BD . ";host=" . SERVIDOR;

try {
    $pdo = new PDO(
        $servidor,
        USUARIO,
        PASSWORD,
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    //echo "Conexión exitosa.";
} catch (PDOException $e) {
    // Mostrar información de depuración solo durante el desarrollo
    die("
        <strong>Error al conectar a la base de datos:</strong><br>
        <strong>Mensaje:</strong> {$e->getMessage()}<br>
        <strong>Host:</strong> " . SERVIDOR . "<br>
        <strong>Usuario:</strong> " . USUARIO . "<br>
        <strong>Base de datos:</strong> " . BD . "<br>
        <strong>DSN:</strong> {$servidor}
    ");
}

$URL = "http://localhost/Dashboard_2.0";

date_default_timezone_set("America/Bogota");
$fechaHora = date('Y-m-d H:i:s');

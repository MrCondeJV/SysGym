<?php

// Cargar las variables de entorno
include(__DIR__ . '../../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Si .env está en la raíz
$dotenv->load();


// Obtener las variables de entorno (usando $_ENV)
if (!defined('SERVIDOR')) {
    define('SERVIDOR', $_ENV['DB_HOST'] ?? ''); // Valor por defecto como fallback
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

// Crear la cadena de conexión (añadiendo el puerto que estaba en tu .env)
$servidor = "mysql:dbname=" . BD . ";host=" . SERVIDOR . ";port=" . ($_ENV['DB_PORT'] ?? '3306');

try {
    $pdo = new PDO(
        $servidor,
        USUARIO,
        PASSWORD,
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Para manejar errores correctamente
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Para obtener resultados como array asociativo
        ]
    );
    //echo "La conexión a la base de datos fue con éxito";
} catch (PDOException $e) {
    // Mejor práctica: registrar el error y mostrar un mensaje genérico
    error_log("Error de conexión a BD: " . $e->getMessage());
    die("Error al conectar a la base de datos. Por favor intente más tarde.");
}

$URL = "http://localhost/sisgym/";

date_default_timezone_set("America/Bogota");
$fechaHora = date('Y-m-d H:i:s');
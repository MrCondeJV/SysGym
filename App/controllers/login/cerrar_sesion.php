<?php
include ('../../config.php');

session_start();
if(isset($_SESSION['sesion_usuario'])){
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
}
$URL = rtrim($URL, '/'); // Elimina cualquier barra extra al final
header('Location: '.$URL.'/App/views/login/index.php');
exit; // Asegura que no se ejecute más código después de la redirección
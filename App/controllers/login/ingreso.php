<?php


include('../../config.php');
session_start();

$nombre_usuario = $_POST['nombre_usuario'] ?? '';
$password_user = $_POST['password_user'] ?? '';

// Consulta para buscar usuario en la tabla usuariossistema
$sql = "SELECT * FROM usuariossistema WHERE nombre_usuario = :nombre_usuario LIMIT 1";
$query = $pdo->prepare($sql);
$query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    if (strtolower($usuario['estado']) !== 'activo') {
        $_SESSION['mensaje'] = "Acceso denegado: Usuario inactivo.";
        header('Location: /SysGym/App/views/login/index.php');
        exit;
    }

    if (password_verify($password_user, $usuario['contrasena_hash'])) {
        $_SESSION['sesion_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];
        $_SESSION['rol'] = $usuario['rol'];

        // Actualizar último acceso
        $sql_update = "UPDATE usuariossistema SET ultimo_acceso = NOW() WHERE id_usuario = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$usuario['id_usuario']]);

        // Redirigir al inicio general
        header('Location: /SysGym/index.php');
        exit;
    }
}

$_SESSION['mensaje'] = "Error: Datos incorrectos";
header('Location: /SysGym/App/views/login/index.php');
exit;
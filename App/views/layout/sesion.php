<?php

include(__DIR__ . '/../../config.php');

// Configuración de la sesión para 15 minutos
$session_lifetime = 20000; // 15 minutos en segundos

// Configuración segura de la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => $session_lifetime,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

if (isset($_GET['renew']) && $_GET['renew'] == "true") {
    session_regenerate_id(true);
    $_SESSION['LAST_ACTIVITY'] = time();

    setcookie(
        session_name(),
        session_id(),
        [
            'expires' => time() + $session_lifetime,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Sesión renovada']);
    exit();
}

// Verificar sesión expirada
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime) {
        session_unset();
        session_destroy();

        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true
            ]
        );

        header('Content-Type: application/json');
        echo json_encode(['status' => 'expired', 'message' => 'La sesión ha expirado']);
        exit();
    }
} else {
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Actualizar tiempo de actividad en cada carga
$_SESSION['LAST_ACTIVITY'] = time();

// Renovar cookie de sesión en cada interacción
setcookie(
    session_name(),
    session_id(),
    [
        'expires' => time() + $session_lifetime,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]
);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['sesion_usuario'])) {
    $nombre_usuario = $_SESSION['sesion_usuario'];

    $sql = "SELECT 
                id_usuario,
                nombres,
                apellidos,
                nombre_usuario,
                rol,
                telefono,
                correo_electronico,
                ultimo_acceso,
                estado,
                creado_en
            FROM usuariossistema
            WHERE nombre_usuario = :nombre_usuario
            LIMIT 1";

    $query = $pdo->prepare($sql);
    $query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $query->execute();

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];
        $_SESSION['rol'] = $usuario['rol']; // aquí guardas el ID del rol
        $_SESSION['telefono'] = $usuario['telefono'];
        $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
        $_SESSION['ultimo_acceso'] = $usuario['ultimo_acceso'];
        $_SESSION['estado'] = $usuario['estado'];
        $_SESSION['creado_en'] = $usuario['creado_en'];

        // Opcional: cargar el nombre del rol y guardarlo también
        $stmtRol = $pdo->prepare("SELECT nombre FROM roles WHERE id = :id_rol");
        $stmtRol->execute([':id_rol' => $usuario['rol']]);
        $rol_info = $stmtRol->fetch(PDO::FETCH_ASSOC);
        $_SESSION['nombre_rol'] = $rol_info ? $rol_info['nombre'] : null;
    } else {
        session_destroy();
        header('Location: /SysGym/App/views/login/index.php');
        exit();
    }
} else {
    header('Location: /SysGym/App/views/login/index.php');
    exit();
}
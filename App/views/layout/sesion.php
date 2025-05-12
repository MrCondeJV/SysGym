<?php
include(__DIR__ . '/../app/config.php');

// Configuración de la sesión para 15 minutos
$session_lifetime = 900; // 15 minutos en segundos (15 × 60 = 900)

// Configuración segura de la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => $session_lifetime,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']), // Activar solo en HTTPS
        'httponly' => true,
        'samesite' => 'Lax' // Protección CSRF
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
if (isset($_SESSION['LAST_ACTIVITY'])) {  // CORRECCIÓN: Cambiado de LAST_ACTIVITY a LAST_ACTIVITY
    if (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime) {
        // Destruir sesión completamente
        session_unset();
        session_destroy();
        
        // Eliminar cookie de sesión
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
        
        // Respuesta para el frontend
        header('Content-Type: application/json');
        echo json_encode(['status' => 'expired', 'message' => 'La sesión ha expirado']);
        exit();
    }
} else {
    // Inicializar tiempo de actividad si no existe
    $_SESSION['LAST_ACTIVITY'] = time(); // CORRECCIÓN: Cambiado de LAST_ACTIVITY a LAST_ACTIVITY
}

// Actualizar tiempo de actividad en cada carga
$_SESSION['LAST_ACTIVITY'] = time(); // CORRECCIÓN: Cambiado de LAST_ACTIVITY a LAST_ACTIVITY

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

// Si hay un usuario en sesión, obtener datos
if (isset($_SESSION['sesion_usuario'])) {
    $nombre_usuario = $_SESSION['sesion_usuario'];

    $sql = "SELECT 
                us.ID AS id_usuario, 
                CASE 
                    WHEN emp.ID IS NOT NULL THEN emp.ID
                    WHEN cli.ID IS NOT NULL THEN cli.ID
                END AS relacionado_id,
                CASE 
                    WHEN emp.ID IS NOT NULL THEN emp.Nombre
                    WHEN cli.ID IS NOT NULL THEN 
                        CASE 
                            WHEN cli.RazonSocial IS NOT NULL AND cli.RazonSocial <> '' 
                            THEN cli.RazonSocial 
                            ELSE CONCAT(cli.Nombres, ' ', cli.Apellidos) 
                        END
                END AS nombre_relacionado,
                CASE 
                    WHEN emp.ID IS NOT NULL THEN emp.Foto
                    ELSE NULL
                END AS foto
            FROM Usuarios AS us
            LEFT JOIN Empleados AS emp ON us.EmpleadoID = emp.ID
            LEFT JOIN Clientes AS cli ON us.ClienteID = cli.ID
            WHERE us.NombreUsuario = :nombre_usuario";

    $query = $pdo->prepare($sql);
    $query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $query->execute();

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $id_usuario_sesion = $usuario['id_usuario'];
        $relacionado_id = $usuario['relacionado_id']; // fallback
        
        $nombre_relacionado_sesion = $usuario['nombre_relacionado'];
        $foto_sesion = $usuario['foto'];

        $_SESSION['id_usuario'] = $id_usuario_sesion;
        $_SESSION['relacionado_id'] = $relacionado_id;
        $_SESSION['nombre_relacionado'] = $nombre_relacionado_sesion;
        
        if (!is_null($foto_sesion)) {
            $_SESSION['foto_usuario'] = $foto_sesion;
        }
    } else {
        // Si no se encuentra el usuario, destruir la sesión
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
} else {
    header('Location: '.$URL.'login/index.php');
    exit();
}
?>
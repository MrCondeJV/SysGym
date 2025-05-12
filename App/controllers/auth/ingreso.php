<?php
include('../../config.php');
session_start();

$nombre_usuario = $_POST['nombre_usuario'];
$password_user = $_POST['password_user'];

// Modificamos la consulta para incluir clientes
$sql = "SELECT u.*, 
        COALESCE(e.Estado, c.Estado) AS Estado,
        CASE 
            WHEN u.EmpleadoID IS NOT NULL THEN 'Empleado'
            WHEN u.ClienteID IS NOT NULL THEN 'Cliente'
        END AS TipoUsuario
        FROM Usuarios u
        LEFT JOIN Empleados e ON u.EmpleadoID = e.ID
        LEFT JOIN Clientes c ON u.ClienteID = c.ID
        WHERE u.NombreUsuario = :nombre_usuario";
$query = $pdo->prepare($sql);
$query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    if ($usuario['Estado'] !== 'Activo') {
        $_SESSION['mensaje'] = "Acceso denegado: Usuario inactivo.";
        header('Location: ' . rtrim($URL, '/') . '/login/index.php');
        exit;
    }

    if (password_verify($password_user, $usuario['Contrasena'])) {
        $_SESSION['sesion_usuario'] = $usuario['NombreUsuario'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        $_SESSION['TipoUsuario'] = $usuario['TipoUsuario']; // Almacenamos el tipo de usuario
        
        // Almacenamos el ID correspondiente según el tipo
        if ($usuario['TipoUsuario'] == 'Empleado') {
            $_SESSION['EmpleadoID'] = $usuario['EmpleadoID'];
        } else {
            $_SESSION['ClienteID'] = $usuario['ClienteID'];
        }

        // Obtener permisos del rol (esto puede mantenerse igual)
        $sql_permisos = "
            SELECT p.Nombre, p.URL 
            FROM Permisos p
            INNER JOIN Rol_Permisos rp ON p.ID = rp.permiso_id
            WHERE rp.rol_id = :rol_id";
        $query_permisos = $pdo->prepare($sql_permisos);
        $query_permisos->bindParam(':rol_id', $_SESSION['rol_id'], PDO::PARAM_INT);
        $query_permisos->execute();
        $permisos = $query_permisos->fetchAll(PDO::FETCH_ASSOC);
        
        $_SESSION['permisos'] = array_column($permisos, 'URL');

        // Redirigir según el rol (puedes ajustar esto según tus necesidades)
        if ($_SESSION['rol_id'] == 3) {
            header('Location: ' . rtrim($URL, '/') . '/conductor/index.php');
        } elseif ($_SESSION['rol_id'] == 6) { // Ejemplo para rol de cliente
            header('Location: ' . rtrim($URL, '/') . '/clientesdash/index.php');
        } else {
            header('Location: ' . rtrim($URL, '/'));
        }
        exit;
    }
}

$_SESSION['mensaje'] = "Error: Datos incorrectos";
header('Location: ' . rtrim($URL, '/') . '/login/index.php');
exit;
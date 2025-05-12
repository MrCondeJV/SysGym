<?php
include('../app/config.php');

// Obtener ID del usuario desde la URL
$id_usuario = $_GET['id'] ?? '';

// Validar ID
if (!$id_usuario || !is_numeric($id_usuario)) {
    $_SESSION['mensaje'] = "Error: No se recibió un ID válido";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

// Consulta optimizada para obtener datos del usuario
$sql_usuario = "
    SELECT 
        us.id AS id_usuario,
        us.nombreusuario AS nombre_usuario,
        us.rol_id,
        us.EmpleadoID,
        us.ClienteID,
        emp.nombre,
        cli.Tipo AS cli_tipo,
        cli.RazonSocial AS cli_razon_social,
        cli.Nombres AS cli_nombres,
        cli.Apellidos AS cli_apellidos
    FROM usuarios AS us
    LEFT JOIN empleados AS emp ON us.EmpleadoID = emp.id
    LEFT JOIN clientes AS cli ON us.ClienteID = cli.id
    WHERE us.id = :id_usuario
";

$query_usuario = $pdo->prepare($sql_usuario);
$query_usuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$query_usuario->execute();
$usuario_datos = $query_usuario->fetch(PDO::FETCH_ASSOC);

if (!$usuario_datos) {
    $_SESSION['mensaje'] = "Usuario no encontrado";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

// Determinar nombre relacionado y tipo
if ($usuario_datos['EmpleadoID']) {
    $nombre_relacionado = trim($usuario_datos['emp_nombres'] . ' ' . $usuario_datos['emp_apellidos']);
    $tipo_relacionado = 'Empleado';
} elseif ($usuario_datos['ClienteID']) {
    if ($usuario_datos['cli_tipo'] === 'Empresa') {
        $nombre_relacionado = $usuario_datos['cli_razon_social'];
    } else {
        $nombre_relacionado = trim($usuario_datos['cli_nombres'] . ' ' . $usuario_datos['cli_apellidos']);
    }
    $tipo_relacionado = 'Cliente';
} else {
    $nombre_relacionado = 'No asignado';
    $tipo_relacionado = 'Sin asignar';
}

// Asignar variables para la vista
$id_usuario = $usuario_datos['id_usuario'];
$nombre_usuario = $usuario_datos['nombre_usuario'];
$rol_id = $usuario_datos['rol_id'];
<?php
include ('../../config.php');

$nombres = $_POST['nombre'];
$rol = $_POST['rol'];
$id_empleado_cliente = $_POST['empleado_cliente_id'];
$tipo = $_POST['tipo'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];

session_start();

// Validación 1: Contraseñas coinciden
if ($password_user !== $password_repeat) {
    $_SESSION['mensaje'] = "Las contraseñas no coinciden.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'usuarios/create.php');
    exit;
}

// Validación 2: Nombre de usuario único
$sentencia_verificar = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE NombreUsuario = :nombre");
$sentencia_verificar->bindParam(':nombre', $nombres);
$sentencia_verificar->execute();
$resultado = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $_SESSION['mensaje'] = "El nombre de usuario ya está en uso. Por favor elija otro.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'usuarios/create.php');
    exit;
}

// Validación 3: Verificar si el empleado o cliente ya tiene usuario asociado
if ($tipo === 'Empleado') {
    $sentencia_verificar_asociacion = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE EmpleadoID = :id");
} elseif ($tipo === 'Cliente') {
    $sentencia_verificar_asociacion = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE ClienteID = :id");
} else {
    $_SESSION['mensaje'] = "Tipo no válido.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'usuarios/create.php');
    exit;
}

$sentencia_verificar_asociacion->bindParam(':id', $id_empleado_cliente);
$sentencia_verificar_asociacion->execute();
$resultado_asociacion = $sentencia_verificar_asociacion->fetch(PDO::FETCH_ASSOC);

if ($resultado_asociacion['total'] > 0) {
    $_SESSION['mensaje'] = "Este ".strtolower($tipo)." ya tiene un usuario asociado.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'usuarios/create.php');
    exit;
}

// Si pasó todas las validaciones, proceder con la creación
$password_user = password_hash($password_user, PASSWORD_DEFAULT);

if ($tipo === 'Empleado') {
    $sentencia = $pdo->prepare("INSERT INTO usuarios 
        (NombreUsuario, contrasena, EmpleadoID, rol_id) 
        VALUES (:nombres, :password_user, :id_empleado_cliente, :rol)");
} elseif ($tipo === 'Cliente') {
    $sentencia = $pdo->prepare("INSERT INTO usuarios 
        (NombreUsuario, contrasena, ClienteID, rol_id) 
        VALUES (:nombres, :password_user, :id_empleado_cliente, :rol)");
}

$sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
$sentencia->bindParam(':password_user', $password_user, PDO::PARAM_STR);
$sentencia->bindParam(':id_empleado_cliente', $id_empleado_cliente, PDO::PARAM_INT);
$sentencia->bindParam(':rol', $rol, PDO::PARAM_INT);

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = "Se registró al usuario correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'usuarios/index.php');
} else {
    $_SESSION['mensaje'] = "Error al registrar el usuario.";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'usuarios/create.php');
}
?>
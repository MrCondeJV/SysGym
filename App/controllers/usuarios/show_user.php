<?php
include('../../config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario_get = $_GET['id'];

    // Consulta para obtener los datos del usuario con los campos requeridos
    $sql_usuario = "
        SELECT 
            id_usuario,
            nombres,
            apellidos,
            nombre_usuario,
            contrasena_hash,
            rol,
            telefono,
            correo_electronico,
            ultimo_acceso,
            estado,
            creado_en
        FROM usuariossistema
        WHERE id_usuario = :id_usuario
    ";

    $query_usuario = $pdo->prepare($sql_usuario);
    $query_usuario->bindParam(':id_usuario', $id_usuario_get, PDO::PARAM_INT);
    $query_usuario->execute();
    $usuario_datos = $query_usuario->fetch(PDO::FETCH_ASSOC);

    if ($usuario_datos) {
        $id_usuario = $usuario_datos['id_usuario'];
        $nombres = $usuario_datos['nombres'];
        $apellidos = $usuario_datos['apellidos'];
        $nombre_usuario = $usuario_datos['nombre_usuario'];
        $contrasena_hash = $usuario_datos['contrasena_hash'];
        $rol = $usuario_datos['rol'];
        $telefono = $usuario_datos['telefono'];
        $correo_electronico = $usuario_datos['correo_electronico'];
        $ultimo_acceso = $usuario_datos['ultimo_acceso'];
        $estado = $usuario_datos['estado'];
        $creado_en = $usuario_datos['creado_en'];
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de usuario no válido'); window.location.href='index.php';</script>";
    exit();
}
?>

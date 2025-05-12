<?php
include ('../app/config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario_get = $_GET['id'];

    // Consulta para obtener los datos del usuario con empleado, cliente y rol
    $sql_usuario = "
        SELECT 
            us.id AS id_usuario, 
            us.rol_id AS rol_actual_usuario,
            us.nombreusuario AS nombre_usuario, 
            rol.nombre AS nombre_rol,
            CASE 
                WHEN emp.ID IS NOT NULL THEN emp.nombre
                WHEN cli.ID IS NOT NULL THEN 
                    CASE 
                        WHEN cli.RazonSocial IS NOT NULL AND cli.RazonSocial <> '' 
                        THEN cli.RazonSocial 
                        ELSE CONCAT(cli.Nombres, ' ', cli.Apellidos) 
                    END
                ELSE 'Sin asignar'
            END AS nombre_relacionado,
            CASE 
                WHEN emp.ID IS NOT NULL THEN 'Empleado'
                WHEN cli.ID IS NOT NULL THEN 'Cliente'
                ELSE 'Sin asignar'
            END AS tipo_relacionado
        FROM usuarios AS us
        LEFT JOIN empleados AS emp ON us.empleadoid = emp.ID
        LEFT JOIN clientes AS cli ON us.clienteid = cli.ID
        LEFT JOIN roles AS rol ON us.rol_id = rol.id
        WHERE us.id = :id_usuario
    ";

    $query_usuario = $pdo->prepare($sql_usuario);
    $query_usuario->bindParam(':id_usuario', $id_usuario_get, PDO::PARAM_INT);
    $query_usuario->execute();
    $usuario_datos = $query_usuario->fetch(PDO::FETCH_ASSOC);

    if ($usuario_datos) {
        $id_usuario = $usuario_datos['id_usuario'];
        $nombre_usuario = $usuario_datos['nombre_usuario'];
        $nombre_relacionado = $usuario_datos['nombre_relacionado'];
        $tipo_relacionado = $usuario_datos['tipo_relacionado'];
        $rol_actual = $usuario_datos['rol_actual_usuario'];
        $nombre_rol = $usuario_datos['nombre_rol'];
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de usuario no válido'); window.location.href='index.php';</script>";
    exit();
}
?>

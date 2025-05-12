<?php
$sql_usuarios = "
    SELECT 
        us.ID AS UsuarioID,
        us.NombreUsuario,
        us.contrasena,
        us.rol_id,
        rol.nombre AS RolNombre,
        CASE 
            WHEN emp.ID IS NOT NULL THEN emp.nombre
            WHEN cli.ID IS NOT NULL THEN 
                CASE 
                    WHEN cli.RazonSocial IS NOT NULL AND cli.RazonSocial <> '' 
                    THEN cli.RazonSocial 
                    ELSE CONCAT(cli.Nombres, ' ', cli.Apellidos) 
                END
            ELSE 'Sin asignar'
        END AS NombreRelacionado,
        CASE 
            WHEN emp.ID IS NOT NULL THEN 'Empleado'
            WHEN cli.ID IS NOT NULL THEN 'Cliente'
            ELSE 'Sin asignar'
        END AS TipoRelacionado
    FROM usuarios AS us
    LEFT JOIN empleados AS emp ON us.EmpleadoID = emp.ID
    LEFT JOIN clientes AS cli ON us.ClienteID = cli.ID
    LEFT JOIN roles AS rol ON us.rol_id = rol.id
";

$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute();
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
?>

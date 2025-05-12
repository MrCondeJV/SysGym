<?php
include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    // Validar y sanitizar entradas
    $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
    $nombre_usuario = isset($_POST['nombre_usuario']) ? trim($_POST['nombre_usuario']) : '';
    $rol = isset($_POST['rol']) ? intval($_POST['rol']) : 0;
    $contrasena = isset($_POST['password_user']) ? $_POST['password_user'] : '';
    $contrasena_repetida = isset($_POST['password_repeat']) ? $_POST['password_repeat'] : '';

    // Validaciones básicas (mantén las que ya tienes)
    
    try {
        // Verificar si el usuario existe
        $sentencia_verificar = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE id = :id_usuario");
        $sentencia_verificar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sentencia_verificar->execute();
        
        if ($sentencia_verificar->fetchColumn() == 0) {
            $_SESSION['mensaje'] = "El usuario no existe";
            $_SESSION['icono'] = "error";
            header("Location: $URL/usuarios/");
            exit();
        }

        // Construir consulta dinámica
        $updates = [];
        $params = [
            ':nombre_usuario' => $nombre_usuario,
            ':rol' => $rol,
            ':id_usuario' => $id_usuario
        ];

        // Solo actualizar contraseña si se proporcionó
        if (!empty($contrasena)) {
            $updates[] = "contrasena = :contrasena";
            $params[':contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        // Construir la consulta final
        $sql = "UPDATE usuarios SET 
                nombreusuario = :nombre_usuario, 
                rol_id = :rol" .
                (!empty($updates) ? ", " . implode(", ", $updates) : "") .
                " WHERE id = :id_usuario";

        $sentencia = $pdo->prepare($sql);
        
        // Ejecutar consulta
        if ($sentencia->execute($params)) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente";
            $_SESSION['icono'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el usuario";
            $_SESSION['icono'] = "error";
        }
        
        header("Location: $URL/usuarios/");
        exit();

    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['icono'] = "error";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
}
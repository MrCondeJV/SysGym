<?php

include('../../config.php');
if (session_status() === PHP_SESSION_NONE) session_start();

try {
    $creado_por = $_SESSION['id_usuario'] ?? null;

    $tipo_documento = trim($_POST['tipo_documento'] ?? '');
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $correo_electronico = trim($_POST['correo_electronico'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $contacto_emergencia_nombre = trim($_POST['contacto_emergencia_nombre'] ?? '');
    $contacto_emergencia_telefono = trim($_POST['contacto_emergencia_telefono'] ?? '');
    $estado = trim($_POST['estado'] ?? 'activo');

    $errores = [];

    // Validaciones básicas
    if (empty($tipo_documento)) $errores[] = "El tipo de documento es obligatorio.";
    if (empty($numero_documento)) $errores[] = "El número de documento es obligatorio.";
    if (empty($nombres)) $errores[] = "El nombre es obligatorio.";
    if (empty($apellidos)) $errores[] = "El apellido es obligatorio.";
    if (empty($fecha_nacimiento)) $errores[] = "La fecha de nacimiento es obligatoria.";
    if (empty($genero)) $errores[] = "El género es obligatorio.";
    if (empty($correo_electronico) || !filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico es obligatorio y debe ser válido.";
    }
    if (empty($telefono)) $errores[] = "El teléfono es obligatorio.";
    if (empty($direccion)) $errores[] = "La dirección es obligatoria.";
    if (empty($contacto_emergencia_nombre)) $errores[] = "El nombre del contacto de emergencia es obligatorio.";
    if (empty($contacto_emergencia_telefono)) $errores[] = "El teléfono del contacto de emergencia es obligatorio.";
    if (empty($creado_por)) $errores[] = "Debe estar logueado para crear un miembro.";

    // Validar que el número de documento sea único
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE tipo_documento = ? AND numero_documento = ?");
    $stmt->execute([$tipo_documento, $numero_documento]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El número de documento ya está registrado para otro miembro.";
    }

    // Manejo de archivo de foto
    $url_foto = null;
    if (isset($_FILES['url_foto']) && $_FILES['url_foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../public/images/miembros/';
        
        // Crear directorio si no existe
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['url_foto']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $errores[] = "Solo se permiten archivos de imagen (JPG, JPEG, PNG, GIF).";
        } else {
            $file_name = 'miembro_' . time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['url_foto']['tmp_name'], $file_path)) {
                $url_foto = '/SysGym/public/images/miembros/' . $file_name;
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        }
    }

    // Si hay errores, mostrarlos y regresar
    if (!empty($errores)) {
        $_SESSION['mensaje'] = implode('<br>', $errores);
        $_SESSION['icono'] = 'error';
        header('Location: ../../views/miembros/create.php');
        exit;
    }

    // Insertar en base de datos
    $sql = "INSERT INTO miembros (
        tipo_documento, numero_documento, nombres, apellidos, fecha_nacimiento, genero, 
        correo_electronico, telefono, direccion, url_foto, contacto_emergencia_nombre, 
        contacto_emergencia_telefono, creado_por, fecha_registro, estado
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $tipo_documento,
        $numero_documento,
        $nombres,
        $apellidos,
        $fecha_nacimiento,
        $genero,
        $correo_electronico,
        $telefono,
        $direccion,
        $url_foto,
        $contacto_emergencia_nombre,
        $contacto_emergencia_telefono,
        $creado_por,
        $estado
    ]);

    $id_miembro = $pdo->lastInsertId();

    $_SESSION['mensaje'] = '¡Miembro registrado exitosamente!';
    $_SESSION['icono'] = 'success';
    header('Location: ../../views/miembros/index.php');
    exit;

} catch (PDOException $e) {
    error_log("Error en create_miembro.php: " . $e->getMessage());
    $_SESSION['mensaje'] = 'Error en la base de datos. Por favor intente nuevamente.';
    $_SESSION['icono'] = 'error';
    header('Location: ../../views/miembros/create.php');
    exit;
} catch (Exception $e) {
    error_log("Error general en create_miembro.php: " . $e->getMessage());
    $_SESSION['mensaje'] = 'Error inesperado. Por favor intente nuevamente.';
    $_SESSION['icono'] = 'error';
    header('Location: ../../views/miembros/create.php');
    exit;
}
?>
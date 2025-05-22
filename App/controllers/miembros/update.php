<?php


include('../../config.php');
session_start();

// Guardar datos del formulario para repoblación en caso de error
$_SESSION['old_data'] = $_POST;

// Obtener datos del formulario
$id_miembro = $_POST['id_miembro'] ?? null;
$tipo_documento = trim($_POST['tipo_documento'] ?? '');
$numero_documento = trim($_POST['numero_documento'] ?? '');

$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$url_foto = $_FILES['url_foto'] ?? null;
$contacto_emergencia_nombre = trim($_POST['contacto_emergencia_nombre'] ?? '');
$contacto_emergencia_telefono = trim($_POST['contacto_emergencia_telefono'] ?? '');
$creado_por = trim($_POST['creado_por'] ?? '');

// Validar ID del miembro
if (!$id_miembro || !is_numeric($id_miembro)) {
    $_SESSION['mensaje'] = "Error: ID de miembro no válido.";
    $_SESSION['icono'] = "error";
    header("Location: $URL/App/views/miembros/index.php");
    exit();
}



// Validar campos obligatorios
$errores = [];
if (empty($nombres)) $errores[] = "El campo 'Nombres' es obligatorio.";
if (empty($apellidos)) $errores[] = "El campo 'Apellidos' es obligatorio.";
if (empty($fecha_nacimiento)) $errores[] = "El campo 'Fecha de Nacimiento' es obligatorio.";
if (empty($genero)) $errores[] = "El campo 'Género' es obligatorio.";
if (empty($correo_electronico)) $errores[] = "El campo 'Correo Electrónico' es obligatorio.";
if (empty($telefono)) $errores[] = "El campo 'Teléfono' es obligatorio.";
if (empty($direccion)) $errores[] = "El campo 'Dirección' es obligatorio.";
if (empty($estado)) $errores[] = "El campo 'Estado' es obligatorio.";
if (empty($contacto_emergencia_nombre)) $errores[] = "El campo 'Nombre Contacto de Emergencia' es obligatorio.";
if (empty($contacto_emergencia_telefono)) $errores[] = "El campo 'Teléfono Contacto de Emergencia' es obligatorio.";
if (empty($creado_por)) $errores[] = "El campo 'Creado por' es obligatorio.";

// Validar formato de correo electrónico
if (!empty($correo_electronico) && !filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo electrónico no es válido.";
}

// Validar formato de teléfono
if (!empty($telefono) && !preg_match('/^[0-9]{7,15}$/', $telefono)) {
    $errores[] = "El teléfono debe contener solo números (7-15 dígitos).";
}
if (!empty($contacto_emergencia_telefono) && !preg_match('/^[0-9]{7,15}$/', $contacto_emergencia_telefono)) {
    $errores[] = "El teléfono de emergencia debe contener solo números (7-15 dígitos).";
}

// Validar que el correo electrónico sea único (excluyendo el miembro actual)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE correo_electronico = ? AND id_miembro != ?");
$stmt->execute([$correo_electronico, $id_miembro]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El correo electrónico ya está en uso.";
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE tipo_documento = ? AND numero_documento = ? AND id_miembro != ?");
$stmt->execute([$tipo_documento, $numero_documento, $id_miembro]);
if ($stmt->fetchColumn() > 0) {
    $errores[] = "El número de documento ya está registrado para otro miembro.";
}

// Manejar la carga de la foto si se subió una nueva
if ($url_foto && $url_foto['error'] === UPLOAD_ERR_OK) {
    $tipo_mime = mime_content_type($url_foto['tmp_name']);
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($tipo_mime, $tipos_permitidos)) {
        $errores[] = "La foto debe ser una imagen válida (JPEG, PNG, GIF).";
    } else {
        $nombre_foto = uniqid('miembro_') . '.' . pathinfo($url_foto['name'], PATHINFO_EXTENSION);
        $ruta_destino = '../../img/miembros/' . $nombre_foto;
        if (!is_dir('../../img/miembros')) {
            mkdir('../../img/miembros', 0777, true);
        }
        if (!move_uploaded_file($url_foto['tmp_name'], $ruta_destino)) {
            $errores[] = "Error al guardar la foto.";
        } else {
            $url_foto_db = 'img/miembros/' . $nombre_foto;
        }
    }
} else {
    // Mantener la foto actual si no se sube una nueva
    $stmt = $pdo->prepare("SELECT url_foto FROM miembros WHERE id_miembro = ?");
    $stmt->execute([$id_miembro]);
    $url_foto_db = $stmt->fetchColumn();
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/miembros/update.php?id=$id_miembro");
    exit();
}

// Construir consulta SQL
$sql = "UPDATE miembros SET 
    tipo_documento = :tipo_documento,
    numero_documento = :numero_documento,
    nombres = :nombres,
    apellidos = :apellidos,
    fecha_nacimiento = :fecha_nacimiento,
    genero = :genero,
    correo_electronico = :correo_electronico,
    telefono = :telefono,
    direccion = :direccion,
    estado = :estado,
    url_foto = :url_foto,
    contacto_emergencia_nombre = :contacto_emergencia_nombre,
    contacto_emergencia_telefono = :contacto_emergencia_telefono,
    creado_por = :creado_por
    WHERE id_miembro = :id_miembro";


$params = [
    ':tipo_documento' => $tipo_documento,
    ':numero_documento' => $numero_documento,
    ':nombres' => $nombres,
    ':apellidos' => $apellidos,
    ':fecha_nacimiento' => $fecha_nacimiento,
    ':genero' => $genero,
    ':correo_electronico' => $correo_electronico,
    ':telefono' => $telefono,
    ':direccion' => $direccion,
    ':estado' => $estado,
    ':url_foto' => $url_foto_db,
    ':contacto_emergencia_nombre' => $contacto_emergencia_nombre,
    ':contacto_emergencia_telefono' => $contacto_emergencia_telefono,
    ':creado_por' => $creado_por,
    ':id_miembro' => $id_miembro
];

// Preparar y ejecutar consulta
try {
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        unset($_SESSION['old_data']);
        $_SESSION['mensaje'] = "Miembro actualizado correctamente.";
        $_SESSION['icono'] = 'success';
        header("Location: $URL/App/views/miembros/index.php");
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el miembro.";
        $_SESSION['icono'] = 'error';
        header("Location: $URL/App/views/miembros/update.php?id=$id_miembro");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = 'error';
    header("Location: $URL/App/views/miembros/update.php?id=$id_miembro");
}
exit();
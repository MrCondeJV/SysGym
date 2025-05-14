<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID de la clase a editar
$id_clase = $_POST['id_clase'] ?? null;

if (!$id_clase || !is_numeric($id_clase)) {
    $_SESSION['mensaje'] = "No se especificó una clase válida para editar.";
    $_SESSION['icono'] = 'error';
    // Asumiendo que la vista de índice de clases está en App/views/clases/index.php
    header('Location: ' . $URL . 'App/views/clases/index.php');
    exit;
}

// Obtener datos actuales de la clase
$stmt = $pdo->prepare("SELECT * FROM clases WHERE id_clase = ?");
$stmt->execute([$id_clase]);
$clase = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$clase) {
    $_SESSION['mensaje'] = "Clase no encontrada.";
    $_SESSION['icono'] = 'error';
    // Asumiendo que la vista de índice de clases está en App/views/clases/index.php
    header('Location: ' . $URL . 'App/views/clases/index.php');
    exit;
}

// Obtener datos del formulario para la tabla clases
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$id_entrenador = trim($_POST['id_entrenador'] ?? '');
$horario = trim($_POST['horario'] ?? '');
$duracion_minutos = trim($_POST['duracion_minutos'] ?? '');
$capacidad_maxima = trim($_POST['capacidad_maxima'] ?? '');
$id_sala = trim($_POST['id_sala'] ?? '');
$dia_semana = trim($_POST['dia_semana'] ?? '');
$precio = trim($_POST['precio'] ?? '');
$nivel = trim($_POST['nivel'] ?? '');
$requisitos = trim($_POST['requisitos'] ?? '');
$id_categoria_clase = trim($_POST['id_categoria_clase'] ?? '');
$cancelada = trim($_POST['cancelada'] ?? '0'); // Asumimos 0 si no se envía (no cancelada)


// Validar campos obligatorios y tipos de datos
$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";
if (empty($id_entrenador) || !is_numeric($id_entrenador)) $errores[] = "El campo 'ID Entrenador' es obligatorio y debe ser numérico.";
if (empty($horario)) $errores[] = "El campo 'Horario' es obligatorio.";
if (empty($duracion_minutos) || !is_numeric($duracion_minutos) || $duracion_minutos <= 0) $errores[] = "El campo 'Duración en Minutos' es obligatorio y debe ser un número positivo.";
if (empty($capacidad_maxima) || !is_numeric($capacidad_maxima) || $capacidad_maxima <= 0) $errores[] = "El campo 'Capacidad Máxima' es obligatorio y debe ser un número positivo.";
if (empty($id_sala) || !is_numeric($id_sala)) $errores[] = "El campo 'ID Sala' es obligatorio y debe ser numérico.";
if (empty($dia_semana)) $errores[] = "El campo 'Día de la Semana' es obligatorio.";
if (empty($precio) || !is_numeric($precio) || $precio < 0) $errores[] = "El campo 'Precio' es obligatorio y debe ser numérico no negativo.";
if (empty($nivel)) $errores[] = "El campo 'Nivel' es obligatorio.";
// Requisitos puede ser opcional, no se valida empty
if (empty($id_categoria_clase) || !is_numeric($id_categoria_clase)) $errores[] = "El campo 'ID Categoría Clase' es obligatorio y debe ser numérico.";
// Cancelada puede ser 0 o 1, se valida si se envía, si no, se asume 0.
if (!in_array($cancelada, ['0', '1'])) $errores[] = "El campo 'Cancelada' debe ser 0 o 1.";


// Si hay errores, redirigir con mensajes
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = "error";
    // Asumiendo que la vista para editar clases está en App/views/clases/update.php
    header('Location: ' . $URL . 'App/views/clases/update.php?id=' . $id_clase);
    exit;
}

// Actualizar clase en la base de datos
$sql = "UPDATE clases SET
    nombre = :nombre,
    descripcion = :descripcion,
    id_entrenador = :id_entrenador,
    horario = :horario,
    duracion_minutos = :duracion_minutos,
    capacidad_maxima = :capacidad_maxima,
    id_sala = :id_sala,
    dia_semana = :dia_semana,
    precio = :precio,
    nivel = :nivel,
    requisitos = :requisitos,
    id_categoria_clase = :id_categoria_clase,
    cancelada = :cancelada
    WHERE id_clase = :id_clase";

$params = [
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':id_entrenador' => $id_entrenador,
    ':horario' => $horario,
    ':duracion_minutos' => $duracion_minutos,
    ':capacidad_maxima' => $capacidad_maxima,
    ':id_sala' => $id_sala,
    ':dia_semana' => $dia_semana,
    ':precio' => $precio,
    ':nivel' => $nivel,
    ':requisitos' => $requisitos,
    ':id_categoria_clase' => $id_categoria_clase,
    ':cancelada' => $cancelada,
    ':id_clase' => $id_clase
];

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    $_SESSION['mensaje'] = "Clase actualizada correctamente.";
    $_SESSION['icono'] = "success";
    // Asumiendo que la vista de índice de clases está en App/views/clases/index.php
    header("Location: $URL/App/views/clases/index.php");
    exit();
} else {
    $_SESSION['mensaje'] = "Error al actualizar la clase.";
    $_SESSION['icono'] = "error";
    // Asumiendo que la vista para editar clases está en App/views/clases/update.php
    header("Location: $URL/App/views/clases/update.php?id=$id_clase");
    exit();
}
?>

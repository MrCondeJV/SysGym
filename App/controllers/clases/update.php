<?php

include('../../config.php');

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta del archivo log
$log_file = __DIR__ . '/errores_clases.log';

// Obtener ID de la clase a editar
$id_clase = $_POST['id_clase'] ?? null;

if (!$id_clase || !is_numeric($id_clase)) {
    $_SESSION['mensaje'] = "No se especificó una clase válida para editar.";
    $_SESSION['icono'] = 'error';

    // Guardar en log
    $msg = "[" . date("Y-m-d H:i:s") . "] ID de clase inválido o no proporcionado.\n";
    error_log($msg, 3, $log_file);

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

    // Guardar en log
    $msg = "[" . date("Y-m-d H:i:s") . "] Clase no encontrada (ID: $id_clase).\n";
    error_log($msg, 3, $log_file);

    header('Location: ' . $URL . 'App/views/clases/index.php');
    exit;
}

// Obtener datos del formulario
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
$cancelada = trim($_POST['cancelada'] ?? '0');

$errores = [];
if (empty($nombre)) $errores[] = "El campo 'Nombre' es obligatorio.";
if (empty($descripcion)) $errores[] = "El campo 'Descripción' es obligatorio.";
if (empty($id_entrenador) || !is_numeric($id_entrenador)) $errores[] = "El campo 'ID Entrenador' es obligatorio y debe ser numérico.";
if (empty($horario)) $errores[] = "El campo 'Horario' es obligatorio.";
if (empty($duracion_minutos) || !is_numeric($duracion_minutos) || $duracion_minutos <= 0) $errores[] = "El campo 'Duración en Minutos' debe ser un número positivo.";
if (empty($capacidad_maxima) || !is_numeric($capacidad_maxima) || $capacidad_maxima <= 0) $errores[] = "El campo 'Capacidad Máxima' debe ser un número positivo.";
if (empty($id_sala) || !is_numeric($id_sala)) $errores[] = "El campo 'ID Sala' debe ser numérico.";
if (empty($dia_semana)) $errores[] = "El campo 'Día de la Semana' es obligatorio.";
if (empty($precio) || !is_numeric($precio) || $precio < 0) $errores[] = "El campo 'Precio' debe ser numérico no negativo.";
if (empty($nivel)) $errores[] = "El campo 'Nivel' es obligatorio.";
if (empty($id_categoria_clase) || !is_numeric($id_categoria_clase)) $errores[] = "El campo 'ID Categoría Clase' debe ser numérico.";
if (!in_array($cancelada, ['0', '1'])) $errores[] = "El campo 'Cancelada' debe ser 0 o 1.";

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['mensaje'] = implode("<br>", $errores);
    $_SESSION['icono'] = "error";

    // Log de errores
    $log_msg = "[" . date("Y-m-d H:i:s") . "] Errores al editar clase (ID $id_clase):\n";
    foreach ($errores as $error) {
        $log_msg .= "- $error\n";
    }
    $log_msg .= "------------------------\n";
    error_log($log_msg, 3, $log_file);

    // Mostrar errores en consola
    echo "<script>";
    foreach ($errores as $error) {
        echo "console.error(" . json_encode($error) . ");";
    }
    echo "window.location.href = '" . $URL . "App/views/clases/update.php?id=$id_clase';";
    echo "</script>";
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
    header("Location: $URL/App/views/clases/index.php");
    exit();
} else {
    $_SESSION['mensaje'] = "Error al actualizar la clase.";
    $_SESSION['icono'] = "error";

    // Capturar errores SQL
    $errorInfo = $stmt->errorInfo();
    $log_msg = "[" . date("Y-m-d H:i:s") . "] Error SQL al actualizar clase (ID $id_clase):\n";
    $log_msg .= "SQLSTATE: {$errorInfo[0]}\n";
    $log_msg .= "Error Code: {$errorInfo[1]}\n";
    $log_msg .= "Mensaje: {$errorInfo[2]}\n";
    $log_msg .= "------------------------\n";
    error_log($log_msg, 3, $log_file);

    // Mostrar errores en consola
    echo "<script>";
    echo "console.error('Error al actualizar la base de datos');";
    echo "console.error(" . json_encode($errorInfo) . ");";
    echo "window.location.href = '$URL/App/views/clases/update.php?id=$id_clase';";
    echo "</script>";
    exit();
}
?>

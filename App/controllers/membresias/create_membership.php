<?php
session_start();
require_once '../../config.php'; // Ajusta la ruta a tu conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    $id_tipo_membresia = isset($_POST['id_tipo_membresia']) ? intval($_POST['id_tipo_membresia']) : 0;

    if ($id_miembro <= 0 || $id_tipo_membresia <= 0) {
        $_SESSION['error'] = "Datos inválidos.";
        header("Location: ../../views/membresias/create.php");
        exit();
    }

    try {
        // Verificar existencia de miembro
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM miembros WHERE id_miembro = ?");
        $stmt->execute([$id_miembro]);
        if ($stmt->fetchColumn() == 0) {
            $_SESSION['error'] = "El miembro seleccionado no existe.";
            header("Location: ../../views/membresias/create.php");
            exit();
        }

        // Obtener duracion_dias de tipo membresia
        $stmt = $pdo->prepare("SELECT duracion_dias FROM tiposmembresia WHERE id_tipo_membresia = ?");
        $stmt->execute([$id_tipo_membresia]);
        $duracion = $stmt->fetchColumn();

        if (!$duracion) {
            $_SESSION['error'] = "Tipo de membresía no válido.";
            header("Location: ../../views/membresias/create.php");
            exit();
        }

        // Calcular fechas
        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime("+$duracion days"));

        // Insertar membresía
        $insert = $pdo->prepare("INSERT INTO membresias (id_miembro, id_tipo_membresia, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $insert->execute([$id_miembro, $id_tipo_membresia, $fecha_inicio, $fecha_fin]);

        $_SESSION['success'] = "Membresía registrada correctamente.";
        header("Location: ../../views/membresias/index.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al registrar la membresía: " . $e->getMessage();
        header("Location: ../../views/membresias/create.php");
        exit();
    }
} else {
    // Método no permitido
    http_response_code(405);
    echo "Método no permitido";
    exit();
}
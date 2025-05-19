<?php
session_start();
require_once '../../config.php'; // Ajusta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_membresia = isset($_POST['id_membresia']) ? intval($_POST['id_membresia']) : 0;

    if ($id_membresia <= 0) {
        $_SESSION['mensaje'] = "ID de membresía inválido.";
        $_SESSION['icono'] = "error";
        header("Location: ../../views/membresias/index.php");
        exit();
    }

    // Función para renovar membresía
    function renovarMembresia($pdo, $id_membresia, $diasReserva = 5)
    {
        // Obtener datos de la membresía y duración
        $stmt = $pdo->prepare("
            SELECT m.id_miembro, m.id_tipo_membresia, m.fecha_fin, t.duracion_dias 
            FROM membresias m
            JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
            WHERE m.id_membresia = ?
            ORDER BY m.fecha_fin DESC
            LIMIT 1
        ");
        $stmt->execute([$id_membresia]);
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$membresia) {
            return "Membresía no encontrada.";
        }

        $hoy = new DateTime();
        $fechaFin = new DateTime($membresia['fecha_fin']);
        $duracion = intval($membresia['duracion_dias']);

        $intervalo = $hoy->diff($fechaFin);
        $diasFaltantes = (int)$intervalo->format('%r%a'); // negativo si ya venció

        if ($diasFaltantes > 0 && $diasFaltantes <= $diasReserva) {
            // Renovación anticipada con reserva de días restantes
            $fechaInicioNueva = clone $fechaFin;
            $nuevaDuracion = $duracion + $diasFaltantes;
        } elseif ($diasFaltantes <= 0) {
            // Renovación después de vencido, sin reserva de días
            $fechaInicioNueva = $hoy;
            $nuevaDuracion = $duracion;
        } else {
            return "No es tiempo para renovar aún. Faltan $diasFaltantes días.";
        }

        $fechaFinNueva = clone $fechaInicioNueva;
        $fechaFinNueva->modify("+$nuevaDuracion days");

        // Insertar nueva membresía
        $insert = $pdo->prepare("
            INSERT INTO membresias (id_miembro, id_tipo_membresia, fecha_inicio, fecha_fin) 
            VALUES (?, ?, ?, ?)
        ");

        $insert->execute([
            $membresia['id_miembro'],
            $membresia['id_tipo_membresia'],
            $fechaInicioNueva->format('Y-m-d'),
            $fechaFinNueva->format('Y-m-d')
        ]);

        return "Membresía renovada desde " . $fechaInicioNueva->format('d/m/Y') . " hasta " . $fechaFinNueva->format('d/m/Y') . ".";
    }

    try {
        $mensaje = renovarMembresia($pdo, $id_membresia);

        if (str_contains($mensaje, 'renovada')) {
            $_SESSION['mensaje'] = $mensaje;
            $_SESSION['icono'] = "success";
        } else {
            $_SESSION['mensaje'] = $mensaje;
            $_SESSION['icono'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en la renovación: " . $e->getMessage();
        $_SESSION['icono'] = "error";
    }

    header("Location: ../../views/membresias/index.php");
    exit();
} else {
    http_response_code(405);
    echo "Método no permitido";
    exit();
}
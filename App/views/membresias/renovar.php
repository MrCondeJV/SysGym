<?php
session_start();
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_membresia = isset($_POST['id_membresia']) ? intval($_POST['id_membresia']) : 0;
    $numero_factura = isset($_POST['numero_factura']) ? trim($_POST['numero_factura']) : '';
    $metodo_pago = isset($_POST['metodo_pago']) ? intval($_POST['metodo_pago']) : 0;
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;

    if ($id_membresia <= 0 || !$numero_factura || $metodo_pago <= 0) {
        $_SESSION['mensaje'] = "Datos incompletos para la renovación.";
        $_SESSION['icono'] = "error";
        header("Location: ../../views/membresias/index.php");
        exit();
    }

    function renovarMembresia($pdo, $id_membresia, $diasReserva = 15)
    {
        $stmt = $pdo->prepare("
            SELECT m.id_miembro, m.id_tipo_membresia, m.fecha_inicio, m.fecha_fin, t.duracion_dias 
            FROM membresias m
            JOIN tiposmembresia t ON m.id_tipo_membresia = t.id_tipo_membresia
            WHERE m.id_membresia = ?
            LIMIT 1
        ");
        $stmt->execute([$id_membresia]);
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$membresia) {
            return [false, "Membresía no encontrada.", null, null];
        }

        $hoy = new DateTime();
        $fechaFin = new DateTime($membresia['fecha_fin']);
        $duracion = intval($membresia['duracion_dias']);

        $intervalo = $hoy->diff($fechaFin);
        $diasFaltantes = (int)$intervalo->format('%r%a');

        if ($diasFaltantes > 0 && $diasFaltantes <= $diasReserva) {
            $fechaInicioNueva = clone $fechaFin;
            $nuevaDuracion = $duracion + $diasFaltantes;
        } elseif ($diasFaltantes <= 0) {
            $fechaInicioNueva = $hoy;
            $nuevaDuracion = $duracion;
        } else {
            return [false, "No es tiempo para renovar aún. Faltan $diasFaltantes días.", null, null];
        }

        $fechaFinNueva = clone $fechaInicioNueva;
        $fechaFinNueva->modify("+$nuevaDuracion days");

        // ACTUALIZAR la membresía existente
        $update = $pdo->prepare("
            UPDATE membresias 
            SET fecha_inicio = ?, fecha_fin = ?
            WHERE id_membresia = ?
        ");
        $update->execute([
            $fechaInicioNueva->format('Y-m-d'),
            $fechaFinNueva->format('Y-m-d'),
            $id_membresia
        ]);

        return [true, "Membresía renovada desde " . $fechaInicioNueva->format('d/m/Y') . " hasta " . $fechaFinNueva->format('d/m/Y') . ".", $id_membresia, $membresia['id_miembro']];
    }

    try {
        list($exito, $mensaje, $id_membresia_actualizada, $id_miembro) = renovarMembresia($pdo, $id_membresia);

        if ($exito) {
            $stmt = $pdo->prepare("INSERT INTO renovaciones (id_membresia, id_miembro, numero_factura, id_metodo_pago, observaciones) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_membresia_actualizada,
                $id_miembro,
                $numero_factura,
                $metodo_pago,
                $observaciones
            ]);
            $id_renovacion = $pdo->lastInsertId(); // <--- OBTIENE EL ID DEL TICKET
            header("Location: ticket_renovacion.php?id_renovacion=" . $id_renovacion); // <--- REDIRECCIÓN AL TICKET
            exit();
        } else {
            $_SESSION['mensaje'] = $mensaje;
            $_SESSION['icono'] = "error";
            header("Location: ../../views/membresias/index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en la renovación: " . $e->getMessage();
        $_SESSION['icono'] = "error";
    }

    header("Location: ../../views/membresias/index.php");
    exit();
}
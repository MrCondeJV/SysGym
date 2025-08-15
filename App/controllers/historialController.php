<?php
require_once __DIR__ . '/../config.php';

class HistorialController {
    private $pdo;

    public function __construct($pdo = null) {
        global $pdo;
        $this->pdo = $pdo ?: $pdo;
    }

    // Listar todo el historial de cambios de estado de reservas (con datos de solicitud, usuario, estado)
    public function listar($filtros = []) {
        $sql = "SELECT h.*, 
                       e1.nombre AS estado_anterior_nombre, 
                       e2.nombre AS estado_nuevo_nombre,
                       u.nombre_completo AS usuario_nombre,
                       r.codigo_reserva,
                       r.id_solicitud,
                       s.titulo AS solicitud_titulo
                FROM historial_estado_reserva h
                LEFT JOIN estados e1 ON h.estado_anterior = e1.codigo
                LEFT JOIN estados e2 ON h.estado_nuevo = e2.codigo
                LEFT JOIN usuarios u ON h.cambiado_por = u.id
                LEFT JOIN reservas r ON h.id_reserva = r.id
                LEFT JOIN solicitudes s ON r.id_solicitud = s.id
                WHERE 1=1";
        $params = [];
        if (!empty($filtros['id_solicitud'])) {
            $sql .= " AND s.id = ?";
            $params[] = $filtros['id_solicitud'];
        }
        if (!empty($filtros['id_reserva'])) {
            $sql .= " AND r.id = ?";
            $params[] = $filtros['id_reserva'];
        }
        if (!empty($filtros['usuario'])) {
            $sql .= " AND u.id = ?";
            $params[] = $filtros['usuario'];
        }
        $sql .= " ORDER BY h.cambiado_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Obtener detalle de un registro de historial por su ID
    public function obtener($id) {
        $sql = "SELECT h.*, 
                       e1.nombre AS estado_anterior_nombre, 
                       e2.nombre AS estado_nuevo_nombre,
                       u.nombre_completo AS usuario_nombre,
                       r.codigo_reserva,
                       r.id_solicitud,
                       s.titulo AS solicitud_titulo,
                       sol.nombre_completo AS solicitante_nombre,
                       sol.unidad_o_area AS solicitante_unidad,
                       sol.nro_documento AS solicitante_documento,
                       adj.nombre_archivo AS adjunto_nombre,
                       adj.subido_en AS adjunto_fecha
                FROM historial_estado_reserva h
                LEFT JOIN estados e1 ON h.estado_anterior = e1.codigo
                LEFT JOIN estados e2 ON h.estado_nuevo = e2.codigo
                LEFT JOIN usuarios u ON h.cambiado_por = u.id
                LEFT JOIN reservas r ON h.id_reserva = r.id
                LEFT JOIN solicitudes s ON r.id_solicitud = s.id
                LEFT JOIN solicitantes sol ON s.id_solicitante = sol.id
                LEFT JOIN adjuntos adj ON adj.id_solicitud = s.id
                WHERE h.id = ?
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
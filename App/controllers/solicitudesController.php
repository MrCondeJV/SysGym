<?php
require_once __DIR__ . '/../config.php';

class SolicitudesController {
    // Listar reservas para el calendario (solo aprobadas)
    public function listarReservasParaCalendario() {
        $sql = "SELECT r.id, r.titulo, r.descripcion, r.fecha_inicio, r.fecha_fin, r.estado, a.nombre AS aula, u.nombre_completo AS usuario
                FROM reservas r
                LEFT JOIN aulas a ON r.id_aula = a.id
                LEFT JOIN usuarios u ON r.creado_por = u.id
                WHERE r.estado = 'aprobada' 
                ORDER BY r.fecha_inicio ASC";
        $stmt = $this->pdo->query($sql);
        $eventos = [];
        while ($row = $stmt->fetch()) {
            $eventos[] = [
                'id' => $row['id'],
                'title' => $row['titulo'],
                'start' => $row['fecha_inicio'],
                'end' => $row['fecha_fin'],
                'description' => $row['descripcion'],
                'aula' => $row['aula'],
                'usuario' => $row['usuario'],
                'estado' => $row['estado']
            ];
        }
        return $eventos;
    }
    private $pdo;

    public function __construct($pdo = null) {
        global $pdo;
        $this->pdo = $pdo ?: $pdo;
    }

    // Listar todas las solicitudes (con filtros opcionales)
    public function listar($filtros = []) {
    $sql = "SELECT s.*, e.nombre AS estado_nombre, a.nombre AS aula_nombre, u.nombre_completo AS usuario_nombre, d.nombre AS dependencia_nombre
        FROM solicitudes s
        LEFT JOIN estados e ON s.estado = e.codigo
        LEFT JOIN aulas a ON s.id_aula_deseada = a.id
        LEFT JOIN usuarios u ON s.id_usuario_creador = u.id
        LEFT JOIN dependencias d ON u.id_dependencia = d.id
        WHERE 1=1";
        $params = [];
        if (!empty($filtros['estado'])) {
            $sql .= " AND s.estado = ?";
            $params[] = $filtros['estado'];
        }
        if (!empty($filtros['usuario'])) {
            $sql .= " AND s.id_usuario_creador = ?";
            $params[] = $filtros['usuario'];
        }
        if (!empty($filtros['aula'])) {
            $sql .= " AND s.id_aula_deseada = ?";
            $params[] = $filtros['aula'];
        }
    $sql .= " ORDER BY s.creado_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Obtener una solicitud por ID
    public function obtener($id) {
    $sql = "SELECT s.*, e.nombre AS estado_nombre, a.nombre AS aula_nombre, u.nombre_completo AS usuario_nombre, d.nombre AS dependencia_nombre
        FROM solicitudes s
        LEFT JOIN estados e ON s.estado = e.codigo
        LEFT JOIN aulas a ON s.id_aula_deseada = a.id
        LEFT JOIN usuarios u ON s.id_usuario_creador = u.id
        LEFT JOIN dependencias d ON u.id_dependencia = d.id
        WHERE s.id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear una nueva solicitud
    public function crear($data) {
        $sql = "INSERT INTO solicitudes (id_solicitante, id_usuario_creador, creado_en, titulo, descripcion, asistentes, id_aula_deseada, fecha_inicio, fecha_fin, estado) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['id_solicitante'],
            $data['id_usuario_creador'],
            $data['titulo'],
            $data['descripcion'],
            $data['asistentes'],
            $data['id_aula_deseada'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['estado'] ?? 1 // Estado por defecto: solicitada
        ];
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }

    // Actualizar solicitud
    public function actualizar($id, $data) {
        $campos = [];
        $params = [];
    if (isset($data['id_aula_deseada'])) { $campos[] = 'id_aula_deseada=?'; $params[] = $data['id_aula_deseada']; }
    if (isset($data['fecha_inicio'])) { $campos[] = 'fecha_inicio=?'; $params[] = $data['fecha_inicio']; }
    if (isset($data['fecha_fin'])) { $campos[] = 'fecha_fin=?'; $params[] = $data['fecha_fin']; }
    if (isset($data['titulo'])) { $campos[] = 'titulo=?'; $params[] = $data['titulo']; }
    if (isset($data['descripcion'])) { $campos[] = 'descripcion=?'; $params[] = $data['descripcion']; }
    if (isset($data['asistentes'])) { $campos[] = 'asistentes=?'; $params[] = $data['asistentes']; }
    if (isset($data['estado'])) { $campos[] = 'estado=?'; $params[] = $data['estado']; }
        if (!$campos) return false;
        $sql = "UPDATE solicitudes SET ".implode(',', $campos).", actualizado_en=NOW() WHERE id=?";
        $params[] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Eliminar solicitud (borrado lógico)
    public function eliminar($id) {
        $sql = "UPDATE solicitudes SET eliminado=1, actualizado_en=NOW() WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Listar estados posibles
    public function listarEstados() {
    $stmt = $this->pdo->query("SELECT * FROM estados ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    // Listar aulas disponibles
    public function listarAulas() {
        $stmt = $this->pdo->query("SELECT * FROM aulas ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    // Validar solapamiento de reservas y bloqueos
    public function validarDisponibilidad($id_aula, $fecha_inicio, $fecha_fin) {
        // Verifica reservas
        $sql = "SELECT COUNT(*) FROM reservas WHERE id_aula = ? AND (
                    (fecha_inicio < ? AND fecha_fin > ?) OR
                    (fecha_inicio < ? AND fecha_fin > ?) OR
                    (fecha_inicio >= ? AND fecha_fin <= ?)
                ) AND id_estado IN (SELECT id FROM estados WHERE nombre IN ('aprobada','solicitada'))";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_aula, $fecha_fin, $fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_inicio, $fecha_fin]);
        $reservas = $stmt->fetchColumn();
        // Verifica bloqueos
        $sql2 = "SELECT COUNT(*) FROM bloqueos_aula WHERE id_aula = ? AND (
                    (fecha_inicio < ? AND fecha_fin > ?) OR
                    (fecha_inicio < ? AND fecha_fin > ?) OR
                    (fecha_inicio >= ? AND fecha_fin <= ?)
                )";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$id_aula, $fecha_fin, $fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_inicio, $fecha_fin]);
        $bloqueos = $stmt2->fetchColumn();
        return ($reservas == 0 && $bloqueos == 0);
    }

    // Historial de cambios de estado (por solicitud)
    public function historialEstados($id_solicitud) {
        // Buscar reservas asociadas a la solicitud
        $sql_res = "SELECT id FROM reservas WHERE id_solicitud = ?";
        $stmt_res = $this->pdo->prepare($sql_res);
        $stmt_res->execute([$id_solicitud]);
        $reservas = $stmt_res->fetchAll(PDO::FETCH_COLUMN);
        if (!$reservas || count($reservas) === 0) {
            return [];
        }
        // Buscar historial para todas las reservas asociadas
        $in = str_repeat('?,', count($reservas) - 1) . '?';
        $sql = "SELECT h.*, e.nombre AS estado_nombre, u.nombre_completo AS usuario_nombre
                FROM historial_estado_reserva h
                LEFT JOIN estados e ON h.estado_nuevo = e.codigo
                LEFT JOIN usuarios u ON h.cambiado_por = u.id
                WHERE h.id_reserva IN ($in) ORDER BY h.cambiado_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($reservas);
        return $stmt->fetchAll();
    }

    // Adjuntos de la solicitud
    public function listarAdjuntos($id_solicitud) {
        $sql = "SELECT * FROM adjuntos WHERE id_solicitud = ? ORDER BY subido_en DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_solicitud]);
        return $stmt->fetchAll();
    }

    // Aprobar solicitud
    public function aprobar($id_solicitud, $id_usuario, $comentario = null) {
        // Cambia el estado de la solicitud a 'aprobada'
        $sql = "UPDATE solicitudes SET estado = 'aprobada' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_solicitud]);

        // Buscar reserva asociada
        $reserva = $this->pdo->query("SELECT id FROM reservas WHERE id_solicitud = $id_solicitud LIMIT 1")->fetch();
        if (!$reserva) {
            // Obtener datos de la solicitud para crear la reserva
            $solicitud = $this->obtener($id_solicitud);
            if ($solicitud) {
                $sqlReserva = "INSERT INTO reservas (codigo_reserva, id_solicitud, id_aula, titulo, descripcion, asistentes, fecha_inicio, fecha_fin, estado, creado_por, creado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $codigo = 'RES-' . strtoupper(uniqid());
                $params = [
                    $codigo,
                    $id_solicitud,
                    $solicitud['id_aula_deseada'],
                    $solicitud['titulo'],
                    $solicitud['descripcion'],
                    $solicitud['asistentes'],
                    $solicitud['fecha_inicio'],
                    $solicitud['fecha_fin'],
                    'aprobada',
                    $id_usuario
                ];
                $stmtRes = $this->pdo->prepare($sqlReserva);
                $stmtRes->execute($params);
                $id_reserva = $this->pdo->lastInsertId();
            } else {
                return false;
            }
        } else {
            $id_reserva = $reserva['id'];
        }
        // Registrar en historial
        $this->registrarHistorial($id_reserva, 'aprobada', $id_usuario, $comentario);
        return true;
    }

    // Rechazar solicitud
    public function rechazar($id_solicitud, $id_usuario, $comentario = null) {
    $sql = "UPDATE solicitudes SET estado = 'rechazada' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_solicitud]);
        $reserva = $this->pdo->query("SELECT id FROM reservas WHERE id_solicitud = $id_solicitud LIMIT 1")->fetch();
        if ($reserva) {
            $this->registrarHistorial($reserva['id'], 'rechazada', $id_usuario, $comentario);
        }
        return true;
    }

    // Método auxiliar para registrar en historial
    private function registrarHistorial($id_reserva, $estado_nuevo, $id_usuario, $comentario = null) {
        // Busca el estado anterior
        $ultimo = $this->pdo->query("SELECT estado_nuevo FROM historial_estado_reserva WHERE id_reserva = $id_reserva ORDER BY cambiado_en DESC LIMIT 1")->fetch();
        $estado_anterior = $ultimo ? $ultimo['estado_nuevo'] : null;
        $sql = "INSERT INTO historial_estado_reserva (id_reserva, estado_anterior, estado_nuevo, cambiado_por, cambiado_en, comentario)
                VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_reserva, $estado_anterior, $estado_nuevo, $id_usuario, $comentario]);
    }
}

<?php
require_once __DIR__ . '/../config.php';

class UsuariosController {
	private $pdo;

	public function __construct($pdo = null) {
		global $pdo;
		$this->pdo = $pdo ?: $pdo;
	}

	// Listar todos los usuarios activos/inactivos
	public function listar($soloActivos = false) {
		$sql = "SELECT u.*, r.nombre AS rol, d.nombre AS dependencia FROM usuarios u
				LEFT JOIN roles r ON u.id_rol = r.id
				LEFT JOIN dependencias d ON u.id_dependencia = d.id";
		if ($soloActivos) {
			$sql .= " WHERE u.activo = 1";
		}
		$sql .= " ORDER BY u.nombre_completo ASC";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll();
	}

	// Obtener un usuario por ID
	public function obtener($id) {
		$sql = "SELECT u.*, r.nombre AS rol, d.nombre AS dependencia FROM usuarios u
			LEFT JOIN roles r ON u.id_rol = r.id
			LEFT JOIN dependencias d ON u.id_dependencia = d.id
			WHERE u.id = ? LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch();
	}

	// Crear usuario
	public function crear($data) {
		$sql = "INSERT INTO usuarios (nombre_completo, usuario, clave_hash, correo, telefono, id_rol, id_dependencia, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$clave_hash = password_hash($data['clave'], PASSWORD_DEFAULT);
		$params = [
			$data['nombre_completo'],
			$data['usuario'],
			$clave_hash,
			$data['correo'],
			$data['telefono'],
			$data['id_rol'],
			$data['id_dependencia'],
			$data['activo']
		];
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	// Actualizar usuario
	public function actualizar($id, $data) {
		$campos = [];
		$params = [];
		if (isset($data['nombre_completo'])) { $campos[] = 'nombre_completo=?'; $params[] = $data['nombre_completo']; }
		if (isset($data['usuario'])) { $campos[] = 'usuario=?'; $params[] = $data['usuario']; }
		if (isset($data['correo'])) { $campos[] = 'correo=?'; $params[] = $data['correo']; }
		if (isset($data['telefono'])) { $campos[] = 'telefono=?'; $params[] = $data['telefono']; }
		if (isset($data['id_rol'])) { $campos[] = 'id_rol=?'; $params[] = $data['id_rol']; }
		if (isset($data['id_dependencia'])) { $campos[] = 'id_dependencia=?'; $params[] = $data['id_dependencia']; }
		if (isset($data['activo'])) { $campos[] = 'activo=?'; $params[] = $data['activo']; }
		if (isset($data['clave']) && $data['clave']) { $campos[] = 'clave_hash=?'; $params[] = password_hash($data['clave'], PASSWORD_DEFAULT); }
		if (!$campos) return false;
		$sql = "UPDATE usuarios SET ".implode(',', $campos).", actualizado_en=NOW() WHERE id=?";
		$params[] = $id;
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	// Eliminar usuario (borrado lógico)
	public function eliminar($id) {
		$sql = "UPDATE usuarios SET activo=0, actualizado_en=NOW() WHERE id=?";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([$id]);
	}

	// Restaurar usuario (activar)
	public function restaurar($id) {
		$sql = "UPDATE usuarios SET activo=1, actualizado_en=NOW() WHERE id=?";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([$id]);
	}

	// Buscar usuarios por nombre, usuario o correo
	public function buscar($termino) {
		$like = "%{$termino}%";
		$sql = "SELECT u.*, r.nombre AS rol, d.nombre AS dependencia FROM usuarios u
				LEFT JOIN roles r ON u.id_rol = r.id
				LEFT JOIN dependencias d ON u.id_dependencia = d.id
				WHERE u.nombre_completo LIKE ? OR u.usuario LIKE ? OR u.correo LIKE ?
				ORDER BY u.nombre_completo ASC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$like, $like, $like]);
		return $stmt->fetchAll();
	}

	// Cambiar contraseña
	public function cambiarClave($id, $nuevaClave) {
		$hash = password_hash($nuevaClave, PASSWORD_DEFAULT);
		$sql = "UPDATE usuarios SET clave_hash=?, actualizado_en=NOW() WHERE id=?";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([$hash, $id]);
	}

	// Listar roles disponibles
	public function listarRoles() {
		$stmt = $this->pdo->query("SELECT * FROM roles ORDER BY nombre ASC");
		return $stmt->fetchAll();
	}

	// Listar dependencias disponibles
	public function listarDependencias() {
		$stmt = $this->pdo->query("SELECT * FROM dependencias ORDER BY nombre ASC");
		return $stmt->fetchAll();
	}
	// Desactivar usuario (alias de eliminar)
	public function desactivar($id) {
		return $this->eliminar($id);
	}

	// Reactivar usuario (alias de restaurar)
	public function reactivar($id) {
		return $this->restaurar($id);
	}
}

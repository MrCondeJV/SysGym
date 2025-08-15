
-- =============================================================
-- Esquema robusto para "Préstamo de Aulas" (MariaDB 10.11+)
-- Incluye: entidades principales, flujo de aprobaciones, adjuntos,
-- bloqueos, características, auditoría, notificaciones, recurrencia,
-- restricciones e índices, además de migración guiada desde la BD actual.
-- =============================================================

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */;
SET NAMES utf8mb4 COLLATE utf8mb4_general_ci;

-- Crear base de datos (opcional)
-- CREATE DATABASE IF NOT EXISTS prestamos_aulas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE prestamos_aulas;

-- ===============
-- 1) Catálogos
-- ===============

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS dependencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ===============
-- 2) Personas y Usuarios
-- ===============

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(150) NOT NULL,
  usuario VARCHAR(120) NOT NULL UNIQUE,
  clave_hash VARCHAR(255) NOT NULL,
  correo VARCHAR(150) NULL,
  telefono VARCHAR(30) NULL,
  id_rol INT NOT NULL,
  id_dependencia INT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_rol FOREIGN KEY (id_rol) REFERENCES roles(id) ON UPDATE CASCADE,
  CONSTRAINT fk_usuarios_dependencia FOREIGN KEY (id_dependencia) REFERENCES dependencias(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Solicitantes externos (sin cuenta de usuario)
CREATE TABLE IF NOT EXISTS solicitantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nro_documento VARCHAR(30) NULL,
  nombre_completo VARCHAR(150) NOT NULL,
  unidad_o_area VARCHAR(100) NULL,
  correo VARCHAR(150) NULL,
  telefono VARCHAR(30) NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===============
-- 3) Aulas y Recursos
-- ===============

CREATE TABLE IF NOT EXISTS edificios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS aulas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) NULL,
  nombre VARCHAR(120) NOT NULL,
  id_edificio INT NULL,
  capacidad INT NULL,
  tipo ENUM('Aula','Auditorio','Biblioteca','Sala','Otro') NOT NULL DEFAULT 'Aula',
  activa TINYINT(1) NOT NULL DEFAULT 1,
  observaciones VARCHAR(500) NULL,
  UNIQUE KEY uq_aula_nombre_edificio (nombre, id_edificio),
  CONSTRAINT fk_aulas_edificio FOREIGN KEY (id_edificio) REFERENCES edificios(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS caracteristicas_aula (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS aula_caracteristica (
  id_aula INT NOT NULL,
  id_caracteristica INT NOT NULL,
  PRIMARY KEY (id_aula, id_caracteristica),
  CONSTRAINT fk_ac_aula FOREIGN KEY (id_aula) REFERENCES aulas(id) ON DELETE CASCADE,
  CONSTRAINT fk_ac_caracteristica FOREIGN KEY (id_caracteristica) REFERENCES caracteristicas_aula(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Bloqueos / mantenimientos / eventos que impiden reservas
CREATE TABLE IF NOT EXISTS bloqueos_aula (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_aula INT NOT NULL,
  inicia DATETIME NOT NULL,
  termina DATETIME NOT NULL,
  motivo VARCHAR(200) NULL,
  creado_por INT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ba_aula FOREIGN KEY (id_aula) REFERENCES aulas(id) ON DELETE CASCADE,
  CONSTRAINT fk_ba_usuario FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===============
-- 4) Solicitudes y Reservas
-- ===============

CREATE TABLE IF NOT EXISTS estados (
  codigo VARCHAR(20) PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO estados (codigo, nombre) VALUES
('borrador','Borrador'),
('solicitada','Solicitada'),
('aprobada','Aprobada'),
('rechazada','Rechazada'),
('cancelada','Cancelada');

CREATE TABLE IF NOT EXISTS solicitudes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_solicitante INT NULL,
  id_usuario_creador INT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  titulo VARCHAR(200) NOT NULL,
  descripcion VARCHAR(800) NULL,
  asistentes INT NULL,
  id_aula_deseada INT NULL,
  fecha_inicio DATETIME NOT NULL,
  fecha_fin DATETIME NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'solicitada',
  CONSTRAINT fk_sol_solicitante FOREIGN KEY (id_solicitante) REFERENCES solicitantes(id) ON DELETE SET NULL,
  CONSTRAINT fk_sol_usuario FOREIGN KEY (id_usuario_creador) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_sol_aula FOREIGN KEY (id_aula_deseada) REFERENCES aulas(id) ON DELETE SET NULL,
  CONSTRAINT fk_sol_estado FOREIGN KEY (estado) REFERENCES estados(codigo) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_reserva VARCHAR(20) NOT NULL UNIQUE,
  id_solicitud INT NULL,
  id_aula INT NOT NULL,
  titulo VARCHAR(200) NOT NULL,
  descripcion VARCHAR(800) NULL,
  asistentes INT NULL,
  fecha_inicio DATETIME NOT NULL,
  fecha_fin DATETIME NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'solicitada',
  creado_por INT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_res_sol FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id) ON DELETE SET NULL,
  CONSTRAINT fk_res_aula FOREIGN KEY (id_aula) REFERENCES aulas(id) ON DELETE RESTRICT,
  CONSTRAINT fk_res_estado FOREIGN KEY (estado) REFERENCES estados(codigo) ON UPDATE CASCADE,
  CONSTRAINT fk_res_usuario FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_reserva_aula_fecha ON reservas (id_aula, fecha_inicio, fecha_fin);
CREATE INDEX idx_solicitud_fecha ON solicitudes (fecha_inicio, fecha_fin);

-- Evitar solapes
DELIMITER //
CREATE TRIGGER trg_no_solape_reserva_ins
BEFORE INSERT ON reservas
FOR EACH ROW
BEGIN
  IF NEW.fecha_fin <= NEW.fecha_inicio THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='La fecha de fin debe ser mayor a la de inicio';
  END IF;

  IF EXISTS (
    SELECT 1 FROM reservas r
    WHERE r.id_aula = NEW.id_aula
      AND r.estado IN ('solicitada','aprobada')
      AND NEW.fecha_fin > r.fecha_inicio
      AND NEW.fecha_inicio < r.fecha_fin
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Conflicto: ya existe una reserva en ese horario';
  END IF;

  IF EXISTS (
    SELECT 1 FROM bloqueos_aula b
    WHERE b.id_aula = NEW.id_aula
      AND NEW.fecha_fin > b.inicia
      AND NEW.fecha_inicio < b.termina
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='El aula está bloqueada en ese horario';
  END IF;
END//
DELIMITER ;

-- Tabla de aprobaciones
CREATE TABLE IF NOT EXISTS aprobaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_reserva INT NOT NULL,
  id_aprobador INT NOT NULL,
  decision ENUM('aprobada','rechazada') NOT NULL,
  fecha_decision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  comentario VARCHAR(500) NULL,
  CONSTRAINT fk_aprob_res FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE,
  CONSTRAINT fk_aprob_usuario FOREIGN KEY (id_aprobador) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Historial de cambios de estado
CREATE TABLE IF NOT EXISTS historial_estado_reserva (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_reserva INT NOT NULL,
  estado_anterior VARCHAR(20) NULL,
  estado_nuevo VARCHAR(20) NOT NULL,
  cambiado_por INT NULL,
  cambiado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  comentario VARCHAR(500) NULL,
  CONSTRAINT fk_hist_res FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE,
  CONSTRAINT fk_hist_usuario FOREIGN KEY (cambiado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_hist_estado FOREIGN KEY (estado_nuevo) REFERENCES estados(codigo)
) ENGINE=InnoDB;

-- Adjuntos (PDF, imágenes)
CREATE TABLE IF NOT EXISTS adjuntos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_solicitud INT NULL,
  id_reserva INT NULL,
  nombre_archivo VARCHAR(255) NOT NULL,
  tipo_mime VARCHAR(100) NULL,
  datos LONGBLOB NOT NULL,
  subido_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  subido_por INT NULL,
  CONSTRAINT fk_adj_sol FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id) ON DELETE CASCADE,
  CONSTRAINT fk_adj_res FOREIGN KEY (id_reserva) REFERENCES reservas(id) ON DELETE CASCADE,
  CONSTRAINT fk_adj_usuario FOREIGN KEY (subido_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Auditoría
CREATE TABLE IF NOT EXISTS auditoria (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NULL,
  accion VARCHAR(60) NOT NULL,
  entidad VARCHAR(60) NOT NULL,
  id_entidad BIGINT NULL,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  detalles JSON NULL,
  CONSTRAINT fk_aud_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2025 a las 13:46:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mambacodegym`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `tabla_afectada` varchar(50) NOT NULL,
  `id_registro` int(11) NOT NULL,
  `accion` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `tabla_afectada`, `id_registro`, `accion`, `datos_anteriores`, `datos_nuevos`, `fecha_cambio`, `id_usuario`) VALUES
(1, 'miembros', 2, 'INSERT', NULL, '{\"nombres\": \"Luis Fernando\", \"apellidos\": \"Barrios\", \"correo_electronico\": \"luifer@gmail.com\"}', '2025-05-15 20:35:46', 3),
(2, 'miembros', 2, 'UPDATE', '{\"nombres\": \"Luis Fernando\", \"apellidos\": \"Barrios\", \"correo_electronico\": \"luifer@gmail.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Luis Fernando Mod\", \"apellidos\": \"Barrios 1\", \"correo_electronico\": \"luifer@gmail.com\", \"estado\": \"activo\"}', '2025-05-15 20:39:11', 3),
(3, 'miembros', 4, 'INSERT', NULL, '{\"nombres\": \"Cristian Fernando\", \"apellidos\": \"Dijin\", \"correo_electronico\": \"luifer1@gmail.com\"}', '2025-05-15 20:41:03', 3),
(4, 'miembros', 5, 'INSERT', NULL, '{\"nombres\": \"Ana María\", \"apellidos\": \"González\", \"correo_electronico\": \"ana.gonzalez@example.com\"}', '2025-05-15 21:22:48', 3),
(5, 'miembros', 6, 'INSERT', NULL, '{\"nombres\": \"Juan David\", \"apellidos\": \"Rodríguez\", \"correo_electronico\": \"juan.rodriguez@example.com\"}', '2025-05-15 21:22:48', 3),
(6, 'miembros', 7, 'INSERT', NULL, '{\"nombres\": \"Luisa Fernanda\", \"apellidos\": \"Martínez\", \"correo_electronico\": \"luisa.martinez@example.com\"}', '2025-05-15 21:22:48', 3),
(7, 'miembros', 8, 'INSERT', NULL, '{\"nombres\": \"Carlos Andrés\", \"apellidos\": \"Ramírez\", \"correo_electronico\": \"carlos.ramirez@example.com\"}', '2025-05-15 21:22:48', 3),
(8, 'miembros', 9, 'INSERT', NULL, '{\"nombres\": \"Laura Sofía\", \"apellidos\": \"Torres\", \"correo_electronico\": \"laura.torres@example.com\"}', '2025-05-15 21:22:48', 3),
(9, 'miembros', 10, 'INSERT', NULL, '{\"nombres\": \"Julián Esteban\", \"apellidos\": \"Moreno\", \"correo_electronico\": \"julian.moreno@example.com\"}', '2025-05-15 21:22:48', 3),
(10, 'miembros', 2, 'UPDATE', '{\"nombres\": \"Luis Fernando Mod\", \"apellidos\": \"Barrios 1\", \"correo_electronico\": \"luifer@gmail.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Luis Fernando Mod\", \"apellidos\": \"Barrios 1\", \"correo_electronico\": \"luifer@gmail.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(11, 'miembros', 5, 'UPDATE', '{\"nombres\": \"Ana María\", \"apellidos\": \"González\", \"correo_electronico\": \"ana.gonzalez@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Ana María\", \"apellidos\": \"González\", \"correo_electronico\": \"ana.gonzalez@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(12, 'miembros', 6, 'UPDATE', '{\"nombres\": \"Juan David\", \"apellidos\": \"Rodríguez\", \"correo_electronico\": \"juan.rodriguez@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Juan David\", \"apellidos\": \"Rodríguez\", \"correo_electronico\": \"juan.rodriguez@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(13, 'miembros', 7, 'UPDATE', '{\"nombres\": \"Luisa Fernanda\", \"apellidos\": \"Martínez\", \"correo_electronico\": \"luisa.martinez@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Luisa Fernanda\", \"apellidos\": \"Martínez\", \"correo_electronico\": \"luisa.martinez@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(14, 'miembros', 8, 'UPDATE', '{\"nombres\": \"Carlos Andrés\", \"apellidos\": \"Ramírez\", \"correo_electronico\": \"carlos.ramirez@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Carlos Andrés\", \"apellidos\": \"Ramírez\", \"correo_electronico\": \"carlos.ramirez@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(15, 'miembros', 9, 'UPDATE', '{\"nombres\": \"Laura Sofía\", \"apellidos\": \"Torres\", \"correo_electronico\": \"laura.torres@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Laura Sofía\", \"apellidos\": \"Torres\", \"correo_electronico\": \"laura.torres@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3),
(16, 'miembros', 10, 'UPDATE', '{\"nombres\": \"Julián Esteban\", \"apellidos\": \"Moreno\", \"correo_electronico\": \"julian.moreno@example.com\", \"estado\": \"activo\"}', '{\"nombres\": \"Julián Esteban\", \"apellidos\": \"Moreno\", \"correo_electronico\": \"julian.moreno@example.com\", \"estado\": \"activo\"}', '2025-05-22 09:43:40', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriasproductos`
--

CREATE TABLE `categoriasproductos` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoriasproductos`
--

INSERT INTO `categoriasproductos` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónica', 'Productos electrónicos como televisores, celulares y laptops'),
(2, 'Ropa', 'Ropa para hombre, mujer y niños'),
(3, 'Alimentos', 'Productos alimenticios y bebidas'),
(4, 'Hogar', 'Artículos para el hogar como muebles y decoración'),
(5, 'Deportes', 'Equipos y ropa deportiva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_clases`
--

CREATE TABLE `categorias_clases` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_clases`
--

INSERT INTO `categorias_clases` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Ejercicios Compuestos', 'Desarrollo de Ejercicios de Jalón y Empuje'),
(2, 'Cardio', 'Clases enfocadas en mejorar la resistencia cardiovascular y quemar calorías.'),
(3, 'Cuerpo y mente', 'Actividades suaves que combinan respiración, estiramiento y concentración.'),
(4, 'Fuerza', 'Clases centradas en el desarrollo muscular y tonificación.'),
(5, 'Funcional', 'Entrenamiento integral que simula movimientos cotidianos.'),
(6, 'Baile', 'Clases con ritmos musicales para mejorar la coordinación y estado físico.'),
(7, 'Defensa personal', 'Técnicas de autoprotección y combate adaptadas para todos los niveles.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id_clase` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_entrenador` int(11) DEFAULT NULL,
  `horario` varchar(20) NOT NULL,
  `duracion_minutos` int(11) NOT NULL,
  `capacidad_maxima` int(11) NOT NULL,
  `id_sala` int(11) DEFAULT NULL,
  `dia_semana` enum('lunes','martes','miércoles','jueves','viernes','sábado','domingo') NOT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `nivel` enum('principiante','intermedio','avanzado') DEFAULT 'principiante',
  `requisitos` text DEFAULT NULL,
  `id_categoria_clase` int(11) DEFAULT NULL,
  `cancelada` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id_clase`, `nombre`, `descripcion`, `id_entrenador`, `horario`, `duracion_minutos`, `capacidad_maxima`, `id_sala`, `dia_semana`, `precio`, `nivel`, `requisitos`, `id_categoria_clase`, `cancelada`) VALUES
(1, 'Clases de Aerobicos 1', 'Ninguna', 1, '10:00 - 11:00', 60, 20, 1, 'jueves', 1200.00, 'principiante', 'N/A', 1, 0),
(2, 'Clases de Aerobicos 2', 'dfsf', 1, '10:00 -11:00', 45, 34, 1, 'miércoles', 3233.00, 'principiante', 'fdgdfg', 1, 0),
(15, 'Yoga Inicial', 'Clase básica de yoga para principiantes.', 1, '07:00:00', 60, 20, 1, 'lunes', 15000.00, '', 'Ropa cómoda, tapete', 1, 0),
(16, 'CrossFit Intermedio', 'Entrenamiento funcional de alta intensidad.', 1, '18:00:00', 45, 15, 1, 'martes', 18000.00, 'intermedio', 'Experiencia previa', 1, 0),
(17, 'Spinning', 'Sesión intensa de ciclismo en sala.', 1, '06:30:00', 50, 25, 1, 'miércoles', 16000.00, '', 'Toalla y agua', 1, 0),
(18, 'Pilates Avanzado', 'Ejercicios de flexibilidad y fuerza.', 1, '09:00:00', 60, 10, 1, 'jueves', 20000.00, 'avanzado', 'Experiencia en pilates', 1, 0),
(19, 'Boxeo Recreativo', 'Clase para aprender técnicas básicas de boxeo.', 1, '19:00:00', 60, 20, 1, 'viernes', 17000.00, '', 'Guantes y vendas', 1, 0),
(20, 'Zumba Fitness', 'Rutinas de baile para quemar calorías.', 1, '17:00:00', 50, 30, 1, 'sábado', 15000.00, '', 'Ropa deportiva', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

CREATE TABLE `configuracion_sistema` (
  `id_config` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text DEFAULT NULL,
  `tipo` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `editable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion_sistema`
--

INSERT INTO `configuracion_sistema` (`id_config`, `clave`, `valor`, `tipo`, `descripcion`, `editable`) VALUES
(1, 'dias_expiracion_password', '90', 'entero', 'Días antes de que expire una contraseña', 1),
(2, 'intentos_maximos_login', '5', 'entero', 'Intentos máximos de login fallidos antes de bloquear', 1),
(3, 'duracion_bloqueo_minutos', '30', 'entero', 'Minutos de bloqueo por intentos fallidos', 1),
(4, 'membresia_dias_gracia', '7', 'entero', 'Días de gracia después del vencimiento de membresía', 1),
(5, 'notificar_stock_minimo', '1', 'booleano', 'Notificar cuando productos alcancen stock mínimo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallesventa`
--

CREATE TABLE `detallesventa` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallesventa`
--

INSERT INTO `detallesventa` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 2, 2, 1, 43242.00, 43242.00),
(2, 2, 1, 1, 2500.00, 2500.00),
(3, 3, 3, 1, 4500.00, 4500.00),
(4, 3, 12, 1, 25000.00, 25000.00),
(5, 4, 2, 1, 43242.00, 43242.00),
(6, 4, 12, 1, 25000.00, 25000.00),
(7, 5, 11, 1, 30000.00, 30000.00),
(8, 5, 12, 1, 25000.00, 25000.00),
(9, 6, 2, 1, 43242.00, 43242.00),
(10, 7, 12, 1, 25000.00, 25000.00),
(11, 8, 2, 1, 43242.00, 43242.00),
(12, 9, 3, 1, 4500.00, 4500.00),
(13, 10, 3, 1, 4500.00, 4500.00),
(14, 11, 2, 2, 43242.00, 86484.00),
(15, 11, 12, 2, 25000.00, 50000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `tamano` int(11) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `entidad_relacionada` varchar(50) NOT NULL,
  `id_entidad_relacionada` int(11) NOT NULL,
  `subido_por` int(11) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadores`
--

CREATE TABLE `entrenadores` (
  `id_entrenador` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `certificacion` varchar(100) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrenadores`
--

INSERT INTO `entrenadores` (`id_entrenador`, `nombres`, `apellidos`, `especialidad`, `telefono`, `correo_electronico`, `fecha_contratacion`, `estado`, `certificacion`, `id_usuario`) VALUES
(1, 'Ernesto Mod', 'Escobar', 'Aerobicos', '122222222222222', 'colanta1@gmail.com', '2025-05-14', 'activo', 'CT3423', 3),
(17, 'Andrés', 'Gómez', 'Yoga', '3101234567', 'andres.gomez@example.com', '2020-01-15', 'activo', 'RYT 200', 14),
(18, 'María', 'López', 'CrossFit', '3112345678', 'maria.lopez@example.com', '2018-05-20', 'activo', 'CF Level 1', 9),
(19, 'Javier', 'Martínez', 'Spinning', '3123456789', 'javier.martinez@example.com', '2019-09-10', 'activo', 'Spin Instructor', 10),
(20, 'Sofía', 'Ramírez', 'Pilates', '3134567890', 'sofia.ramirez@example.com', '2021-03-05', 'activo', 'Certified Pilates Instructor', 11),
(21, 'Carlos', 'Torres', 'Boxeo', '3145678901', 'carlos.torres@example.com', '2017-11-12', 'activo', 'Boxing Coach Level 2', 12),
(22, 'Laura', 'Fernández', 'Zumba', '3156789012', 'laura.fernandez@example.com', '2022-07-18', 'activo', 'Zumba Basic', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipamiento`
--

CREATE TABLE `equipamiento` (
  `id_equipo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_compra` date DEFAULT NULL,
  `ultimo_mantenimiento` date DEFAULT NULL,
  `estado` enum('operativo','mantenimiento','descartado') DEFAULT 'operativo',
  `ubicacion` varchar(100) DEFAULT NULL,
  `garantia_vencimiento` date DEFAULT NULL,
  `entrenador_responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excepciones_clases`
--

CREATE TABLE `excepciones_clases` (
  `id_excepcion` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `accion` enum('cancelada','cambio_horario','cambio_sala','sustituto') NOT NULL,
  `nuevo_horario` time DEFAULT NULL,
  `id_nueva_sala` int(11) DEFAULT NULL,
  `id_entrenador_sustituto` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialaccesos`
--

CREATE TABLE `historialaccesos` (
  `id_acceso` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_salida` datetime DEFAULT NULL,
  `metodo_acceso` enum('huella','tarjeta','manual') NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historialaccesos`
--

INSERT INTO `historialaccesos` (`id_acceso`, `id_miembro`, `fecha_entrada`, `fecha_salida`, `metodo_acceso`, `id_usuario`) VALUES
(1, 5, '2025-05-19 10:58:08', NULL, 'huella', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `huellasdigitales`
--

CREATE TABLE `huellasdigitales` (
  `id_huella` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `datos_plantilla` longtext NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_uso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcionesclases`
--

CREATE TABLE `inscripcionesclases` (
  `id_inscripcion` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_asistencia` enum('inscrito','asistio','ausente') DEFAULT 'inscrito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id_log` int(11) NOT NULL,
  `nivel` enum('info','advertencia','error','critico') NOT NULL,
  `mensaje` text NOT NULL,
  `contexto` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contexto`)),
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresias`
--

CREATE TABLE `membresias` (
  `id_membresia` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `id_tipo_membresia` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado_pago` enum('pagado','pendiente','vencido','cancelado') DEFAULT 'pendiente',
  `precio` decimal(10,2) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `pausada_desde` date DEFAULT NULL,
  `pausada_hasta` date DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `dias_pausa_total` int(11) NOT NULL DEFAULT 0,
  `creado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `membresias`
--

INSERT INTO `membresias` (`id_membresia`, `id_miembro`, `id_tipo_membresia`, `fecha_inicio`, `fecha_fin`, `estado_pago`, `precio`, `id_metodo`, `creado_en`, `pausada_desde`, `pausada_hasta`, `id_promocion`, `dias_pausa_total`, `creado_por`) VALUES
(1, 2, 4, '2025-05-19', '2025-06-18', 'pendiente', NULL, 1, '2025-05-20 02:46:04', NULL, NULL, NULL, 0, 3),
(2, 2, 2, '2025-04-21', '2025-05-21', 'pendiente', NULL, 1, '2025-05-22 01:02:49', NULL, NULL, NULL, 0, 3),
(3, 2, 2, '2025-05-21', '2025-06-20', 'pendiente', NULL, NULL, '2025-05-22 01:03:54', NULL, NULL, NULL, 0, 3),
(4, 2, 1, '2025-05-21', '2025-06-05', 'pendiente', NULL, NULL, '2025-05-22 02:49:56', NULL, NULL, NULL, 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id_metodo`, `nombre`, `descripcion`, `activo`) VALUES
(1, 'efectivo', 'Pago en efectivo', 1),
(2, 'tarjeta_credito', 'Pago con tarjeta de crédito', 1),
(3, 'tarjeta_debito', 'Pago con tarjeta de débito', 1),
(4, 'transferencia', 'Transferencia bancaria', 1),
(5, 'otro', 'Otro método de pago', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE `miembros` (
  `id_miembro` int(11) NOT NULL,
  `tipo_documento` enum('CC','TI','CE','NIT','RC','PASAPORTE','PEP','DNI','CM','CI','LM','OTRO') NOT NULL DEFAULT 'CC',
  `numero_documento` varchar(30) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('masculino','femenino','otro') DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo',
  `url_foto` varchar(255) DEFAULT NULL,
  `contacto_emergencia_nombre` varchar(100) DEFAULT NULL,
  `contacto_emergencia_telefono` varchar(20) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `miembros`
--

INSERT INTO `miembros` (`id_miembro`, `tipo_documento`, `numero_documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `genero`, `correo_electronico`, `telefono`, `direccion`, `fecha_registro`, `estado`, `url_foto`, `contacto_emergencia_nombre`, `contacto_emergencia_telefono`, `creado_por`) VALUES
(2, 'CC', '47577196', 'Luis Fernando Mod', 'Barrios 1', '2025-05-15', 'masculino', 'luifer@gmail.com', '3333333333', 'Cll 23 #64-75', '2025-05-15 20:35:46', 'activo', '', 'Liliana Muñoz', '32543534344', 3),
(5, 'CC', '80251887', 'Ana María', 'González', '1992-04-10', 'femenino', 'ana.gonzalez@example.com', '3011234567', 'Cra 1 #10-11', '2025-05-15 21:22:48', 'activo', '', 'Carlos González', '3214567890', 3),
(6, 'CC', '58527854', 'Juan David', 'Rodríguez', '1985-12-22', 'masculino', 'juan.rodriguez@example.com', '3029876543', 'Av 5 #23-45', '2025-05-15 21:22:48', 'activo', '', 'Laura Rodríguez', '3102345678', 3),
(7, 'CC', '51883610', 'Luisa Fernanda', 'Martínez', '1998-08-19', 'femenino', 'luisa.martinez@example.com', '3001234567', 'Cll 8 #12-34', '2025-05-15 21:22:48', 'activo', '', 'Pedro Martínez', '3156789123', 3),
(8, 'CC', '83834493', 'Carlos Andrés', 'Ramírez', '1990-05-30', 'masculino', 'carlos.ramirez@example.com', '3119876543', 'Cra 7 #21-34', '2025-05-15 21:22:48', 'activo', '', 'Martha Ramírez', '3124567890', 3),
(9, 'CC', '63521640', 'Laura Sofía', 'Torres', '1995-09-14', 'femenino', 'laura.torres@example.com', '3041239876', 'Cll 13 #45-67', '2025-05-15 21:22:48', 'activo', '', 'Ana Torres', '3111111111', 3),
(10, 'CC', '66104722', 'Julián Esteban', 'Moreno', '2000-01-07', 'masculino', 'julian.moreno@example.com', '3054321987', 'Av 9 #23-56', '2025-05-15 21:22:48', 'activo', '', 'Ricardo Moreno', '3123456789', 3);

--
-- Disparadores `miembros`
--
DELIMITER $$
CREATE TRIGGER `audit_miembros_insert` AFTER INSERT ON `miembros` FOR EACH ROW BEGIN
  INSERT INTO auditoria (tabla_afectada, id_registro, accion, datos_nuevos, id_usuario)
  VALUES ('miembros', NEW.id_miembro, 'INSERT', 
          JSON_OBJECT('nombres', NEW.nombres, 'apellidos', NEW.apellidos, 'correo_electronico', NEW.correo_electronico),
          NEW.creado_por);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `audit_miembros_update` AFTER UPDATE ON `miembros` FOR EACH ROW BEGIN
  INSERT INTO auditoria (tabla_afectada, id_registro, accion, datos_anteriores, datos_nuevos, id_usuario)
  VALUES ('miembros', NEW.id_miembro, 'UPDATE', 
          JSON_OBJECT('nombres', OLD.nombres, 'apellidos', OLD.apellidos, 'correo_electronico', OLD.correo_electronico, 'estado', OLD.estado),
          JSON_OBJECT('nombres', NEW.nombres, 'apellidos', NEW.apellidos, 'correo_electronico', NEW.correo_electronico, 'estado', NEW.estado),
          NEW.creado_por);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo_movimiento` enum('compra','venta','ajuste','devolucion','perdida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  `tipo_referencia` varchar(30) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_leida` datetime DEFAULT NULL,
  `tipo` enum('sistema','pago','clase','membresia','inventario') NOT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  `tipo_referencia` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_membresia` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_transaccion` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL,
  `id_metodo_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `codigo_producto` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad_stock` int(11) NOT NULL DEFAULT 0,
  `id_categoria` int(11) DEFAULT NULL,
  `codigo_barras` varchar(50) DEFAULT NULL,
  `url_imagen` varchar(255) DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `margen_ganancia` decimal(5,2) DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `codigo_producto`, `nombre`, `descripcion`, `precio`, `cantidad_stock`, `id_categoria`, `codigo_barras`, `url_imagen`, `precio_compra`, `margen_ganancia`, `stock_minimo`, `activo`, `id_proveedor`) VALUES
(1, '', 'Colgate', 'Pasta dental colgate pequeño', 2500.00, 24, 3, 'BAR-ABC34', '', 1800.00, 700.00, 10, 1, 1),
(2, 'ABC345', 'Arroz', 'dsafd', 43242.00, 116, 3, 'BAR-ABC345', '../../img/productos/producto_682b606c4ff48.png', 1221.00, 999.99, 12, 1, 1),
(3, 'ABC3456', 'Carne de Res', 'Carne premium', 4500.00, 31, 3, 'BAR-ABC3456', '../../img/productos/producto_6823c918487b8.jpeg', 2400.00, 999.99, 12, 1, 1),
(10, 'PROD001', 'Mancuernas 5kg', 'Juego de mancuernas de 5kg para entrenamiento', 50000.00, 30, 2, '1234567890123', '../../img/productos/producto_682b6159c8847.png', 35000.00, 999.99, 5, 1, 3),
(11, 'PROD002', 'Colchoneta Yoga', 'Colchoneta antideslizante para yoga y pilates', 30000.00, 49, 5, '1234567890124', '../../img/productos/producto_682b6169296f5.png', 20000.00, 999.99, 10, 1, 6),
(12, 'PROD003', 'Bandas Elásticas', 'Set de bandas para ejercicios de resistencia', 25000.00, 34, 5, '1234567890125', '../../img/productos/producto_682b6175814b5.png', 15000.00, 999.99, 8, 1, 6),
(13, 'PROD004', 'Botella de Agua', 'Botella deportiva reutilizable de 750ml', 15000.00, 100, 3, '1234567890126', '../../img/productos/producto_682b61824d11f.png', 8000.00, 999.99, 15, 1, 1),
(14, 'PROD005', 'Guantes de Boxeo', 'Guantes para entrenamiento y sparring', 70000.00, 20, 5, '1234567890127', '../../img/productos/producto_682b618f5d8dc.jpeg', 45000.00, 999.99, 4, 1, 3),
(15, 'PROD006', 'Ropa Deportiva', 'Conjunto de camiseta y pantalón para gimnasio', 90000.00, 25, 2, '1234567890128', '../../img/productos/producto_682b619bce681.jpeg', 60000.00, 999.99, 6, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id_promocion` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `tipo_descuento` enum('porcentaje','monto_fijo') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `usos_maximos` int(11) DEFAULT NULL,
  `usos_actuales` int(11) DEFAULT 0,
  `activa` tinyint(1) DEFAULT 1,
  `aplica_a` enum('membresia','producto','clase','todo') NOT NULL,
  `id_aplicable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id_promocion`, `codigo`, `descripcion`, `descuento`, `tipo_descuento`, `fecha_inicio`, `fecha_fin`, `usos_maximos`, `usos_actuales`, `activa`, `aplica_a`, `id_aplicable`) VALUES
(1, 'PRONTO_CIVIL', '10% de descuento por pronto pago en membresía Civil', 10.00, 'porcentaje', '2025-01-01', '2025-12-31', 1000, 0, 1, 'membresia', 1),
(2, 'PRONTO_MILITAR', '15% de descuento por pronto pago en membresía Militar', 15.00, 'porcentaje', '2025-01-01', '2025-12-31', 1000, 0, 1, 'membresia', 2),
(3, 'PRONTO_FAMILIA', '12% de descuento por pronto pago en membresía Familia Militar', 12.00, 'porcentaje', '2025-01-01', '2025-12-31', 1000, 0, 1, 'membresia', 3),
(4, 'DESCUENTO_NAVIDAD', '20% de descuento en membresías por Navidad', 20.00, 'porcentaje', '2025-12-15', '2025-12-31', 500, 0, 1, 'membresia', NULL),
(5, 'DESCUENTO_ANIVERSARI', '15% de descuento en todas las membresías por aniversario', 15.00, 'porcentaje', '2025-05-01', '2025-05-07', 300, 0, 1, 'membresia', NULL),
(6, 'DESCUENTO_PRIMAVERA', '10% de descuento en membresías durante la primavera', 10.00, 'porcentaje', '2025-09-01', '2025-09-30', 200, 0, 1, 'membresia', NULL),
(7, 'DESCUENTO_FIJO_CIVIL', 'Descuento fijo de $5,000 COP para membresía Civil', 999.99, 'monto_fijo', '2025-02-01', '2025-02-28', 150, 0, 1, 'membresia', 1),
(8, 'DESCUENTO_FIJO_MILIT', 'Descuento fijo de $7,000 COP para membresía Militar', 999.99, 'monto_fijo', '2025-02-01', '2025-02-28', 150, 0, 1, 'membresia', 2),
(9, 'DESCUENTO_FIJO_FAMIL', 'Descuento fijo de $10,000 COP para membresía Familia Militar', 999.99, 'monto_fijo', '2025-02-01', '2025-02-28', 150, 0, 1, 'membresia', 3),
(10, 'DESCUENTO_GENERAL', '5% de descuento general en todas las membresías', 5.00, 'porcentaje', '2025-06-01', '2025-06-30', 1000, 0, 1, 'membresia', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `contacto`, `telefono`, `correo_electronico`, `direccion`, `notas`) VALUES
(1, 'Colanta Mod', 'Luis Espinosa', '3333333333', 'colanta@gmail.com', 'Cll 23 #64-75', 'N/A mod'),
(3, 'Proveedor Alfa', 'Juan Pérez', '3101234567', 'juan.perez@alfa.com', 'Cra 10 #20-30', 'Entrega rápida y confiable'),
(4, 'Proveedor Beta', 'María Gómez', '3112345678', 'maria.gomez@beta.com', 'Cll 15 #45-50', 'Productos de alta calidad'),
(5, 'Proveedor Gamma', 'Carlos Ramírez', '3123456789', 'carlos.ramirez@gamma.com', 'Av 5 #10-25', 'Buena atención al cliente'),
(6, 'Proveedor Delta', 'Ana Torres', '3134567890', 'ana.torres@delta.com', 'Cra 7 #12-35', 'Precios competitivos'),
(7, 'Proveedor Épsilon', 'Luis Martínez', '3145678901', 'luis.martinez@epsilon.com', 'Cll 8 #22-40', 'Amplia variedad de productos'),
(8, 'Proveedor Zeta', 'Laura Fernández', '3156789012', 'laura.fernandez@zeta.com', 'Av 9 #18-30', 'Entrega puntual y confiable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renovaciones`
--

CREATE TABLE `renovaciones` (
  `id_renovacion` int(11) NOT NULL,
  `id_membresia` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `renovado_por` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `renovaciones`
--

INSERT INTO `renovaciones` (`id_renovacion`, `id_membresia`, `id_miembro`, `numero_factura`, `id_metodo_pago`, `renovado_por`, `observaciones`, `fecha`) VALUES
(1, 3, 2, '4521ASD', 1, 9, 'null', '2025-05-22 01:03:54'),
(2, 2, 2, '4521ASD', 1, 9, 'null', '2025-05-22 01:07:31'),
(3, 2, 2, '4521ASD', 1, 9, 'null', '2025-05-22 01:17:06'),
(4, 2, 2, '4521ASD', 2, 9, 'Pruebas', '2025-05-22 01:44:17'),
(5, 2, 2, '4521ASD', 1, 9, 'null', '2025-05-22 01:45:49'),
(6, 2, 2, '4521ASD', 1, 9, '', '2025-05-22 01:47:21'),
(7, 2, 2, '4521ASD', 1, 9, 'null', '2025-05-22 01:51:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `creado_en`) VALUES
(1, 'Administrador', 'Tiene acceso completo al sistema, incluyendo la gestión de usuarios y configuraciones.', '2025-04-24 20:04:26'),
(2, 'Supervisor', 'Puede supervisar actividades y gestionar ciertos módulos, pero con acceso limitado.', '2025-04-24 20:04:26'),
(3, 'Invitado', 'Acceso restringido, solo puede visualizar información básica.', '2025-04-24 20:04:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id_sala`, `nombre`, `capacidad`, `descripcion`) VALUES
(1, 'Sala Aerobicos', 25, 'Sala disponibles para bailes de Aerobicos'),
(2, 'Sala A', 30, 'Sala principal para clases de yoga y pilates.'),
(3, 'Sala B', 20, 'Sala equipada para entrenamiento funcional y crossfit.'),
(4, 'Sala C', 25, 'Sala destinada para clases de spinning y ciclismo indoor.'),
(5, 'Sala D', 15, 'Sala pequeña para entrenamientos personalizados y boxeo.'),
(6, 'Sala E', 40, 'Sala amplia para actividades de baile y zumba.'),
(7, 'Sala F', 10, 'Sala para sesiones de meditación y terapia física.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposmembresia`
--

CREATE TABLE `tiposmembresia` (
  `id_tipo_membresia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `duracion_dias` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `beneficios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposmembresia`
--

INSERT INTO `tiposmembresia` (`id_tipo_membresia`, `nombre`, `duracion_dias`, `precio`, `descripcion`, `beneficios`) VALUES
(1, 'Civil 15 dias', 15, 30000.00, 'Membresia 15 dias civil', 'N/A'),
(2, 'Civil', 30, 50000.00, 'Membresía para civiles con acceso básico.', 'Acceso a todas las instalaciones básicas'),
(3, 'Militar', 30, 45000.00, 'Membresía para personal militar con descuentos especiales.', 'Acceso completo y beneficios adicionales'),
(4, 'Familia Militar', 30, 80000.00, 'Membresía familiar para militares y sus familias.', 'Acceso completo para toda la familia con beneficios especiales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariossistema`
--

CREATE TABLE `usuariossistema` (
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `rol` enum('admin','recepcionista','entrenador','gerente') NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuariossistema`
--

INSERT INTO `usuariossistema` (`id_usuario`, `nombres`, `apellidos`, `nombre_usuario`, `contrasena_hash`, `rol`, `telefono`, `correo_electronico`, `ultimo_acceso`, `estado`, `creado_en`) VALUES
(3, 'LUIS FERNANDO1', 'BARRIOS', 'admin', '$2y$10$O4YmnUki6ce/GzFWDRECLeyLzEIdomMJhQEB.ZBxJaFPTSKbeJ0W6', 'admin', '1234123222', 'luis.barrios@esfim.edu.co', NULL, 'activo', '0000-00-00 00:00:00'),
(9, 'pepito', 'perez', 'pepito.perez', '$2y$10$d4ruHcpjC1zVbOMZmz2mWOYlUsyCa45DVoimrcvuW6YB9Iv.TMhyO', 'admin', '5555555555', 'pepito@gmail.com', '2025-05-22 06:29:50', 'activo', '2025-05-13 20:43:21'),
(10, 'Andrés', 'Gómez', 'andresg', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'admin', '3101234567', 'andres.gomez@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54'),
(11, 'María', 'López', 'marial', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'entrenador', '3112345678', 'maria.lopez@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54'),
(12, 'Javier', 'Martínez', 'javierm', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'entrenador', '3123456789', 'javier.martinez@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54'),
(13, 'Sofía', 'Ramírez', 'sofiar', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'entrenador', '3134567890', 'sofia.ramirez@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54'),
(14, 'Carlos', 'Torres', 'carlost', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'entrenador', '3145678901', 'carlos.torres@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54'),
(15, 'Laura', 'Fernández', 'lauraf', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'entrenador', '3156789012', 'laura.fernandez@example.com', '2025-05-15 16:27:54', 'activo', '2025-05-15 21:27:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_miembro` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `numero_factura` varchar(50) DEFAULT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `descuento_total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_miembro`, `id_usuario`, `fecha_venta`, `total`, `numero_factura`, `id_metodo_pago`, `id_promocion`, `descuento_total`) VALUES
(2, NULL, 9, '2025-05-16 15:43:12', 45742.00, NULL, 1, NULL, 0.00),
(3, NULL, 9, '2025-05-16 15:45:17', 29500.00, NULL, 1, NULL, 0.00),
(4, NULL, 9, '2025-05-16 15:45:31', 68242.00, NULL, 1, NULL, 0.00),
(5, NULL, 9, '2025-05-16 15:58:49', 55000.00, 'F20250516-00001', 1, NULL, 0.00),
(6, NULL, 9, '2025-05-16 16:09:38', 43242.00, 'F20250516-00002', 1, NULL, 0.00),
(7, NULL, 9, '2025-05-16 16:10:51', 25000.00, 'F20250516-00003', 1, NULL, 0.00),
(8, NULL, 9, '2025-05-16 16:22:53', 43242.00, 'F20250516-00004', 1, NULL, 0.00),
(9, NULL, 9, '2025-05-16 16:35:38', 4500.00, 'F20250516-00005', 1, NULL, 0.00),
(10, NULL, 9, '2025-05-16 16:36:01', 4500.00, 'F20250516-00006', 1, NULL, 0.00),
(11, NULL, 9, '2025-05-16 19:15:26', 136484.00, 'F20250516-00007', 1, NULL, 0.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `categoriasproductos`
--
ALTER TABLE `categoriasproductos`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categorias_clases`
--
ALTER TABLE `categorias_clases`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id_clase`),
  ADD KEY `id_entrenador` (`id_entrenador`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `fk_categoria_clase` (`id_categoria_clase`),
  ADD KEY `idx_clases_activas` (`cancelada`,`horario`,`dia_semana`);

--
-- Indices de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  ADD PRIMARY KEY (`id_config`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `detallesventa`
--
ALTER TABLE `detallesventa`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `subido_por` (`subido_por`);

--
-- Indices de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`id_entrenador`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `equipamiento`
--
ALTER TABLE `equipamiento`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `entrenador_responsable` (`entrenador_responsable`);

--
-- Indices de la tabla `excepciones_clases`
--
ALTER TABLE `excepciones_clases`
  ADD PRIMARY KEY (`id_excepcion`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `id_nueva_sala` (`id_nueva_sala`),
  ADD KEY `id_entrenador_sustituto` (`id_entrenador_sustituto`);

--
-- Indices de la tabla `historialaccesos`
--
ALTER TABLE `historialaccesos`
  ADD PRIMARY KEY (`id_acceso`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_historial_acceso` (`id_miembro`,`fecha_entrada`),
  ADD KEY `idx_accesos_miembro` (`id_miembro`,`fecha_entrada`);

--
-- Indices de la tabla `huellasdigitales`
--
ALTER TABLE `huellasdigitales`
  ADD PRIMARY KEY (`id_huella`),
  ADD KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `inscripcionesclases`
--
ALTER TABLE `inscripcionesclases`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `id_clase` (`id_clase`,`id_miembro`),
  ADD KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `membresias`
--
ALTER TABLE `membresias`
  ADD PRIMARY KEY (`id_membresia`),
  ADD KEY `id_miembro` (`id_miembro`),
  ADD KEY `id_tipo_membresia` (`id_tipo_membresia`),
  ADD KEY `id_metodo` (`id_metodo`),
  ADD KEY `idx_membresia_fechas` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `idx_promocion` (`id_promocion`),
  ADD KEY `idx_membresias_vencimiento` (`fecha_fin`,`estado_pago`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id_metodo`);

--
-- Indices de la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id_miembro`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `idx_miembro_nombre` (`apellidos`,`nombres`),
  ADD KEY `idx_miembro_correo` (`correo_electronico`),
  ADD KEY `idx_miembro_telefono` (`telefono`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_membresia` (`id_membresia`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `fk_pago_metodo_pago` (`id_metodo_pago`),
  ADD KEY `idx_pagos_fecha` (`fecha_pago`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo_producto` (`codigo_producto`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `fk_producto_proveedor` (`id_proveedor`),
  ADD KEY `idx_productos_activos` (`activo`,`cantidad_stock`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id_promocion`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  ADD PRIMARY KEY (`id_renovacion`),
  ADD KEY `id_membresia` (`id_membresia`),
  ADD KEY `id_miembro` (`id_miembro`),
  ADD KEY `id_metodo_pago` (`id_metodo_pago`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`);

--
-- Indices de la tabla `tiposmembresia`
--
ALTER TABLE `tiposmembresia`
  ADD PRIMARY KEY (`id_tipo_membresia`);

--
-- Indices de la tabla `usuariossistema`
--
ALTER TABLE `usuariossistema`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `id_miembro` (`id_miembro`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_venta_metodo_pago` (`id_metodo_pago`),
  ADD KEY `fk_venta_promocion` (`id_promocion`),
  ADD KEY `idx_ventas_fecha` (`fecha_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `categoriasproductos`
--
ALTER TABLE `categoriasproductos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias_clases`
--
ALTER TABLE `categorias_clases`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id_clase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detallesventa`
--
ALTER TABLE `detallesventa`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id_entrenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `equipamiento`
--
ALTER TABLE `equipamiento`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `excepciones_clases`
--
ALTER TABLE `excepciones_clases`
  MODIFY `id_excepcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historialaccesos`
--
ALTER TABLE `historialaccesos`
  MODIFY `id_acceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `huellasdigitales`
--
ALTER TABLE `huellasdigitales`
  MODIFY `id_huella` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcionesclases`
--
ALTER TABLE `inscripcionesclases`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `membresias`
--
ALTER TABLE `membresias`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  MODIFY `id_renovacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tiposmembresia`
--
ALTER TABLE `tiposmembresia`
  MODIFY `id_tipo_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuariossistema`
--
ALTER TABLE `usuariossistema`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`id_entrenador`) REFERENCES `entrenadores` (`id_entrenador`),
  ADD CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `fk_categoria_clase` FOREIGN KEY (`id_categoria_clase`) REFERENCES `categorias_clases` (`id_categoria`);

--
-- Filtros para la tabla `detallesventa`
--
ALTER TABLE `detallesventa`
  ADD CONSTRAINT `detallesventa_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `detallesventa_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`subido_por`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD CONSTRAINT `entrenadores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `equipamiento`
--
ALTER TABLE `equipamiento`
  ADD CONSTRAINT `equipamiento_ibfk_1` FOREIGN KEY (`entrenador_responsable`) REFERENCES `entrenadores` (`id_entrenador`);

--
-- Filtros para la tabla `excepciones_clases`
--
ALTER TABLE `excepciones_clases`
  ADD CONSTRAINT `excepciones_clases_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id_clase`),
  ADD CONSTRAINT `excepciones_clases_ibfk_2` FOREIGN KEY (`id_nueva_sala`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `excepciones_clases_ibfk_3` FOREIGN KEY (`id_entrenador_sustituto`) REFERENCES `entrenadores` (`id_entrenador`);

--
-- Filtros para la tabla `historialaccesos`
--
ALTER TABLE `historialaccesos`
  ADD CONSTRAINT `historialaccesos_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`),
  ADD CONSTRAINT `historialaccesos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `huellasdigitales`
--
ALTER TABLE `huellasdigitales`
  ADD CONSTRAINT `huellasdigitales_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripcionesclases`
--
ALTER TABLE `inscripcionesclases`
  ADD CONSTRAINT `inscripcionesclases_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id_clase`),
  ADD CONSTRAINT `inscripcionesclases_ibfk_2` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`);

--
-- Filtros para la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `membresias`
--
ALTER TABLE `membresias`
  ADD CONSTRAINT `fk_membresia_metodo` FOREIGN KEY (`id_metodo`) REFERENCES `metodos_pago` (`id_metodo`),
  ADD CONSTRAINT `fk_membresia_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id_promocion`),
  ADD CONSTRAINT `membresias_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`),
  ADD CONSTRAINT `membresias_ibfk_2` FOREIGN KEY (`id_tipo_membresia`) REFERENCES `tiposmembresia` (`id_tipo_membresia`);

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `miembros_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pago_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id_metodo`),
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_membresia`) REFERENCES `membresias` (`id_membresia`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuariossistema` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoriasproductos` (`id_categoria`);

--
-- Filtros para la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  ADD CONSTRAINT `renovaciones_ibfk_1` FOREIGN KEY (`id_membresia`) REFERENCES `membresias` (`id_membresia`),
  ADD CONSTRAINT `renovaciones_ibfk_2` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`),
  ADD CONSTRAINT `renovaciones_ibfk_3` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id_metodo`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_venta_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id_metodo`),
  ADD CONSTRAINT `fk_venta_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id_promocion`),
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuariossistema` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

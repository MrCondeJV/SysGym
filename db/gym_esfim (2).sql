-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2025 a las 15:30:13
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
-- Base de datos: `gym_esfim`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `renovar_membresia` (IN `p_id_membresia` INT, IN `p_id_tipo_membresia` INT, IN `p_descuento` DECIMAL(5,2), IN `p_id_usuario` INT)   BEGIN
  DECLARE v_fecha_inicio DATE;
  DECLARE v_fecha_fin DATE;
  DECLARE v_precio DECIMAL(10,2);
  DECLARE v_id_miembro INT;
  
  -- Obtener datos de la membresía actual
  SELECT id_miembro, fecha_fin INTO v_id_miembro, v_fecha_inicio
  FROM membresias WHERE id_membresia = p_id_membresia;
  
  -- Obtener datos del nuevo tipo de membresía
  SELECT precio, duracion_dias INTO v_precio, v_fecha_fin
  FROM tiposmembresia WHERE id_tipo_membresia = p_id_tipo_membresia;
  
  -- Calcular fecha fin y precio con descuento
  SET v_fecha_fin = v_fecha_inicio + INTERVAL v_fecha_fin DAY;
  SET v_precio = v_precio * (1 - (p_descuento / 100));
  
  -- Actualizar membresía existente como histórica
  UPDATE membresias SET estado_pago = 'cancelado' WHERE id_membresia = p_id_membresia;
  
  -- Crear nueva membresía
  INSERT INTO membresias (id_miembro, id_tipo_membresia, fecha_inicio, fecha_fin, estado_pago, precio, metodo_pago, creado_en)
  VALUES (v_id_miembro, p_id_tipo_membresia, v_fecha_inicio, v_fecha_fin, 'pagado', v_precio, 'renovacion', NOW());
  
  -- Registrar pago
  INSERT INTO pagos (id_membresia, monto, metodo_pago, registrado_por)
  VALUES (LAST_INSERT_ID(), v_precio, 'renovacion', p_id_usuario);
  
  -- Registrar en logs
  INSERT INTO logs_sistema (nivel, mensaje, id_usuario)
  VALUES ('info', CONCAT('Membresía renovada: ', p_id_membresia), p_id_usuario);
END$$

DELIMITER ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriasproductos`
--

CREATE TABLE `categoriasproductos` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_clases`
--

CREATE TABLE `categorias_clases` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id_clase` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_entrenador` int(11) DEFAULT NULL,
  `horario` time NOT NULL,
  `duracion_minutos` int(11) NOT NULL,
  `capacidad_maxima` int(11) NOT NULL,
  `id_sala` int(11) DEFAULT NULL,
  `dia_semana` enum('lunes','martes','miércoles','jueves','viernes','sábado','domingo') NOT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `nivel` enum('principiante','intermedio','avanzado') DEFAULT 'principiante',
  `requisitos` text DEFAULT NULL,
  `id_categoria_clase` int(11) DEFAULT NULL,
  `cancelada` tinyint(1) DEFAULT 0
) ;

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
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `entidad` varchar(50) NOT NULL,
  `valor` enum('activo','inactivo','suspendido','pagado','pendiente','vencido','cancelado','operativo','mantenimiento','descartado','inscrito','asistio','ausente') NOT NULL,
  `descripcion` text DEFAULT NULL
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
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialaccesos`
--

CREATE TABLE `historialaccesos` (
  `id_acceso` int(11) NOT NULL,
  `id_miembro` int(11) NOT NULL,
  `hora_entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `hora_salida` datetime DEFAULT NULL,
  `metodo_acceso` enum('huella','tarjeta','manual') NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `precio` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `es_grupal` tinyint(1) DEFAULT 0,
  `id_membresia_padre` int(11) DEFAULT NULL,
  `pausada_desde` date DEFAULT NULL,
  `pausada_hasta` date DEFAULT NULL,
  `id_promocion` int(11) DEFAULT NULL,
  `dias_pausa_total` int(11) DEFAULT 0
) ;

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
) ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariossistema`
--

CREATE TABLE `usuariossistema` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `rol` enum('admin','recepcionista','entrenador','gerente') NOT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `estado` enum('activo','inactivo','suspendido') DEFAULT 'activo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `ultimo_cambio_password` datetime DEFAULT NULL,
  `intentos_fallidos` int(11) DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL,
  `requiere_cambio_password` tinyint(1) DEFAULT 0,
  `fecha_expiracion_password` date DEFAULT (curdate() + interval 90 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_membresias_proximas_vencer`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_membresias_proximas_vencer` (
`id_miembro` int(11)
,`nombre_completo` varchar(101)
,`tipo_membresia` varchar(50)
,`fecha_fin` date
,`dias_restantes` int(7)
,`correo_electronico` varchar(100)
,`telefono` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos_stock_bajo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_productos_stock_bajo` (
`id_producto` int(11)
,`nombre` varchar(100)
,`cantidad_stock` int(11)
,`stock_minimo` int(11)
,`categoria` varchar(50)
,`proveedor` varchar(100)
,`estado_stock` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_membresias_proximas_vencer`
--
DROP TABLE IF EXISTS `vw_membresias_proximas_vencer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_membresias_proximas_vencer`  AS SELECT `m`.`id_miembro` AS `id_miembro`, concat(`m`.`nombres`,' ',`m`.`apellidos`) AS `nombre_completo`, `tm`.`nombre` AS `tipo_membresia`, `mem`.`fecha_fin` AS `fecha_fin`, to_days(`mem`.`fecha_fin`) - to_days(curdate()) AS `dias_restantes`, `m`.`correo_electronico` AS `correo_electronico`, `m`.`telefono` AS `telefono` FROM ((`miembros` `m` join `membresias` `mem` on(`m`.`id_miembro` = `mem`.`id_miembro`)) join `tiposmembresia` `tm` on(`mem`.`id_tipo_membresia` = `tm`.`id_tipo_membresia`)) WHERE `mem`.`estado_pago` = 'pagado' AND `mem`.`fecha_fin` between curdate() and curdate() + interval 15 day ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos_stock_bajo`
--
DROP TABLE IF EXISTS `vw_productos_stock_bajo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos_stock_bajo`  AS SELECT `p`.`id_producto` AS `id_producto`, `p`.`nombre` AS `nombre`, `p`.`cantidad_stock` AS `cantidad_stock`, `p`.`stock_minimo` AS `stock_minimo`, `c`.`nombre` AS `categoria`, `pr`.`nombre` AS `proveedor`, CASE WHEN `p`.`cantidad_stock` = 0 THEN 'agotado' WHEN `p`.`cantidad_stock` <= `p`.`stock_minimo` THEN 'bajo' ELSE 'suficiente' END AS `estado_stock` FROM ((`productos` `p` left join `categoriasproductos` `c` on(`p`.`id_categoria` = `c`.`id_categoria`)) left join `proveedores` `pr` on(`p`.`id_proveedor` = `pr`.`id_proveedor`)) WHERE `p`.`cantidad_stock` <= `p`.`stock_minimo` AND `p`.`activo` = 1 ;

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
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

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
  ADD KEY `idx_historial_acceso` (`id_miembro`,`hora_entrada`),
  ADD KEY `idx_accesos_miembro` (`id_miembro`,`hora_entrada`);

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
  ADD KEY `idx_membresia_fechas` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `fk_membresia_padre` (`id_membresia_padre`),
  ADD KEY `fk_membresia_promocion` (`id_promocion`),
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
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoriasproductos`
--
ALTER TABLE `categoriasproductos`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias_clases`
--
ALTER TABLE `categorias_clases`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id_clase` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detallesventa`
--
ALTER TABLE `detallesventa`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id_entrenador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipamiento`
--
ALTER TABLE `equipamiento`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `excepciones_clases`
--
ALTER TABLE `excepciones_clases`
  MODIFY `id_excepcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historialaccesos`
--
ALTER TABLE `historialaccesos`
  MODIFY `id_acceso` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id_miembro` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tiposmembresia`
--
ALTER TABLE `tiposmembresia`
  MODIFY `id_tipo_membresia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuariossistema`
--
ALTER TABLE `usuariossistema`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_membresia_padre` FOREIGN KEY (`id_membresia_padre`) REFERENCES `membresias` (`id_membresia`),
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

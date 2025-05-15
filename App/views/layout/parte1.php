<?php

ob_start(); // Inicia el buffer de salida
// Verificar si la sesión está iniciada, si no, iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*
// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['sesion_usuario'])) {
    header('Location: ../login/index.php');
    exit();
}
*/

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administración Gym</title>
    <link rel="icon" href="<?php echo $URL; ?>/public/images/logo.ico" type="image/x-icon">

    <script src="<?php echo $URL; ?>/public/css/darkmode.js"></script>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">


    <!-- SweetAlert2-->
    <script src="<?php echo $URL; ?>public/templates/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.all.min.js">
    </script>
    <link rel="stylesheet"
        href="<?php echo $URL; ?>public/templates/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.css">


    <!-- DataTables -->
    <link rel="stylesheet"
        href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">


    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet"
        href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="<?php echo $URL; ?>/public/css/custom.css">

    <!-- jQuery (usa la CDN como respaldo) -->
    <script src="<?php echo $URL; ?>public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <script>
    if (typeof jQuery == 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    }
    </script>


    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- FullCalendar (AdminLTE 3 plugin) -->
    <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.css">
    <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper" id="content-wrapper">

        <?php include('navbar.php'); ?>


        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="nav-link brand-link d-flex flex-column align-items-center">
                <div class="logo-img rounded-circle overflow-hidden shadow-lg mb-2">
                    <img src="<?php echo $URL; ?>/public/images/logo1.png" alt="KEMUEL S.A.S Logo"
                        class="img-fluid h-100 w-100" style="object-fit: cover;">
                </div>
                <span class="brand-text text-white text-center">
                    <span class="font-weight-bold d-block" style="font-size: 0.9em">GYM</span>
                    <span class="font-weight-light d-block" style="font-size: 0.6em">MambaCode</span>
                </span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php
                                    echo !empty($_SESSION['foto_usuario'])
                                        ? $URL . '/' . $_SESSION['foto_usuario']
                                        : $URL . '/img/empleados/default.png';
                                    ?>" class="img-circle elevation-2" alt="User Image"
                            style="width: 34px; height: 34px; object-fit: cover;">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <?php echo $_SESSION['nombre_relacionado'] ?? 'Usuario'; ?>
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- Opción solo para Administradores (rol_id = 1) -->

                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>index.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>

                        <!-- Falta corregir la Logica para mostrar y ocultar las ventanas -->


                        <!--Clientes-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Miembros <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>empleados/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Miembros</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>empleados/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Miembro</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>empleados/inactivos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Miembros Inhabilitados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Clientes-->

                        <!--Membresias-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>
                                    Membresias
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Membresias</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Membresia</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Renovar Membresia</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Membresias Inhabilitadas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Membresias-->
                        <!--Membresias-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-gem"></i>
                                <p>
                                    Tipos Membresias
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias_tipo/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado Tipos Membresias</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/membresias_tipo/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Tipo Membresia</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Membresias-->


                        <!--Clases-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Clases
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/calendar.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Calendario de Clases</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Clases</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Clase</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/enroll.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Inscripción a Clases</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/attendance.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Asistencia a Clases</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/clases/inactive.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Clases Inhabilitadas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!--Fin Clases-->

                        <!--Categorias Clases-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p>
                                    Categorias Clases
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/categorias_clases/index_categorias.php"
                                        class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado Categorias</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/categorias_clases/create_categoria.php"
                                        class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Categorias</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!--Fin Categorias Clases-->



                        <!--Salas-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-building"></i>

                                <p>
                                    Salas
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/salas/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Salas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/salas/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Sala</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/salas/inactive.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Salas Inhabilitadas</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!--Fin Clases-->




                        <!--Entrenadores-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-dumbbell"></i>
                                <p>
                                    Entrenadores
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/entrenadores/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Entrenadores</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/entrenadores/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Entrenador</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>clientes/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Asignar Clase</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>clientes/inactivos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Entrenadores Inhabilitadas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Entrenadores-->



                        <!--Marcacion Entrada-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>Marcacion Entrada <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>mantenimientos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Registrar Ingreso</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>mantenimientos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Historial de Ingresos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Marcacion Entrada-->



                        <!--Productos-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>Productos <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/productos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Productos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/productos/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Producto</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/productos/inactive.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Productos Inhabilitados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Productos-->



                        <!--Ventas-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>Ventas <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>ordenServicio/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Registrar Venta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>ordenServicio/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Historial de Ventas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Ventas -->

                        <!--Pagos-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>
                                    Pagos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>rutas/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Historial de pagos</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!--Fin Pagos-->


                        <!--Proveedores-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-handshake"></i>
                                <p>
                                    Proveedores
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/proveedores/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado Proveedores</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/proveedores/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agregar Proveedor</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/proveedores/index_compras.php"
                                        class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Compras</p> <!-- Con reporte de todo lo comprado -->
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/proveedores/inactive.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Proveedores Inhabilitados</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!--Fin Proveedores-->

                        <!--Usuarios sistema-->

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>
                                    Usuarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/usuarios/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Usuarios</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/usuarios/create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Usuario</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>App/views/usuarios/inactive.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usuarios Inhabilitados</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Usuarios-->


                        <!--Reportes y Estadisticas-->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Reportes / Estadisticas <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>mantenimientos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de reportes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $URL; ?>mantenimientos/index.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ver Estadisticas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--Fin Reportes y estadistica-->

                        <!--Cerrar Session-->
                        <li class="nav-item">
                            <a href="#" class="nav-link text-danger" onclick="confirmarLogout(event)">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Cerrar Sesión</p>
                            </a>
                        </li>
                        <!--Fin Cerrar Session-->
                    </ul>
                </nav>
            </div>
        </aside>



        <style>
        .brand-link .logo-img {
            width: 80px;
            height: 80px;
            transition: all 0.3s ease;
        }

        .sidebar-mini.sidebar-collapse .brand-link {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center;
        }

        .sidebar-mini.sidebar-collapse .brand-link .logo-img {
            width: 40px;
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .sidebar-mini.sidebar-collapse .brand-link .brand-text {
            display: none;
        }
        </style>

        <?php
        include(__DIR__ . '../modalsesion.php');
        include(__DIR__ . '../btndark.php');
        ?>
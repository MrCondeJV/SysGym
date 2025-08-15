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
    <title>Dashboard | ESFIM</title>
    <link rel="icon" href="<?php echo $URL; ?>/public/images/efim.png" type="image/x-icon">

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
    <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.all.min.js">
    </script>
    <link rel="stylesheet"
        href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.css">


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
    <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <script>
        if (typeof jQuery == 'undefined') {
            document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
        }
    </script>

    <!-- Select2 CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- FullCalendar (AdminLTE 3 plugin) -->
    <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.css">
    <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fullcalendar/main.min.js"></script>
    <style>
        /* --- SIDEBAR --- */
        .main-sidebar {
            background: linear-gradient(180deg, #232526 0%, #414345 100%);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
        }

        .main-sidebar .brand-link {
            padding: 0.8rem 0.5rem 0.5rem 0.5rem;
            background: #232526;
            border-bottom: 1px solid #2c2c2c;
        }

        .main-sidebar .logo-img {
            width: 44px !important;
            height: 44px !important;
            border-radius: 50%;
            border: 2px solid #15297C;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .main-sidebar .brand-text {
            font-size: 1em !important;
            color: #fff !important;
            letter-spacing: 1px;
            margin-top: 0.2rem;
        }

        .nav-sidebar .nav-link {
            padding: 0.38rem 1rem !important;
            font-size: 0.93rem;
            color: #e0e0e0;
            border-radius: 0.28rem;
            margin-bottom: 0.08rem;
            min-height: 34px;
            transition: background 0.18s, color 0.18s;
        }

        /* Cambios aquí: iconos gris claro y acento blanco translúcido */
        .nav-sidebar .nav-icon {
            font-size: 1.15rem !important;
            margin-right: 0.65rem;
            color: #e0e0e0 !important;
            /* Gris claro */
            transition: color 0.18s;
        }

        .nav-sidebar .nav-link.active,
        .nav-sidebar .nav-link:hover,
        .nav-sidebar .nav-link:focus {
            background: rgba(255, 255, 255, 0.13) !important;
            /* Blanco translúcido */
            color: #fff !important;
            font-weight: 600;
        }

        .nav-sidebar .nav-link.active .nav-icon,
        .nav-sidebar .nav-link:hover .nav-icon {
            color: #fff !important;
        }

        .nav-sidebar .nav-treeview .nav-link {
            padding-left: 2.1rem !important;
            font-size: 0.89rem;
            color: #b2bec3;
            background: transparent;
        }

        .nav-sidebar .nav-treeview .nav-link.active,
        .nav-sidebar .nav-treeview .nav-link:hover {
            background: rgba(255, 255, 255, 0.10) !important;
            color: #fff !important;
        }

        .sidebar .nav-sidebar>.nav-item {
            margin-bottom: 0.06rem;
        }

        .sidebar::-webkit-scrollbar {
            width: 7px;
            background: #232526;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #15297C;
            border-radius: 4px;
        }

        /* --- NAVBAR --- */
        .main-header.navbar {
            min-height: 46px !important;
            background: linear-gradient(180deg, #232526 0%, #414345 100%) !important;
            border-bottom: 3px solid #15297C;
            box-shadow: 0 2px 8px rgba(14, 148, 160, 0.10);
            padding-top: 0.1rem;
            padding-bottom: 0.1rem;
        }

        .main-header .navbar-nav .nav-link {
            font-size: 0.93rem;
            padding-top: 0.18rem !important;
            padding-bottom: 0.18rem !important;
            color: #fff !important;
            border-radius: 0.22rem;
            transition: background 0.18s, color 0.18s;
        }

        .main-header .navbar-nav .nav-link:hover,
        .main-header .navbar-nav .show>.nav-link {
            background: rgba(44, 44, 44, 0.13);
            color: #fff !important;
        }

        .main-header .navbar-nav .dropdown-menu {
            font-size: 0.95rem;
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(44, 44, 44, 0.10);
            border: none;
            background: #232526;
            color: #fff;
        }

        .main-header .navbar-nav .dropdown-item:active,
        .main-header .navbar-nav .dropdown-item:focus,
        .main-header .navbar-nav .dropdown-item:hover {
            background: linear-gradient(90deg, #0e94a0, #0bbffb) !important;
            color: #fff !important;
        }

        .main-header .navbar-nav .img-circle {
            width: 28px !important;
            height: 28px !important;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(11, 191, 251, 0.13);
            object-fit: cover;
        }

        .badge.navbar-badge {
            background: #fff;
            color: #0e94a0;
            font-weight: bold;
        }

        /* Mejor alineación de íconos */
        .main-header .navbar-nav .nav-link,
        .nav-sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        .content-wrapper {
            padding-top: 56px;
            /* Ajusta según la altura real de tu navbar */
        }
    </style>

    <style>
        /* Dropdown usuario mejor integrado */
        .main-header .navbar-nav .dropdown-menu {
            background: #232526;
            color: #fff;
            border: none;
            box-shadow: 0 4px 16px rgba(14, 148, 160, 0.13);
            min-width: 210px;
            padding: 0.5rem 0;
        }

        .main-header .navbar-nav .dropdown-item {
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.2rem;
            transition: background 0.18s, color 0.18s;
        }

        .main-header .navbar-nav .dropdown-item:active,
        .main-header .navbar-nav .dropdown-item:focus,
        .main-header .navbar-nav .dropdown-item:hover {
            background: linear-gradient(90deg, #0e94a0, #0bbffb) !important;
            color: #fff !important;
        }

        .main-header .navbar-nav .dropdown-item.text-danger {
            color: #ff7675 !important;
        }

        .main-header .navbar-nav .dropdown-item.text-danger:hover {
            color: #fff !important;
        }

        .main-header .navbar-nav .dropdown-item-text {
            color: #0bbffb;
            font-size: 1.05em;
            text-align: center;
            padding-bottom: 0.3rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

    <div class="wrapper" id="content-wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark bg-dark shadow-sm mb-0 fixed-top">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Menú lateral">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-light navbar-badge" id="notification-counter">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                            style="max-height: 500px; overflow-y: auto;">
                            <span class="dropdown-header" id="notification-header">0 Notificaciones</span>
                            <div class="dropdown-divider"></div>
                            <div id="notification-list" class="px-3">
                                <!-- Notificaciones dinámicas -->
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer" id="mark-all-read">Marcar todas como
                                leídas</a>
                        </div>
                    </li>


                    <!-- Dark Mode Toggle -->
                    <li class="nav-item d-flex align-items-center">
                        <span class="mr-2 text-dark" aria-label="Modo oscuro">🌙</span>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="darkModeSwitch"
                                aria-label="Activar/desactivar modo oscuro">
                            <label class="custom-control-label" for="darkModeSwitch"></label>
                        </div>
                    </li>

                    <!-- Usuario actual -->
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            <img src="<?php
                                        echo !empty($_SESSION['foto_usuario'])
                                            ? $URL . '/' . $_SESSION['foto_usuario']
                                            : $URL . 'public/images/efim.png';
                                        ?>" class="img-circle elevation-2 mr-2" alt="User Image"
                                style="width: 32px; height: 32px; object-fit: cover;">
                            <span class="d-none d-md-inline text-white font-weight-bold">
                                <?php echo htmlspecialchars($_SESSION['nombres'] . " " . $_SESSION['apellidos'] ?? 'Usuario'); ?>
                            </span>
                            <i class="fas fa-angle-down ml-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <span class="dropdown-item-text font-weight-bold">
                                <?php echo htmlspecialchars($_SESSION['nombres'] . " " . $_SESSION['apellidos'] ?? 'Usuario'); ?>
                            </span>
                            <div class="dropdown-divider"></div>
                            <?php if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3): ?>
                                <a href="<?php echo $URL; ?>App/views/usuarios/update.php?id=<?php echo $_SESSION['id_usuario']; ?>"
                                    class="dropdown-item">
                                    <i class="fas fa-user-cog mr-2"></i> Mi perfil
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a href="<?php echo $URL; ?>App/controllers/login/cerrar_sesion.php"
                                class="dropdown-item text-danger" onclick="confirmarLogout(event)">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>


        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="nav-link brand-link d-flex flex-column align-items-center">
                <div class="logo-img rounded-circle overflow-hidden shadow-lg mb-2">
                    <img src="<?php echo $URL; ?>/public/images/efim.png" alt="ESFIM Logo"
                        class="img-fluid h-100 w-100" style="object-fit: cover;">
                </div>
                
            </a>

            <div class="sidebar">


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-icons-only" role="menu">
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/index.php" class="nav-link" title="Dashboard">
                                <i class="nav-icon fas fa-tachometer-alt sidebar-icon-lg"></i>
                                <div class="sidebar-label">Dashboard</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/App/views/calendario/index.php" class="nav-link" title="Calendario">
                                <i class="nav-icon fas fa-calendar-alt sidebar-icon-lg"></i>
                                <div class="sidebar-label">Calendario</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/App/views/solicitudes/index.php" class="nav-link" title="Tickets">
                                <i class="nav-icon fas fa-ticket-alt sidebar-icon-lg"></i>
                                <div class="sidebar-label">Solicitudes</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/App/views/historial/index.php" class="nav-link" title="Historial">
                                <i class="nav-icon fas fa-history sidebar-icon-lg"></i>
                                <div class="sidebar-label">Historial</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/App/views/usuarios/index.php" class="nav-link" title="Usuarios">
                                <i class="nav-icon fas fa-users sidebar-icon-lg"></i>
                                <div class="sidebar-label">Usuarios</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo $URL; ?>/App/views/salas/index.php" class="nav-link" title="Salas">
                                <i class="nav-icon fas fa-door-open sidebar-icon-lg"></i>
                                <div class="sidebar-label">Salas</div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>



        <style>
        /* Sidebar icon-only style */
        .nav-icons-only .nav-link {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 1.1rem 0 !important;
            min-height: 72px;
            gap: 0.18rem;
        }
        .nav-icons-only .nav-link .sidebar-icon-lg {
            width: 40px;
            height: 40px;
            font-size: 2.2rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            line-height: 1;
            background: none;
        }
        .nav-icons-only .sidebar-label {
            display: block;
            font-size: 0.78rem;
            color: #e0e0e0;
            text-align: center;
            margin: 0;
            letter-spacing: 0.01em;
            font-weight: 500;
            line-height: 1.1;
            pointer-events: none;
            user-select: none;
            width: 100%;
        }
        .nav-icons-only .nav-link span,
        .nav-icons-only .nav-link p {
            display: none !important;
        }
        .nav-icons-only .sidebar-label {
            display: block;
            font-size: 0.78rem;
            color: #e0e0e0;
            text-align: center;
            margin: 0;
            letter-spacing: 0.01em;
            font-weight: 500;
            line-height: 1.1;
            pointer-events: none;
            user-select: none;
        }
        .nav-icons-only .nav-item {
            margin-bottom: 0.2rem;
        }
        .nav-icons-only {
            margin-top: 1.5rem;
        }
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
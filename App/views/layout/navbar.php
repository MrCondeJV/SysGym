
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark bg-dark shadow-sm mb-0">
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
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 500px; overflow-y: auto;">
                    <span class="dropdown-header" id="notification-header">0 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <div id="notification-list" class="px-3">
                        <!-- Notificaciones dinámicas -->
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="mark-all-read">Marcar todas como leídas</a>
                </div>
            </li>

            <!-- Sonido -->
            

            <!--Mantenimiento-->

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-tools"></i> <!-- Ícono diferente para mantenimiento -->
                    <span class="badge badge-warning navbar-badge" id="maintenance-counter">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 500px; overflow-y: auto;">
                    <span class="dropdown-header" id="maintenance-header">0 Recordatorios</span>
                    <div class="dropdown-divider"></div>
                    <div id="maintenance-list" class="px-3">
                        <!-- Recordatorios dinámicos -->
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="mark-maintenance-read">Marcar todos como leídos</a>
                </div>
            </li>

            <!-- Dark Mode Toggle -->
            <li class="nav-item d-flex align-items-center">
                <span class="mr-2 text-dark" aria-label="Modo oscuro">🌙</span>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="darkModeSwitch" aria-label="Activar/desactivar modo oscuro">
                    <label class="custom-control-label" for="darkModeSwitch"></label>
                </div>
            </li>
        </ul>
    </div>
</nav>


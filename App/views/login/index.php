
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Transportes</title>
    <link rel="icon" href="/public/images/logo.ico" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/sysgym/public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/sysgym/public/templates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/sysgym/public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="stylelogin.css">
</head>

<body>
    <div class="login-box">
        <div class="login-logo">
            <img src="/sysgym/public/images/logo1.png" alt="Logo">
            <h3 class="mt-2"><b>Sistema de Administracion Gym</b></h3>
        </div>

        <?php
        session_start();
        if (isset($_SESSION['sesion_usuario'])) {
            header('Location: ../../');
            exit();
        }
        if (isset($_SESSION['mensaje'])) {
            echo "<script>
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '" . $_SESSION['mensaje'] . "',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <div class="card card-outline card-light">
            <div class="card-body">
                <p class="login-box-msg">Ingrese sus credenciales</p>
                <form action="../../controllers/login/ingreso.php" method="post" autocomplete="on">
                    <div class="input-group mb-3">
                        <input type="text" name="nombre_usuario" class="form-control" placeholder="Nombre de usuario"
                            required autocomplete="username">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_user" class="form-control" placeholder="Contraseña"
                            required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/sysgym/public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/sysgym/public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-messaging-compat.js"></script>
    <!-- Firebase -->
    <script src="<?php echo $URL; ?>/app/controllers/firebase/script.js"></script>

    <script>
    $(document).ready(function() {
        if (localStorage.getItem("sessionExpired") === "true") {
            Swal.fire({
                title: '<i class="fas fa-exclamation-triangle"></i> Sesión expirada',
                html: 'Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.',
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check"></i> Aceptar',
                confirmButtonColor: '#d33',
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: 'rgba(0,0,0,0.7)'
            }).then(() => {
                localStorage.removeItem("sessionExpired"); // Limpia el flag
                window.location.href = window.location.origin +
                    "/SysGym/app/views/login/index.php"; // Redirige al login
            });
        }
    });
    </script>

    <!-- Cargar el script principal al final para evitar errores -->
    <script src="main.js"></script>
</body>

</html>
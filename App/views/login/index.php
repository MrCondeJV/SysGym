<?php
session_start();
if (isset($_SESSION['sesion_usuario'])) {
    header('Location: ../../');
    exit();
}

// --- BLOQUE DE SEGURIDAD DE INTENTOS ---
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_lock_time'] = 0;
}

$lock_seconds = 30;
$max_attempts = 3;
$now = time();
$wait_message = null;

// Si está bloqueado, calcula el tiempo restante
if ($_SESSION['login_lock_time'] > $now) {
    $wait = $_SESSION['login_lock_time'] - $now;
    $wait_message = "Demasiados intentos fallidos. Por favor espera $wait segundos para volver a intentar.";
} else {
    // Si ya pasó el tiempo de bloqueo, resetea los intentos
    if ($_SESSION['login_lock_time'] > 0) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_lock_time'] = 0;
    }
}

// Al procesar el login (esto normalmente va en tu controlador, pero aquí va el ejemplo para mostrar el mensaje)
$mensaje = null;
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
    // Si el login fue fallido, aumenta el contador
    if ($mensaje === "Credenciales incorrectas") {
        $_SESSION['login_attempts']++;
        // Si supera el máximo, bloquea y aumenta el tiempo de espera progresivamente
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $bloqueos = floor($_SESSION['login_attempts'] / $max_attempts);
            $_SESSION['login_lock_time'] = $now + ($lock_seconds * $bloqueos);
            $wait = $_SESSION['login_lock_time'] - $now;
            $wait_message = "Demasiados intentos fallidos. Por favor espera $wait segundos para volver a intentar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gym</title>
    <link rel="icon" href="/public/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap">
    <link rel="stylesheet" href="/SysGym/public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/SysGym/public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #video-bg {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100vw;
            min-height: 100vh;
            width: auto;
            height: auto;
            z-index: -2;
            object-fit: cover;
            filter: brightness(0.7) blur(1px);
        }

        #gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: radial-gradient(650px circle at 0% 0%, rgba(25, 118, 210, 0.5) 15%, rgba(25, 118, 210, 0.3) 35%, rgba(11, 191, 251, 0.2) 75%, rgba(11, 191, 251, 0.1) 80%, transparent 100%),
                radial-gradient(1250px circle at 100% 100%, rgba(25, 118, 210, 0.5) 15%, rgba(25, 118, 210, 0.3) 35%, rgba(11, 191, 251, 0.2) 75%, rgba(11, 191, 251, 0.1) 80%, transparent 100%);
            z-index: -1;
        }

        /* Estructura principal para evitar scroll */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 120px); /* Resta la altura del footer */
            padding: 20px 0;
        }

        .bg-glass {
            background-color: hsla(0, 0%, 100%, 0.90) !important;
            backdrop-filter: saturate(200%) blur(25px);
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(25, 118, 210, 0.18);
        }

        .info-pago {
            color: rgb(255, 255, 255);
        }

        .info-pago .btn {
            border-radius: 30px;
        }

        .info-pago .btn-light {
            color: #1976d2;
            font-weight: bold;
        }

        .info-pago .btn-light:hover {
            background: #1976d2;
            color: #fff;
        }

        /* Footer fijo en la parte inferior */
        .login-footer {
            margin-top: auto;
            background: rgba(25, 118, 210, 0.10);
            border-top: 1px solid #e3e3e3;
            padding: 15px 0;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .login-footer .footer-text {
            font-size: 1rem;
            color: #e3eafc;
            margin-bottom: 5px;
        }

        .login-footer .footer-dev {
            font-size: 0.95rem;
            color: #f8f9fa;
        }

        @media (max-width: 991px) {
            .bg-glass {
                border-radius: 1.2rem;
            }
            
            .main-content {
                min-height: calc(100vh - 100px);
                padding: 15px 0;
            }
        }

        @media (max-width: 768px) {
            .bg-glass {
                border-radius: 0.7rem;
            }

            .main-content {
                min-height: calc(100vh - 90px);
                padding: 10px 0;
            }

            .login-footer .footer-text {
                font-size: 0.9rem;
            }

            .login-footer .footer-dev {
                font-size: 0.85rem;
            }

            .info-pago h3,
            .info-pago h4 {
                font-size: 1.1rem;
            }

            .info-pago p,
            .info-pago small {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                min-height: calc(100vh - 80px);
                padding: 5px 0;
            }

            .login-footer {
                padding: 10px 0;
            }

            .login-footer .footer-text {
                font-size: 0.85rem;
            }

            .login-footer .footer-dev {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Video de fondo -->
    <video id="video-bg" autoplay muted loop>
        <source src="/SysGym/public/videos/video.mp4" type="video/mp4">
        Tu navegador no soporta el video de fondo.
    </video>
    <!-- Gradiente encima del video -->
    <div id="gradient-bg"></div>

    <div class="main-content">
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start">
            <div class="row gx-lg-5 align-items-center mb-5 justify-content-center">
                <!-- Info de pago (izquierda) -->
                <div class="col-lg-5 mb-5 mb-lg-0 info-pago">
                   
                    <h1 class="fw-bold mb-3">¡Hazte miembro!</h1>
                    <h2 class="mb-3"><i class="fas fa-credit-card"></i> Pago de Membresía</h2>
                    <p class="mb-4">
                        Accede a todos los beneficios, clases y zonas exclusivas.<br>
                        ¡Haz tu pago de membresía en línea de forma fácil y segura!
                    </p>
                    <a href="https://www.zonapagos.net/formulariosNV/?cod=BTK3a53qAPJK5dFzuUb9xsIzfpnpHCAhBLb588U2ARZAoy3ei0%2FDcFxcc8D82hku4RRINKB1Jvp8TQjAnQ2jFqpPmEJ8XgQHIWF4xwOUsT1S7stXzfxEZv3USAH0vdODvJbaMjt1h6Ata51BYT8vbzXtRVqnFFpOqMtBKwqDEsa9Sqkj9d2lm0t8x6Fmwer7L%2F%2BSwl4%2FUN9BoockmksSefLwBZPZdZwnYCZZn5FHubReD4Auj%2F%2FUIXh9lG%2FK%2Ba%2FAj%2FHDwxVvEvv4VO47q25Gog%3D%3D"
                        class="btn btn-light font-weight-bold mb-3" target="_blank" rel="noopener">
                        <i class="fas fa-arrow-right"></i> Pagar Membresía
                    </a>
                    <div>
                        <small>¿Tienes dudas? <a href="mailto:info@gym.com"
                                style="color:#1976d2;text-decoration:underline;">Contáctanos</a></small>
                    </div>
                </div>
                <!-- Login (derecha) -->
                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div class="bg-glass p-4 p-md-5">
                        <div class="text-center mb-4">
                            <img src="/SysGym/public/images/efim.png" alt="Logo" style="width:120px;">
                            <h3 class="mt-2 mb-0" style="color:#1976d2;"><b>Sistema de Administración Gym</b></h3>
                        </div>
                        <div class="card card-outline card-light" style="box-shadow:20px; border:none;">
                            <div class="card-body">
                                <p class="login-box-msg" style="font-size:1.1rem; color:#1976d2; font-weight:600;">
                                    <i class="fas fa-sign-in-alt"></i> Ingresa a tu cuenta
                                </p>
                                <?php if ($wait_message): ?>
                                <div class="alert alert-warning text-center" id="wait-message">
                                    <?= $wait_message ?>
                                </div>
                                <script>
                                    // Deshabilita el formulario mientras espera
                                    document.addEventListener('DOMContentLoaded', function() {
                                        let btn = document.querySelector('button[type="submit"]');
                                        if(btn) btn.disabled = true;
                                        let wait = <?= $_SESSION['login_lock_time'] - $now ?>;
                                        let msg = document.getElementById('wait-message');
                                        let interval = setInterval(function() {
                                            wait--;
                                            if (wait > 0) {
                                                msg.innerText = "Demasiados intentos fallidos. Por favor espera " + wait + " segundos para volver a intentar.";
                                            } else {
                                                clearInterval(interval);
                                                msg.style.display = "none";
                                                if(btn) btn.disabled = false;
                                            }
                                        }, 1000);
                                    });
                                </script>
                                <?php endif; ?>
                                <form action="/SysGym/App/controllers/login/ingreso.php" method="post" autocomplete="on" <?= $wait_message ? 'onsubmit="return false;"' : '' ?>>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nombre_usuario" class="form-control border-start-0"
                                            placeholder="Nombre de usuario" required autocomplete="username" <?= $wait_message ? 'disabled' : '' ?>>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password_user" class="form-control border-start-0"
                                            placeholder="Contraseña" required autocomplete="current-password" <?= $wait_message ? 'disabled' : '' ?>>
                                        <span class="input-group-text bg-white border-start-0" style="cursor:pointer;"
                                            onclick="togglePassword(this)">
                                            <i class="fas fa-eye" id="togglePassIcon"></i>
                                        </span>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block w-100 mb-2"
                                        style="font-weight:600;" <?= $wait_message ? 'disabled' : '' ?>>
                                        <i class="fas fa-arrow-right"></i> Ingresar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/SysGym/public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/SysGym/public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-messaging-compat.js"></script>
 
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
                    localStorage.removeItem("sessionExpired");
                    window.location.href = window.location.origin +
                        "/SysGym/App/views/login/index.php";
                });
            }
        });
    </script>

    <!-- En el formulario de login, muestra el mensaje si existe -->
    <?php if ($mensaje): ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: '<?= addslashes($mensaje) ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>
    <script>
    function togglePassword(el) {
        const input = el.parentElement.querySelector('input[type="password"],input[type="text"]');
        const icon = el.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    </script>

    <!-- Footer -->
    <footer class="login-footer">
        <div class="footer-text">
            &copy; <?php echo date('Y'); ?> DITIC ESFIM. Todos los derechos reservados.
        </div>
        <div class="footer-dev">
            Desarrolladores del proyecto: <span id="devs">Ing. Luis Barrios</span>
        </div>
    </footer>
</body>
</html>
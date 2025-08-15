<script>
  $(document).ready(function () {
    let sessionDuration = 9000; // 15 minutos
    let warningTime = 60;      // 1 minuto de advertencia

    let warningTimer, logoutTimer, countdownInterval, sessionCountdownInterval;
    let userActiveTimeout;
    let warningShown = false;

    function formatTime(seconds) {
      const minutes = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    function startSessionTimers() {
      //console.log("Temporizador de sesión iniciado (15 minutos)");

      clearTimeout(warningTimer);
      clearTimeout(logoutTimer);
      clearInterval(countdownInterval);
      clearInterval(sessionCountdownInterval);

      let sessionSecondsLeft = sessionDuration;
      sessionCountdownInterval = setInterval(() => {
        //console.log(`⏳ Tiempo restante de sesión: ${formatTime(sessionSecondsLeft)}`);
        sessionSecondsLeft--;
        if (sessionSecondsLeft <= 0) {
          clearInterval(sessionCountdownInterval);
        }
      }, 1000);

      warningTimer = setTimeout(() => {
        showWarningAlert();
        startWarningCountdown();
      }, (sessionDuration - warningTime) * 1000);

      logoutTimer = setTimeout(() => {
        closeSession();
      }, sessionDuration * 1000);
    }

    function showWarningAlert() {
      if (warningShown) return;
      warningShown = true;

      Swal.fire({
        title: '<i class="fas fa-exclamation-triangle"></i> Advertencia de sesión',
        html: 'Tu sesión expirará en <span id="swalCountdown">60</span> segundos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-sync-alt"></i> Seguir en la sesión',
        cancelButtonText: 'Cerrar sesión',
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        allowOutsideClick: false,
        allowEscapeKey: false,
        reverseButtons: true,
        willClose: () => {
          clearInterval(countdownInterval);
          warningShown = false;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          renewSession();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          closeSession();
        }
      });
    }

    function startWarningCountdown() {
      let warningLeft = warningTime;
      countdownInterval = setInterval(() => {
        warningLeft--;
        const countdownEl = document.getElementById('swalCountdown');
        if (countdownEl) {
          countdownEl.textContent = warningLeft;
        }
        if (warningLeft <= 0) {
          clearInterval(countdownInterval);
          Swal.close();
          closeSession();
        }
      }, 1000);
    }

    function closeSession() {
      //console.log("Cerrando sesión...");
      clearInterval(countdownInterval);
      clearInterval(sessionCountdownInterval);
      localStorage.setItem("sessionExpired", "true");
      window.location.href = window.location.origin + "/SysGym/App/views/login/index.php";
    }

    function renewSession() {
      //console.log("Renovando sesión...");
      fetch("http://localhost/SysGym/App/views/layout/sesion.php?renew=true")
        .then(response => response.json())
        .then(data => {
          console.log("Respuesta del servidor:", data);
          if (data.status === "success") {
            Swal.fire({
              title: '¡Sesión renovada!',
              text: 'Tu sesión ha sido extendida por 15 minutos más',
              icon: 'success',
              timer: 2000,
              showConfirmButton: false
            });
            startSessionTimers();
          } else {
            Swal.fire({
              title: 'Error',
              text: 'No se pudo renovar la sesión. Serás redirigido.',
              icon: 'error'
            }).then(() => {
              closeSession();
            });
          }
        })
        .catch(error => {
          console.error("Error al contactar con el servidor:", error);
          Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo verificar tu sesión. Intenta nuevamente.',
            icon: 'error'
          });
        });
    }

    // Iniciar temporizador al cargar
    startSessionTimers();

    // Controlar actividad del usuario y reiniciar temporizador cada 60 segundos como máximo
    $(document).on('click keydown scroll', () => {
      clearTimeout(userActiveTimeout);
      userActiveTimeout = setTimeout(() => {
        startSessionTimers();
      }, 60000); // 60 segundos
    });
  });
</script>

<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">

    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 <a>DITIC ESFIM</a>. - </strong>Desarrollado por: <strong>Ing. Luis Barrios</strong>. All rights reserved.
</footer>
</div>
<!-- ./wrapper -->

<!--cierre de sesion-->
<script>
function confirmarLogout(event) {
    event.preventDefault(); // Evita que el enlace redirija inmediatamente

    Swal.fire({
        title: "¿Seguro que deseas cerrar sesión?",
        text: "Tendrás que volver a iniciar sesión para acceder nuevamente.",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: "#ff4d4d",
        cancelButtonColor: "#6c757d",
        confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Sí, salir',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        background: document.body.classList.contains('dark-mode') ? '#343a40' : '#fff',
        color: document.body.classList.contains('dark-mode') ? '#f8f9fa' : '#212529',
        customClass: {
            popup: 'swal2-theme-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?php echo rtrim($URL, '/'); ?>/App/controllers/login/cerrar_sesion.php";

        }
    });
}
</script>


<!-- Bootstrap 4 -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
<!-- jQuery UI para autocomplete -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script
    src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js">
</script>
<script
    src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js">
</script>
<script
    src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js">
</script>
<script
    src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js">
</script>
<script
    src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js">
</script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js">
</script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js">
</script>


<!-- Alerta Accesos denegados -->

<?php if (isset($_SESSION['error_toast'])): ?>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/toastr/toastr.min.js"></script>
<link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/toastr/toastr.min.css">

<script>
$(document).ready(function() {
    toastr.error('<?php echo $_SESSION['error_toast']; ?>', 'Acceso denegado', {
        closeButton: true,
        progressBar: true,
        timeOut: 5000
    });
});
</script>


<?php unset($_SESSION['error_toast']); ?>
<?php endif; ?>
<?php ob_end_flush(); // Vacía el buffer de salida 
?>


</body>

</html>
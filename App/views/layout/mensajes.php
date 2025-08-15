<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['mensaje']) && !empty($_SESSION['icono'])) {
?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                position: 'center',
                icon: '<?php echo $_SESSION['icono']; ?>',
                title: '<?php echo $_SESSION['mensaje']; ?>',
                showConfirmButton: false,
                timer: 2500,
                customClass: {
                    popup: 'swal2-theme-adminlte'
                },
                background: document.body.classList.contains('dark-mode') ? '#343a40' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#f8f9fa' : '#212529'
            });
        });
    </script>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
}
?>



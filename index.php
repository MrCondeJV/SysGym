<?php
include('App/config.php');
#include('app/views/layout/sesion.php');
include('App/views/layout/parte1.php');

?>
<style>
@media (max-width: 576px) {
    .nombre-conductor span {
        display: block;
        /* Fuerza el salto de línea */
        word-break: break-word;
        /* Evita que el texto se salga del contenedor */
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper d-flex align-items-center justify-content-center min-vh-90">
    <!-- Ajustado a min-vh-90 -->
    <!-- Main content -->
    <section class="content w-100">
        <div class="container-fluid">

            <!-- Título de bienvenida -->
            <?php # if ($_SESSION['rol_id'] == 1 || $_SESSION['rol_id'] == 2 || $_SESSION['rol_id'] == 4 || $_SESSION['rol_id'] == 5): 
            ?>
            <div class="row mb-3">
                <div class="col">
                    <p class="m-0 text-white font-weight-bold bg-dark py-3 rounded text-uppercase text-center
                  mt-1 mt-md-3 fs-6 nombre-conductor">
                        BIENVENIDO AL GYMNASIO ESFIM
                    </p>
                </div>
            </div>
            <?php #endif; 
            ?>
        </div>
    </section>
</div>


<?php include('app/views/layout/parte2.php'); ?>
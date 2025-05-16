<?php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

if (isset($_SESSION['mensaje'])): ?>
    <script>
        $(document).ready(function () {
            const swalConfig = {
                icon: '<?php echo $_SESSION['icono']; ?>',
                title: '<?php echo $_SESSION['mensaje']; ?>',
                showConfirmButton: true,
                confirmButtonText: '<?php echo $_SESSION['icono'] == 'success' ? 'Continuar' : 'Reintentar'; ?>',
                confirmButtonColor: '<?php echo $_SESSION['icono'] == 'success' ? '#28a745' : '#dc3545'; ?>'
            };

            Swal.fire(swalConfig).then((result) => {
                <?php if ($_SESSION['icono'] == 'success'): ?>
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo $URL; ?>App/views/categorias_clases/index.php';
                    }
                <?php endif; ?>
            });
        });
    </script>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
endif;
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-tags"></i> Registro de una <span class="font-weight-bold">Nueva Categoría</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar una categoría de clase.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="../../controllers/categorias_clases/create_categoria_clase.php" method="post">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Categoría</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre de la categoría..." required>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="4" placeholder="Escriba una descripción..." required></textarea>
                                </div>

                                <hr>
                                <div class="form-group text-center">
                                    <a href="index_categorias.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php include('../layout/parte2.php'); ?>

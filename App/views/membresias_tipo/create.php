<?php
include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-tags"></i> Registro de una <span class="font-weight-bold">Nueva
                            Membresia</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar una membresia para
                        miembros.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
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
                            <form action="../../controllers/membresias_tipo/create_tipomembership.php" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre de la Membresía</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Ej: Básica" required maxlength="50">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="duracion_dias">Duración (días)</label>
                                        <input type="number" class="form-control" id="duracion_dias"
                                            name="duracion_dias" placeholder="30" min="1" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="precio">Precio (COP)</label>
                                        <input type="number" class="form-control" id="precio" name="precio"
                                            placeholder="100000" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                        placeholder="Descripción de la membresía"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="beneficios">Beneficios</label>
                                    <textarea class="form-control" id="beneficios" name="beneficios" rows="3"
                                        placeholder="Beneficios adicionales"></textarea>
                                </div>

                                <div class="form-group text-right">
                                    <a href="index.php" class="btn btn-secondary mr-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Registrar Membresía</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>
<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/salas/update_sala.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(5, 99, 32),rgb(51, 240, 114)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-door-open fa-lg"></i> Actualizar Sala
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información de la Sala
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos de la Sala</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/salas/update.php" method="post">
                                <input type="hidden" name="id_sala" value="<?= $id_sala; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre de la Sala</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre de la sala..." value="<?= htmlspecialchars($sala['nombre'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="capacidad">Capacidad</label>
                                        <input type="number" name="capacidad" class="form-control" placeholder="Escriba la capacidad..." min="1" value="<?= htmlspecialchars($sala['capacidad'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="descripcion">Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Escriba una descripción..." required><?= htmlspecialchars($sala['descripcion'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group text-center">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
<?php
include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/membresias_tipo/updatelist_tipomembership.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-edit"></i> Editar <span class="font-weight-bold">Tipo de Membresía</span>
                    </h1>
                    <p class="text-muted mt-2">Modifique los datos y guarde los cambios.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Editar:
                                <strong><?php echo htmlspecialchars($tipo['nombre']); ?></strong>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/membresias_tipo/update_tipomembership.php" method="post">
                                <input type="hidden" name="id_tipo_membresia"
                                    value="<?php echo $tipo['id_tipo_membresia']; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre de la Membresía</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" maxlength="50"
                                            value="<?php echo htmlspecialchars($tipo['nombre']); ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="duracion_dias">Duración (días)</label>
                                        <input type="number" class="form-control" id="duracion_dias"
                                            name="duracion_dias" min="1" value="<?php echo $tipo['duracion_dias']; ?>"
                                            required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="precio">Precio (COP)</label>
                                        <input type="number" class="form-control" id="precio" name="precio" min="0"
                                            step="0.01" value="<?php echo $tipo['precio']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion"
                                        rows="3"><?php echo htmlspecialchars($tipo['descripcion']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="beneficios">Beneficios</label>
                                    <textarea class="form-control" id="beneficios" name="beneficios"
                                        rows="3"><?php echo htmlspecialchars($tipo['beneficios']); ?></textarea>
                                </div>

                                <div class="form-group text-right">
                                    <a href="index.php" class="btn btn-secondary mr-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
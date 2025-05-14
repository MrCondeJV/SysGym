<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/categorias_productos/list_categorias.php');
include('../../controllers/proveedores/list_proveedores.php');
//include('../layout/sesion.php');

if (isset($_SESSION['mensaje'])): ?>
    <script>
        $(document).ready(function() {
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
                        window.location.href = '<?php echo $URL; ?>productos/index.php';
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-box"></i> Registro de un <span class="font-weight-bold">Nuevo Producto</span>
                    </h1>
                    <p class="text-muted mt-2">Complete los campos a continuación para registrar un nuevo producto en el sistema.</p>
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
                            <form action="../../controllers/productos/create_producto.php" method="post" enctype="multipart/form-data">
                                <!-- Información básica del producto -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="codigo_producto">Código del Producto</label>
                                        <input type="text" name="codigo_producto" id="codigo_producto" class="form-control" placeholder="Escriba el código del producto..." required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre del Producto</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del producto..." required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="descripcion">Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Escriba la descripción del producto..." required></textarea>
                                    </div>
                                </div>

                                <!-- Precios y stock -->
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="precio">Precio</label>
                                        <input type="number" name="precio" class="form-control" placeholder="Escriba el precio..." step="0.01" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="precio_compra">Precio de Compra</label>
                                        <input type="number" name="precio_compra" class="form-control" placeholder="Escriba el precio de compra..." step="0.01" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cantidad_stock">Cantidad en Stock</label>
                                        <input type="number" name="cantidad_stock" class="form-control" placeholder="Escriba la cantidad en stock..." required>
                                    </div>
                                </div>

                                <!-- Categoría y proveedor -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="id_categoria">Categoría</label>
                                        <select name="id_categoria" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($categorias_datos as $categoria) { ?>
                                                <option value="<?= $categoria['id_categoria']; ?>"><?= $categoria['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_proveedor">Proveedor</label>
                                        <select name="id_proveedor" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($proveedores_datos as $proveedor) { ?>
                                                <option value="<?= $proveedor['id_proveedor']; ?>"><?= $proveedor['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Código de barras y stock mínimo -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="codigo_barras">Código de Barras</label>
                                        <input type="text" name="codigo_barras" id="codigo_barras" class="form-control" placeholder="Se generará automáticamente" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="stock_minimo">Stock Mínimo</label>
                                        <input type="number" name="stock_minimo" class="form-control" placeholder="Escriba el stock mínimo..." required>
                                    </div>
                                </div>

                                <!-- Imagen y estado -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="url_imagen">Imagen del Producto</label>
                                        <input type="file" name="url_imagen" class="form-control" accept="image/*" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="activo">Estado</label>
                                        <select name="activo" class="form-control" required>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group text-center">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
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

<script>
    // Generar automáticamente el código de barras basado en el código del producto
    document.getElementById('codigo_producto').addEventListener('input', function() {
        const codigoProducto = this.value;
        document.getElementById('codigo_barras').value = `BAR-${codigoProducto}`;
    });
</script>

<?php include('../layout/parte2.php'); ?>
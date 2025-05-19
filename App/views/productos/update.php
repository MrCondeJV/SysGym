<?php

include('../../config.php');
include('../layout/parte1.php');
include('../../controllers/categorias_productos/list_categorias.php');
include('../../controllers/proveedores/list_proveedores.php');
include('../../controllers/productos/show_producto.php');
include('../layout/sesion.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-sm-12 text-center">
                    <div class="p-4 rounded shadow-lg" style="background: linear-gradient(90deg,rgb(5, 99, 32),rgb(51, 240, 114)); color: #fff; font-family: 'Arial', sans-serif;">
                        <h1 class="m-0 text-uppercase font-weight-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
                            <i class="fas fa-box fa-lg"></i> Actualizar Producto
                        </h1>
                        <p class="mt-3 font-italic" style="font-size: 1.3rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-info-circle"></i> Actualizar Información del Producto
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
                            <h3 class="card-title">Datos del Producto</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../../controllers/productos/update_producto.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_producto" value="<?= $id_producto; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre del Producto</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Escriba el nombre del producto..." value="<?= htmlspecialchars($nombre ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="codigo_producto">Código del Producto</label>
                                        <input type="text" name="codigo_producto" class="form-control" placeholder="Escriba el código del producto..." value="<?= htmlspecialchars($codigo_producto ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="descripcion">Descripción</label>
                                        <input type="text" name="descripcion" class="form-control" placeholder="Escriba la descripción..." value="<?= htmlspecialchars($descripcion ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="precio">Precio</label>
                                        <input type="number" name="precio" class="form-control" placeholder="Escriba el precio..." step="0.01" value="<?= htmlspecialchars($precio ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="precio_compra">Precio de Compra</label>
                                        <input type="number" name="precio_compra" class="form-control" placeholder="Escriba el precio de compra..." step="0.01" value="<?= htmlspecialchars($precio_compra ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cantidad_stock">Cantidad en Stock</label>
                                        <input type="number" name="cantidad_stock" class="form-control" placeholder="Escriba la cantidad en stock..." value="<?= htmlspecialchars($cantidad_stock ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="id_categoria">Categoría</label>
                                        <select name="id_categoria" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($categorias_datos as $categoria): ?>
                                                <option value="<?= $categoria['id_categoria']; ?>" <?= ($categoria['id_categoria'] == ($id_categoria ?? '')) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($categoria['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="id_proveedor">Proveedor</label>
                                        <select name="id_proveedor" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($proveedores_datos as $proveedor): ?>
                                                <option value="<?= $proveedor['id_proveedor']; ?>" <?= ($proveedor['id_proveedor'] == ($id_proveedor ?? '')) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($proveedor['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="codigo_barras">Código de Barras</label>
                                        <input type="text" name="codigo_barras" class="form-control" placeholder="Escriba el código de barras..." value="<?= htmlspecialchars($codigo_barras ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="stock_minimo">Stock Mínimo</label>
                                        <input type="number" name="stock_minimo" class="form-control" placeholder="Escriba el stock mínimo..." value="<?= htmlspecialchars($stock_minimo ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="url_imagen">Imagen del Producto</label>
                                        <input type="file" name="url_imagen" class="form-control" accept="image/*">
                                        <?php if (!empty($url_imagen)): ?>
                                            <small class="form-text text-muted">Imagen actual:</small>
                                            <img src="<?= $url_imagen; ?>" alt="Imagen del Producto" class="img-thumbnail" style="max-width: 150px;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="activo">Estado</label>
                                        <select name="activo" class="form-control" required>
                                            <option value="1" <?= ($activo ?? '') == '1' ? 'selected' : ''; ?>>Activo</option>
                                            <option value="0" <?= ($activo ?? '') == '0' ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
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
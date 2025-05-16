<?php

include('../../config.php');
include('../layout/parte1.php');
include('../layout/sesion.php');

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center text-center">
                <div class="col-sm-12">
                    <h1 class="m-0 text-primary">
                        <i class="fas fa-cash-register"></i> Nueva Venta
                    </h1>
                    <p class="text-muted mt-2">Busca productos y agrégalos a la venta.</p>
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
                            <h3 class="card-title">Venta de Productos</h3>
                        </div>
                        <div class="card-body">
                            <!-- Buscador de productos -->
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-8">
                                    <label for="buscar_producto">Buscar producto</label>
                                    <input type="text" id="buscar_producto" class="form-control" placeholder="Nombre o código de producto">
                                    <div id="sugerencias_productos" class="list-group"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cantidad_producto">Cantidad</label>
                                    <input type="number" id="cantidad_producto" class="form-control" min="1" value="1">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mb-3" id="agregar_producto">Agregar a la venta</button>

                            <!-- Tabla de productos agregados -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center" id="tabla_venta">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Productos agregados aparecerán aquí -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total:</th>
                                            <th id="total_venta">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <form action="../../controllers/ventas/registrar_venta.php" method="post" id="form_venta">
                                <input type="hidden" name="productos" id="productos_json">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-success">Registrar Venta</button>
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
// Variables para productos en la venta
let productosVenta = [];

// Buscar productos con AJAX (simulado aquí, deberías hacer una petición real)
document.getElementById('buscar_producto').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 2) {
        document.getElementById('sugerencias_productos').innerHTML = '';
        return;
    }
    // Simulación: deberías hacer un fetch a tu backend para buscar productos
    fetch('../../controllers/productos/buscar_producto.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(prod => {
                html += `<a href="#" class="list-group-item list-group-item-action" data-id="${prod.id_producto}" data-nombre="${prod.nombre}" data-precio="${prod.precio}">${prod.nombre} - $${prod.precio}</a>`;
            });
            document.getElementById('sugerencias_productos').innerHTML = html;
        });
});

// Seleccionar producto de sugerencias
document.getElementById('sugerencias_productos').addEventListener('click', function(e) {
    if (e.target.matches('.list-group-item')) {
        document.getElementById('buscar_producto').value = e.target.dataset.nombre;
        document.getElementById('buscar_producto').dataset.id = e.target.dataset.id;
        document.getElementById('buscar_producto').dataset.precio = e.target.dataset.precio;
        this.innerHTML = '';
        e.preventDefault();
    }
});

// Agregar producto a la tabla de venta
document.getElementById('agregar_producto').addEventListener('click', function() {
    const id = document.getElementById('buscar_producto').dataset.id;
    const nombre = document.getElementById('buscar_producto').value;
    const precio = parseFloat(document.getElementById('buscar_producto').dataset.precio || 0);
    const cantidad = parseInt(document.getElementById('cantidad_producto').value);

    if (!id || !nombre || precio <= 0 || cantidad <= 0) {
        alert('Selecciona un producto válido y cantidad.');
        return;
    }

    // Verificar si ya está en la lista
    const existente = productosVenta.find(p => p.id == id);
    if (existente) {
        existente.cantidad += cantidad;
    } else {
        productosVenta.push({id, nombre, precio, cantidad});
    }
    renderTablaVenta();
    // Limpiar campos
    document.getElementById('buscar_producto').value = '';
    document.getElementById('buscar_producto').dataset.id = '';
    document.getElementById('buscar_producto').dataset.precio = '';
    document.getElementById('cantidad_producto').value = 1;
});

// Renderizar tabla de productos en la venta
function renderTablaVenta() {
    let tbody = '';
    let total = 0;
    productosVenta.forEach((prod, idx) => {
        const subtotal = prod.precio * prod.cantidad;
        total += subtotal;
        tbody += `<tr>
            <td>${prod.nombre}</td>
            <td>$${prod.precio.toFixed(2)}</td>
            <td>${prod.cantidad}</td>
            <td>$${subtotal.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(${idx})"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });
    document.querySelector('#tabla_venta tbody').innerHTML = tbody;
    document.getElementById('total_venta').textContent = '$' + total.toFixed(2);
    document.getElementById('productos_json').value = JSON.stringify(productosVenta);
}

// Eliminar producto de la venta
function eliminarProducto(idx) {
    productosVenta.splice(idx, 1);
    renderTablaVenta();
}

// Validar antes de enviar
document.getElementById('form_venta').addEventListener('submit', function(e) {
    if (productosVenta.length === 0) {
        e.preventDefault();
        alert('Agrega al menos un producto a la venta.');
    }
});
</script>

<?php include('../layout/parte2.php'); ?>
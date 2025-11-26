<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas</title>
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Facturas</h2>
        <button class="btn btn-primary">Agregar Factura</button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID de Factura</th>
                    <th>Customer ID</th>
                    <th>Tour</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila de ejemplo 1 -->
                <tr>
                    <td>1001</td>
                    <td>1</td>
                    <td>Tour Aventura</td>
                    <td>
                        <button class="btn btn-sm btn-info">Ver</button>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta factura?')">Eliminar</button>
                    </td>
                </tr>
                <!-- Fila de ejemplo 2 -->
                <tr>
                    <td>1002</td>
                    <td>2</td>
                    <td>Tour Cultural</td>
                    <td>
                        <button class="btn btn-sm btn-info">Ver</button>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta factura?')">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

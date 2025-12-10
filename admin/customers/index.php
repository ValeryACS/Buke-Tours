<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clientes</title>
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Clientes</h2>
        <a href="create.php" class="btn btn-primary">Agregar Cliente</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Tour</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila de ejemplo 1 -->
                <tr>
                    <td>1</td>
                    <td>Ejemplo</td>
                    <td>Uno</td>
                    <td>8888-0001</td>
                    <td>uno@mail.com</td>
                    <td>Tour Aventura</td>
                    <td>
                        <a href="edit.php?id=1" class="btn btn-sm btn-warning">Editar</a>
                        <a href="delete.php?id=1" class="btn btn-sm btn-danger" onclick="return confirm('�Est� seguro de eliminar este cliente?')">Eliminar</a>
                    </td>
                </tr>
                <!-- Fila de ejemplo 2 -->
                <tr>
                    <td>2</td>
                    <td>Ejemplo</td>
                    <td>Dos</td>
                    <td>8888-0002</td>
                    <td>dos@mail.com</td>
                    <td>Tour Cultural</td>
                    <td>
                        <a href="edit.php?id=2" class="btn btn-sm btn-warning">Editar</a>
                        <a href="delete.php?id=2" class="btn btn-sm btn-danger" onclick="return confirm('Está seguro de eliminar este cliente?')">Eliminar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

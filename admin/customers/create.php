<?php
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agregar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Agregar Cliente</h1>
  <form action="store.php" method="POST">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" name="nombre" id="nombre" class="form-control" required />
    </div>

    <div class="mb-3">
      <label for="apellido" class="form-label">Apellido</label>
      <input type="text" name="apellido" id="apellido" class="form-control" required />
    </div>

    <div class="mb-3">
      <label for="telefono" class="form-label">Teléfono</label>
      <input type="tel" name="telefono" id="telefono" class="form-control" required />
    </div>

    <div class="mb-3">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" name="correo" id="correo" class="form-control" required />
    </div>

    <div class="mb-3">
      <label for="tour" class="form-label">Tour</label>
      <input type="text" name="tour" id="tour" class="form-control" required />
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

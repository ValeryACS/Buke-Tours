<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET['id'])) {
    die("Error: No se proporcion� el ID del cliente.");
}

$id = intval($_GET['id']);

// Aqu� deber�as cargar los datos reales del cliente desde base de datos seg�n $id.
// Por ahora, vamos a simular datos:

$cliente = [
    "nombre" => "Ejemplo",
    "apellido" => "Uno",
    "telefono" => "8888-0001",
    "correo" => "uno@mail.com",
    "tour" => "Tour Aventura"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Editar Administrador #<?= $id ?></h1>
  <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>" />

    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required />
    </div>

    <div class="mb-3">
      <label for="apellido" class="form-label">Apellido</label>
      <input type="text" name="apellido" id="apellido" class="form-control" value="<?= htmlspecialchars($cliente['apellido']) ?>" required />
    </div>

    <div class="mb-3">
      <label for="telefono" class="form-label">Teléfono</label>
      <input type="tel" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono']) ?>" required />
    </div>

    <div class="mb-3">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($cliente['correo']) ?>" required />
    </div>

    <div class="mb-3">
      <label for="tour" class="form-label">Tour</label>
      <input type="text" name="tour" id="tour" class="form-control" value="<?= htmlspecialchars($cliente['tour']) ?>" required />
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

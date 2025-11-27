<?php
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">
  <h1 class="mb-4">Crear Tour</h1>

  <form action="process.php" method="POST">

    <div class="mb-3">
      <label class="form-label">Nombre del Tour</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control" rows="4" required></textarea>
    </div>



    <div class="mb-3">
      <label class="form-label">Ubicación</label>
      <input type="text" name="ubicacion" class="form-control" placeholder="Costa Rica">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="/admin/tours/" class="btn btn-secondary">Cancelar</a>

  </form>
</div>

</body>
</html>

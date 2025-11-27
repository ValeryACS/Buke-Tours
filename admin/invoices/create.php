<?php
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agregar Factura</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Agregar Factura</h1>
  <form action="store.php" method="POST">
    <div class="mb-3">
      <label for="customer_id" class="form-label">Customer ID</label>
      <input type="number" name="customer_id" id="customer_id" class="form-control" required />
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

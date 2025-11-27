<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET['id'])) {
    die("Error: No se proporcionó el ID de la factura.");
}

$id = intval($_GET['id']);

// Cargar datos reales aquí (simulado para ejemplo)
$factura = [
    "customer_id" => 1,
    "tour" => "Tour Aventura"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Factura #<?= $id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Editar Factura #<?= $id ?></h1>
  <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>" />

    <div class="mb-3">
      <label for="customer_id" class="form-label">Customer ID</label>
      <input type="number" name="customer_id" id="customer_id" class="form-control" value="<?= htmlspecialchars($factura['customer_id']) ?>" required />
    </div>

    <div class="mb-3">
      <label for="tour" class="form-label">Tour</label>
      <input type="text" name="tour" id="tour" class="form-control" value="<?= htmlspecialchars($factura['tour']) ?>" required />
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

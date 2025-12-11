<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET['id'])) {
    die("Error: No se proporcion� el ID de la factura.");
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Eliminar Factura #<?= $id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Eliminar Factura #<?= $id ?></h1>
  <p>�Est� seguro que desea eliminar esta factura?</p>

  <form method="POST">
    <button type="submit" class="btn btn-danger">S�, eliminar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

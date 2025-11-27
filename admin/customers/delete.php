<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET['id'])) {
    die("Error: No se proporcionó el ID del cliente.");
}

$id = intval($_GET['id']);

// Aquí deberías eliminar el cliente de la base de datos según $id.
// Por ahora mostramos solo mensaje y redirección.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí va la lógica para eliminar el cliente en base de datos

    // Después redirige a lista de clientes
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Eliminar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Eliminar Cliente #<?= $id ?></h1>
  <p>¿Está seguro que desea eliminar este cliente?</p>

  <form method="POST">
    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

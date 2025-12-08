<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET['id'])) {
    die("Error: No se proporcion� el ID del cliente.");
}

$id = intval($_GET['id']);

// Aqu� deber�as eliminar el cliente de la base de datos seg�n $id.
// Por ahora mostramos solo mensaje y redirecci�n.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aqu� va la l�gica para eliminar el cliente en base de datos

    // Despu�s redirige a lista de clientes
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Eliminar Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <h1>Eliminar Administrador #<?= $id ?></h1>
  <p>Esta seguro que desea eliminar este administrador?</p>

  <form method="POST">
    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>

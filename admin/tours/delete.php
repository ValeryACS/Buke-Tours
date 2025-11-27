<?php
header("Content-Type: text/html; charset=UTF-8");

if (!isset($_GET["id"])) {
  die("Error: No se proporcionó el ID del tour.");
}

$id = intval($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eliminar Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

  <div class="alert alert-danger">
    <h4 class="alert-heading">¿Eliminar Tour #<?= $id ?>?</h4>
    <p>Esta acción no se puede deshacer. ¿Estás seguro?</p>
    <hr>

    <form action="remove.php" method="POST">
      <input type="hidden" name="id" value="<?= $id ?>">

      <button type="submit" class="btn btn-danger">Sí, eliminar</button>
      <a href="/admin/tours/" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>

</div>

</body>
</html>

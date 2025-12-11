<?php
header("Content-Type: text/html; charset=UTF-8");

// Obtener ID
if (!isset($_GET["id"])) {
  die("Error: No se proporcion� el ID del tour.");
}

$id = intval($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">
  <h1 class="mb-4">Editar Tour #<?= $id ?></h1>


  <form action="update.php" method="POST">

    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="mb-3">
      <label class="form-label">Nombre del Tour</label>
      <input type="text" name="nombre" class="form-control" value="Tour <?= $id ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Descripci�n</label>
      <textarea name="descripcion" class="form-control" rows="4">Descripci�n del tour <?= $id ?></textarea>
    </div>



    <div class="mb-3">
      <label class="form-label">Ubicaci�n</label>
      <input type="text" name="ubicacion" class="form-control" value="Costa Rica">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="/admin/tours/" class="btn btn-secondary">Cancelar</a>

  </form>
</div>

</body>
</html>

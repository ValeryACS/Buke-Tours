<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tours</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>
<div class="container py-4">
  <div class="row g-3 align-items-end mb-3">
    <div class="col-12 col-md-6">
      <label for="tours-search" class="form-label">Buscar Tours</label>
      <input id="tours-search" type="search" class="form-control" placeholder="Playa, Río, Lugar…" autocomplete="off">
    </div>

    <div class="col-12 col-md-3">
      <button id="btn-tours-search" class="btn btn-primary w-100">
        <i class="bi bi-search"></i> Buscar
      </button>
    </div>

    <div class="col-12 col-md-3 text-md-end">
      <a href="create.php" class="btn btn-success mb-2">Agregar Tour</a>
      <small class="text-muted d-block">Escribe para ver resultados en vivo.</small>
    </div>
  </div>

  <hr class="my-4" />

  <div id="tours-resultado" class="row g-3">
    <?php for ($i = 1; $i <= 9; $i++): ?>
      <div class="col-12 col-sm-6 col-md-4">
        <article class="card h-100 shadow-sm">
          <img src="https://via.placeholder.com/600x350?text=Tour+<?= $i ?>" class="card-img-top" alt="Imagen del Tour <?= $i ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Nombre del Tour <?= $i ?></h5>
            <p class="card-text text-muted mb-3">
              Descripción breve del tour. Ideal para lugares como Playa, Montaña, Río, etc.
            </p>
            <div class="mt-auto">
              <div class="d-flex justify-content-between align-items-center">
               
                <div class="btn-group">
                  <!-- Botón Editar -->
                  <a href="edit.php?id=<?= $i ?>" class="btn btn-sm btn-primary">Editar</a>

                  <!-- Botón Eliminar -->
<a href="delete.php?id=<?= $i ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar el Tour <?= $i ?>?');">Eliminar</a>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div>
    <?php endfor; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

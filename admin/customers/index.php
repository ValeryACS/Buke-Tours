<?php

/**
 * Usado para listar los Clientes
 */
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>Clientes</title>
    <?php 
      include '../../php/components/admin/styles/admin-common-styles.php';
    ?>
    </head>
  <body>
    <?php 
      include '../../php/components/admin/nav-bar-admin.php';
    ?>

    <main class="container py-4">
      <div class="row g-3 align-items-end">
        <div class="col-12 col-md-6">
          <label for="clientes-search" class="form-label">Buscar clientes</label>
          <input
            id="clientes-search"
            type="search"
            class="form-control"
            placeholder="Nombre, correo, teléfono o país"
            autocomplete="off"
          />
        </div>
        <div class="col-12 col-md-3">
          <button id="btn-clientes-search" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Buscar
          </button>
        </div>
        <div class="col-12 col-md-3 text-md-end">
          <small class="text-muted d-block">Escribe para ver resultados en vivo.</small>
        </div>
      </div>

      <hr class="my-4" />

      <div id="clientes-resultado" class="row g-3"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>

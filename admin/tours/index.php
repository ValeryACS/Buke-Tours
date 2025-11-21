<?php
/**
 * Usada para administrar los Tours
 */
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>Tours</title>
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
          <label for="tours-search" class="form-label">Buscar Tours</label>
          <input
            id="tours-tourssearch"
            type="search"
            class="form-control"
            placeholder="Playa, R&iacute;o, Lugar .."
            autocomplete="off"
          />
        </div>
        <div class="col-12 col-md-3">
          <button id="btn-tours-search" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Buscar
          </button>
        </div>
        <div class="col-12 col-md-3 text-md-end">
          <small class="text-muted d-block">Escribe para ver resultados en vivo.</small>
        </div>
      </div>

      <hr class="my-4" />

      <div id="tours-resultado" class="row g-3"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
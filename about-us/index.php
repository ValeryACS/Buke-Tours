<?php
// Fuerza UTF-8 sin importar configuración del servidor
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Tours</title>

    <?php 
      // ⚠ IMPORTANTE: estos archivos deben estar guardados en UTF-8 sin BOM
      include './../php/components/admin/styles/admin-common-styles.php';
    ?>
  </head>

  <body>
    <?php 
      // ⚠ IMPORTANTE: también debe estar guardado en UTF-8 sin BOM
      include './../php/components/admin/nav-bar-admin.php';
    ?>

    <main class="container py-4">

      <div class="row g-3 align-items-end">
        <div class="col-12 col-md-6">
          <label for="tours-search" class="form-label">Buscar Tours</label>
          <input
            id="tours-search"
            type="search"
            class="form-control"
            placeholder="Playa, Río, Lugar…"
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

      <!-- CUADRÍCULA 3x3 DE TOURS -->
      <div id="tours-resultado" class="row g-3">
        <?php for ($i = 1; $i <= 9; $i++): ?>
          <div class="col-12 col-sm-6 col-md-4">
            <article class="card h-100 shadow-sm">

              <!-- Imagen -->
              <img 
                src="https://via.placeholder.com/600x350?text=Tour+<?= $i ?>" 
                class="card-img-top" 
                alt="Imagen del Tour <?= $i ?>"
              >

              <div class="card-body d-flex flex-column">
                
                <h5 class="card-title">Nombre del Tour <?= $i ?></h5>

                <p class="card-text text-muted mb-3">
                  Descripción breve del tour. Ideal para lugares como Playa, Montaña, Río, etc.
                </p>

                <div class="mt-auto">
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Costa Rica · 3h</small>

                    <div class="btn-group">
                      <a href="#" class="btn btn-sm btn-outline-primary">Ver</a>
                      <a href="#" class="btn btn-sm btn-primary">Editar</a>

                      <!-- Botón de eliminar -->
                      <button 
                        class="btn btn-sm btn-danger"
                        onclick="alert('¿Seguro que deseas eliminar el Tour <?= $i ?>?');"
                      >
                        Eliminar
                      </button>
                    </div>

                  </div>
                </div>

              </div>
            </article>
          </div>
        <?php endfor; ?>
      </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>

<?php
/**
 * Usado para renderizar los Tours disponibles
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <link rel="stylesheet" href="/Buke-Tours/assets/css/tours.css" type="text/css" />
    <?php 
      include '../php/styles/common-styles.php';
    ?>
    <title>Tours Disponibles</title>
  </head>
  <body>
    <?php 
    include '../php/components/navbar.php';
    ?>
    <main class="container py-4 cards-wrapper">
      <div class="form-group mt-5 mb-5 container-md">
        <form id="search-form-tours" class="d-flex justify-content-center flex-row">
          <input
            class="barra-busqueda m-auto form-control"
            placeholder="Buscar tours..."
            type="text"
            id="search-input-tour"
          />
          <button type="button" id="btn-search-tours" class="btn btn-success">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
        id="search-tours-results"
      ></div>
      <!-- Grid responsive: 1 col en xs, 2 en sm, 3 en lg -->
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
        id="tours"
      ></div>
    </main>
    <main class="container py-4 cards-wrapper" id="skeleton-tours">
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
      >
        <!-- SKELETON CARD (reutiliza 9 veces) -->
        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <!-- Imagen (usa ratio para reservar alto) -->
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <!-- tag -->
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <!-- title -->
              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <!-- párrafos -->
              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <!-- botón -->
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>
      </div>
    </main>
    <?php 
    include '../php/components/cart-modal.php';
    include '../php/components/footer.php';
    include '../php/scripts/common-scripts.php';
    ?>
    <script type="module" src="/Buke-Tours/assets/js/tours.js" defer></script>
  </body>
</html>

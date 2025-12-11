<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];
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
    <title><?php echo $lang['tours_page_title']; ?></title>
  </head>
  <body>
    <?php 
    require_once '../config.php';
    ?>
    <main class="container py-4 cards-wrapper">
      <div class="form-group mt-5 mb-5 container-md">
        <form
          id="search-form-tours"
          class="row g-3 align-items-center justify-content-center"
        >
          <div class="col-12 col-md-4">
            <input
              class="barra-busqueda form-control"
              placeholder="<?php echo $lang['Buscar_Tour'];?>"
              type="text"
              id="search-input-tour"
            />
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <input
              type="date"
              class="form-control"
              id="check-in-date"
              name="check_in_date"
              min="<?php echo date('Y-m-d'); ?>"
              placeholder="<?php echo $lang['check_in_label']; ?>"
            />
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <input
              type="date"
              class="form-control"
              id="check-out-date"
              name="check_out_date"
              min="<?php echo date('Y-m-d'); ?>"
              placeholder="<?php echo $lang['check_out_label']; ?>"
            />
          </div>
          <div class="col-12 col-md-2 d-grid">
            <button type="button" id="btn-search-tours" class="btn btn-success">
              <i class="bi bi-search"></i>
            </button>
          </div>
          <div class="col-12 col-md-2 d-grid">
            <button
              type="button"
              id="btn-clear-tours-search"
              class="btn btn-danger"
            >
              <i class="bi bi-arrow-counterclockwise me-1"></i>
              <?php echo $lang['btn_clear']; ?>
            </button>
          </div>
        </form>
      </div>
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
        id="search-tours-results"
      ></div>
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
        id="tours"
      ></div>
    </main>
    <main class="container py-4 cards-wrapper" id="skeleton-tours">
      <div
        class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 justify-content-center"
      >
        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>
            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

            <a
              class="btn btn-primary disabled placeholder"
              aria-disabled="true"
            ></a>
          </div>
        </div>

        <div class="col d-flex">
          <div class="card w-100 h-100 d-flex flex-column">
            <div class="ratio ratio-16x9 bg-body-secondary placeholder">
              <i class="bi bi-card-image image-skeleton"></i>
            </div>

            <div class="card-body d-flex flex-column placeholder-glow">
              <div class="d-flex justify-content-center mb-2">
                <span
                  class="placeholder col-4 rounded-1"
                  style="height: 20px"
                ></span>
              </div>

              <span class="placeholder col-6 mb-2" style="height: 24px"></span>

              <span class="placeholder col-12 mb-1"></span>
              <span class="placeholder col-10 mb-1"></span>
              <span class="placeholder col-8"></span>
            </div>

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

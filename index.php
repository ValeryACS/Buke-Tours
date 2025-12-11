<?php

/**
 * Usado para renderizar el Home Page
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include 'language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];

include 'php/config/db.php';

$mysqli = openConnection();

$sqlTours = 'SELECT * FROM tour';

$toursDisponibles= $mysqli->prepare($sqlTours);
$toursDisponibles->execute();
$toursResult = $toursDisponibles->get_result();

closeConnection($mysqli);
?>
<!DOCTYPE html>
<html lang="<?php echo $html_lang; ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/Buke-Tours/assets/css/slider.css" type="text/css" />
    <link rel="stylesheet" href="/Buke-Tours/assets/css/profile.css" />
    <?php 
      include './php/styles/common-styles.php';
    ?>
  </head>
  <body>
   <?php 
   require_once 'config.php';
   ?>
    <main class="w-100">
      <div class="form-group mb-5 bg-yellow-light pb-3">
          <form
            id="search-form"
            class="row g-3 align-items-center justify-content-center"
          >
            <div class="col-12 col-md-4">
              <input
                class="barra-busqueda form-control"
                placeholder="<?php echo $lang['Buscar_Tour'];?>"
                type="text"
                id="search-input"
              />
            </div>
            <div class="col-12 col-sm-6 col-md-2">
              <input
                type="date"
                class="form-control"
                id="check-in-date"
                name="check_in_date"
                min="<?php echo date('Y-m-d'); ?>"
                placeholder="Check in"
              />
            </div>
            <div class="col-12 col-sm-6 col-md-2">
              <input
                type="date"
                class="form-control"
                id="check-out-date"
                name="check_out_date"
                min="<?php echo date('Y-m-d'); ?>"
                placeholder="Check out"
              />
            </div>
            <div class="col-12 col-md-2 d-grid">
              <button type="submit" id="btn-search" class="btn btn-success">
                <i class="bi bi-search"></i>
              </button>
            </div>
            <div class="col-12 col-md-2 d-grid">
              <button
                type="button"
                id="btn-clear-home-search"
                class="btn btn-danger"
              >
                <i class="bi bi-arrow-counterclockwise me-1"></i>
              </button>
            </div>
          </form>
        </div>
      <section>
        
        <div id="search-result" class="w-100 bg-white"></div>
        <div
          style="
            --swiper-navigation-color: #fff;
            --swiper-pagination-color: #fff;
          "
          id="swiper-slider-tours"
          class="swiper slider-tours"
        >
          <article class="swiper-wrapper">
            <?php
                    if ($toursResult) {
                        while ($fila = $toursResult->fetch_assoc()):
                        ?>
                        <div class="swiper-slide">
                            <img
                              src="<?php echo $fila['img']; ?>"
                              loading="lazy"
                              class="tour-imagen"
                            />
                            <div class="overlay-effect position-absolute">
                            <h1 style="color: #ffd89c;background: #2a2238;">
                                <?php echo strlen($fila['title']) <= 23 ? $fila['title'] : substr($fila['title'], 0, 23) . "..."; ?>
                            </h1>  
                            <h3>
                                <?php echo strlen($fila['location']) <= 23 ? $fila['location'] : substr($fila['location'], 0, 23) . "..."; ?>
                            </h3>
                            <i
                              class="bi bi-cart-plus-fill display-4 add-to-cart"
                              data-bs-toggle="modal"
                              data-bs-target="#cartModal"
                              data-tour-id="<?php echo $fila['sku']; ?>"
                            ></i>
                            <i
                              class="bi bi-cursor-fill view-tour-page display-4 "
                              data-tour-id="<?php echo $fila['id']; ?>"
                            ></i>
                            </div>
                            <div
                              class="swiper-lazy-preloader swiper-lazy-preloader-white"
                            ></div>
                          </div>
                        <?php 
                        endwhile;
                      }
                        ?>
            
           
          </article>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-pagination"></div>
        </div>
      </section>
      <section class="content bg-light-subtle bg-opacity-75 mt-5">
        <article class="hero">
          <h1 class="titulo">Bukë Tours</h1>
          <p class="m-4">
           <?php echo $lang['informacion'] ?? "At Bukë Tours, we specialize in offering unique and 
                    authentic experiences in Costa Rica. Our goal is to provide you with the
                    best tours to discover the nature, culture, and adventure in the country's 
                    most impressive locations."; ?>
          </p>
        </article>

        <article class="rectangle-bbf6cb004687">
          <h2 class="titulo"><?php echo $lang['mision_titulo'] ?? 'Nuestra misión'; ?></h2>
          <p class="m-4">
            <?php echo $lang['nuestra_mision'] ?? 'Queremos que cada viaje sea una experiencia inolvidable, llena de
                        emoción y conexión con la naturaleza.'; ?>
          </p>

          <h2 class="titulo"><?php echo $lang['equipo'] ?? 'Nuestro equipo'; ?></h2>
          <p class="m-4">
            <?php echo $lang['nuestro_equipo'] ?? 'Contamos con un equipo de guías profesionales y apasionados por 
                        compartir la belleza de Costa Rica contigo.'; ?>
          </p>
        </article>
      </section>

      <section
        class="main-content bg-buke-tours mx-auto my-5 profile-form-section"
        style="max-width: 768px"
      >
        <h1 class="titulo"><?php echo $lang['resenas'] ?? 'Reseñas'; ?></h1>
        <h2 class="subtitulo  w-100"><?php echo $lang['sobre_tours'] ?? 'Sobre los tours'; ?></h2>
        
          <?php 
            if (isset($_SESSION['id'])) {
              include './php/components/reviews-form.php';
            }
          ?>
        <?php 
         include './php/components/reviews-list.php';
        ?>
        
      </section>
    </main>
    <script src="js/script.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <?php 
    include './php/components/cart-modal.php';
    include './php/components/footer.php';
    include './php/scripts/home-page.php';
    ?>
  </body>
</html>

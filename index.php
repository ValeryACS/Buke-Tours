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
    <?php 
      include './php/styles/common-styles.php';
    ?>
  </head>
  <body>
   <?php 
   require_once 'config.php';
   ?>
    <main>
      <section>
        <div class="form-group mt-5 mb-5 container-md">
          <form id="search-form" class="d-flex justify-content-center flex-row">
            <input
              class="barra-busqueda m-auto form-control"
              placeholder="<?php echo $lang['Buscar_Tour'];?>"
              type="text"
              id="search-input"
            />
            <button type="submit" id="btn-search" class="btn btn-success">
              <i class="bi bi-search"></i>
            </button>
          </form>
        </div>
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
                              <h1>
                                <?php echo $fila['title']; ?>
                                <i
                                  class="bi bi-cart-plus-fill display-4 add-to-cart"
                                  data-bs-toggle="modal"
                                  data-bs-target="#cartModal"
                                  data-tour-id="<?php echo $fila['sku']; ?>"
                                ></i>
                                <i
                                  class="bi bi-cursor-fill view-tour-page"
                                  data-tour-id="<?php echo $fila['sku']; ?>"
                                ></i>
                              </h1>
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
        class="main-content bg-buke-tours mx-auto my-5"
        style="max-width: 768px"
      >
        <!-- Sección principal -->
        <h1 class="subtitulo"><?php echo $lang['resenas'] ?? 'Reseñas'; ?></h1>
        <h2><?php echo $lang['sobre_tours'] ?? 'Sobre los tours'; ?></h2>

        <div class="formulario-resena">
          <h3>⭐<?php echo $lang['titulo_resena']; ?></h3>
          <form action="#" method="post">
            <label for="nombre"><?php echo $lang['label_nombre'];?></label>
            <input type="text" id="nombre" name="nombre" required />

            <label for="calificacion"><?php echo $lang['label_calificacion'];?></label>
            <select id="calificacion" name="calificacion" required>
              <option value="5">⭐⭐⭐⭐⭐</option>
              <option value="4">⭐⭐⭐⭐</option>
              <option value="3">⭐⭐⭐</option>
              <option value="2">⭐⭐</option>
              <option value="1">⭐</option>
            </select>

            <label for="comentario"><?php echo $lang['comentario'];?></label>
            <textarea
              id="comentario"
              name="comentario"
              rows="4"
              required
            ></textarea>

            <button type="submit"><?php echo $lang['boton_enviar'];?></button>
          </form>
        </div>

        <div class="resenas-container">
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="text-start">
              <div class="fw-bold">
                Valery Campos <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>Excelente experiencia en el tour de Manglar.</p>
            </div>
          </div>
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="text-start">
              <div class="fw-bold">
                Maria Villanueva <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>Muy bueno, lo recomiendo.</p>
            </div>
          </div>
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="text-start">
              <div class="fw-bold">
                Jose Vargas <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>La experiencia fue increíble, logramos ver muchas especies.</p>
            </div>
          </div>
        </div>
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

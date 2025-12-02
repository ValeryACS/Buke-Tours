<?php

/**
 * Usado para renderizar el Home Page
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include 'language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];
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
    include './php/components/navbar.php';
   ?>
    <main>
      <section>
        <div class="form-group mt-5 mb-5 container-md">
          <form id="search-form" class="d-flex justify-content-center flex-row">
            <input
              class="barra-busqueda m-auto form-control"
              placeholder="Buscar tours..."
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
            <div class="swiper-slide">
              <img
                src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/06/93/b4/50/monteverde-cloud-forest.jpg?w=600&h=-1&s=1"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  Monteverde
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-monteverde-hanging-bridges"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-monteverde-hanging-bridges"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
            <div class="swiper-slide">
              <img
                src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/5a/2f/50/photo0jpg.jpg?w=1000&h=-1&s=1"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  Manuel Antonio
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-manuel-antonio-park"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-manuel-antonio-park"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
            <div class="swiper-slide">
              <img
                src="https://cdn.pixabay.com/photo/2017/06/13/19/22/tyrolean-2399759_1280.jpg"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  La Fortuna
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-arenal-zipline"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-arenal-zipline"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
            <div class="swiper-slide">
              <img
                src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/02/67/45/0f/waterfall.jpg?w=900&h=-1&s=1"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  R&iacute;o Celeste
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-rio-celeste"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-rio-celeste"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
            <div class="swiper-slide">
              <img
                src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/15/d7/9d/img-20180217-133510-largejpg.jpg?w=1000&h=-1&s=1"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  Nauyaca
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-nauyaca-falls"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-nauyaca-falls"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
            <div class="swiper-slide">
              <img
                src="https://mytanfeet.com/wp-content/uploads/2018/01/Things-to-do-in-Puerto-Viejo-beach-hop.jpg"
                loading="lazy"
                class="tour-imagen"
              />
              <div class="overlay-effect position-absolute">
                <h1>
                  Puerto Viejo
                  <i
                    class="bi bi-cart-plus-fill display-4 add-to-cart"
                    data-bs-toggle="modal"
                    data-bs-target="#cartModal"
                    data-tour-id="cr-puerto-viejo"
                  ></i>
                  <i
                    class="bi bi-cursor-fill view-tour-page"
                    data-tour-id="cr-puerto-viejo"
                  ></i>
                </h1>
              </div>
              <div
                class="swiper-lazy-preloader swiper-lazy-preloader-white"
              ></div>
            </div>
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
            En Bukë Tours nos especializamos en ofrecer experiencias únicas y
            auténticas en Costa Rica. Nuestro objetivo es brindarte los mejores
            tours para descubrir la naturaleza, la cultura y la aventura en los
            lugares más impresionantes del país.
          </p>
        </article>

        <article class="rectangle-bbf6cb004687">
          <h2 class="titulo">Nuestra Misión</h2>
          <p class="m-4">
            Queremos que cada viaje sea una experiencia inolvidable, llena de
            emoción y conexión con la naturaleza.
          </p>

          <h2 class="titulo">Nuestro Equipo</h2>
          <p class="m-4">
            Contamos con un equipo de guías profesionales y apasionados por
            compartir la belleza de Costa Rica contigo.
          </p>
        </article>
      </section>

      <section
        class="main-content bg-buke-tours mx-auto my-5"
        style="max-width: 768px"
      >
        <!-- Sección principal -->
        <h1 class="subtitulo">Reseñas</h1>
        <h2>Sobre los Tours</h2>

        <div class="formulario-resena">
          <h3>⭐Agrega tu reseña</h3>
          <form action="#" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required />

            <label for="calificacion">Calificación:</label>
            <select id="calificacion" name="calificacion" required>
              <option value="5">⭐⭐⭐⭐⭐</option>
              <option value="4">⭐⭐⭐⭐</option>
              <option value="3">⭐⭐⭐</option>
              <option value="2">⭐⭐</option>
              <option value="1">⭐</option>
            </select>

            <label for="comentario">Comentario:</label>
            <textarea
              id="comentario"
              name="comentario"
              rows="4"
              required
            ></textarea>

            <button type="submit">Enviar Reseña</button>
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

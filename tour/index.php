<?php
/**
 * Usado para renderizar un Tour
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
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
    <title>Tour</title>
  </head>
  <body>
    <?php 
    require_once '../config.php';
    ?>
    <main class="content bg-buke-tours">
      <section
  class="py-5">
  <div class="container">
    <div class="row g-4">

      <!-- MAP -->
      <div class="col-lg-7">
        <div class="ratio ratio-4x3 rounded shadow-sm">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d16846.755301994162!2d-84.82101270477017!3d10.30521943638019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8fa01978f5f77c49%3A0x990decc8173f24d4!2sProvincia%20de%20Puntarenas%2C%20Monteverde!5e1!3m2!1ses-419!2scr!4v1743633737842!5m2!1ses-419!2scr"
            allowfullscreen=""
            loading="lazy">
          </iframe>
        </div>
      </div>

      <!-- LIST OF TOURS -->
      <div class="col-lg-5">
        <div class="d-flex flex-column gap-3">

          <!-- CARD 1 -->
          <div class="card shadow-sm border-5">
            <div class="row g-0">
              <div class="col-4">
                <img src="../assets/img/tour1.jpg" class="img-fluid rounded-start" alt="">
              </div>
              <div class="col-8 p-2">
                <h6 class="mb-1">Monteverde</h6>
                <small class="text-muted"><?php echo $lang['label_duracion'] ?> 3 <?php echo $lang['opcion_horas'] ?></small>
              </div>
            </div>
          </div>

          <!-- CARD 2 -->
          <div class="card shadow-sm border-5">
            <div class="row g-0">
              <div class="col-4">
                <img src="../assets/img/tour2.jpg" class="img-fluid rounded-start" alt="">
              </div>
              <div class="col-8 p-2">
                <h6 class="mb-1">Manuel Antonio</h6>
                <small class="text-muted"><?php echo $lang['label_duracion'] ?> 5 <?php echo $lang['opcion_horas'] ?></small>
              </div>
            </div>
          </div>

          <!-- CARD 3 -->
          <div class="card shadow-sm border-5">
            <div class="row g-0">
              <div class="col-4">
                <img src="../assets/img/tour3.jpg" class="img-fluid rounded-start" alt="">
              </div>
              <div class="col-8 p-2">
                <h6 class="mb-1">La Fortuna</h6>
                <small class="text-muted"><?php echo $lang['label_duracion'] ?> 4 <?php echo $lang['opcion_horas'] ?></small>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- SIMILAR EXPERIENCES-->
  <h4 class="mt-5 mb-3 titulo">Similar Experiences</h4>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


<div class="swiper mySwiperSimilar">
  <div class="swiper-wrapper">

    <div class="swiper-slide">
      <div class="card shadow-sm border-0 h-100">
        <img src="../assets/img/tour1.jpg" class="card-img-top" alt="">
        <div class="card-body">
          <h6>Ninh Binh Full-Day Tour</h6>
          <div class="d-flex align-items-center">
            <span class="text-success me-2">5.0 ★</span>
            <small class="text-muted">(3,011)</small>
          </div>
          <small class="text-muted">from $33.00 per adult</small>
        </div>
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card shadow-sm border-0 h-100">
        <img src="../assets/img/tour2.jpg" class="card-img-top" alt="">
        <div class="card-body">
          <h6>Hoa Lu – Trang An</h6>
          <div class="d-flex align-items-center">
            <span class="text-success me-2">4.9 ★</span>
            <small class="text-muted">(896)</small>
          </div>
          <small class="text-muted">from $50.00 per adult</small>
        </div>
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card shadow-sm border-0 h-100">
        <img src="../assets/img/tour3.jpg" class="card-img-top" alt="">
        <div class="card-body">
          <h6>Hidden Gems Tour</h6>
          <div class="d-flex align-items-center">
            <span class="text-success me-2">5.0 ★</span>
            <small class="text-muted">(3,803)</small>
          </div>
          <small class="text-muted">from $89.00 per adult</small>
        </div>
      </div>
    </div>

    <div class="swiper-slide">
      <div class="card shadow-sm border-0 h-100">
        <img src="../assets/img/tour4.jpg" class="card-img-top" alt="">
        <div class="card-body">
          <h6>Private Full-Day Tour</h6>
          <div class="d-flex align-items-center">
            <span class="text-success me-2">5.0 ★</span>
            <small class="text-muted">(379)</small>
          </div>
          <small class="text-muted">from $115.00 per adult</small>
        </div>
      </div>
    </div>

  </div>

 <!-- Navegacion -->
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>


  <div class="swiper-pagination"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const swiperSimilar = new Swiper('.mySwiperSimilar', {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,

    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },

    breakpoints: {
      0:   { slidesPerView: 1 },
      576: { slidesPerView: 2 },
      992: { slidesPerView: 3 }
    }
  });
</script>



  </div>

  
</div>


  </div>
</section>
 

</section>

      </section>
      
    </main>
    <?php 
    include '../php/components/cart-modal.php';
    include '../php/components/footer.php';
    include '../php/scripts/common-scripts.php';
    ?>
 
  </body>
</html>

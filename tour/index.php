<?php
/**
 * Usado para renderizar un Tour
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tourId = 1;

if(isset($_GET['tourID'])){
  $tourId = (int)$_GET['tourID'];
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];

include '../php/config/db.php';

$mysqli = openConnection();

$sqlTour = 'SELECT * FROM tour WHERE id=? LIMIT 1';

$tour= $mysqli->prepare($sqlTour);
$tour->bind_param("i", $tourId);
$tour->execute();
$tourResult = $tour->get_result();

$rows = [];

if ($tourResult) {
    while ($row = $tourResult->fetch_assoc()) {
        $rows[] = $row;
    }
}

closeConnection($mysqli);
include '../php/helpers/get-iframe.php';
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
  <div class="container bg-white ">
    <h1 class="titulo">
      <?php 
      if(isset($rows[0]['title'])){
        echo $rows[0]['title'];
      }
      ?>
    </h1>
    
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="ratio ratio-4x3 rounded shadow-sm">
          <?php foreach ($rows as $filaTour): 
            $iframeSrc = getIframe($filaTour['iframe'] ?? null);
            $fallback  = 'https://www.google.com/maps?q=' . urlencode(($filaTour['location'].",". $filaTour['title'])?? '') . '&output=embed';
          ?>
            <?php if ($iframeSrc): ?>
              <iframe
                src="<?php echo htmlspecialchars($iframeSrc, ENT_QUOTES, 'UTF-8'); ?>"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            <?php else: ?>
              <iframe
                src="<?php echo htmlspecialchars($fallback, ENT_QUOTES, 'UTF-8'); ?>"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
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
      <div class="col-lg-12">
        <h1 class="titulo">
        <?php 
        if(isset($rows[0]['location'])){
          echo $rows[0]['location']. "-". $rows[0]['title'];
        }
        ?>
      </h1>
      </div>
      <div class="col-lg-7 p-4">
        
        <p>
          <?php 
          if(isset($rows[0]['description'])){
            echo $rows[0]['description'];
          }
          ?>
        </p>
      </div> 
      <div class="col-lg-5">
        <img class="float-right img-thumbnail" src="<?php echo $rows[0]['img']; ?>" alt="<?php echo $rows[0]['title']; ?>">
      </div>
    </div>
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

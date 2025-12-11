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

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

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

$similarTours = [];

if (!empty($rows)) {
    $currentCategory = $rows[0]['category'] ?? '';
    $currentLocation = $rows[0]['location'] ?? '';
    $currentTitle    = trim((string)($rows[0]['title'] ?? ''));
    $titlePattern    = '%' . $currentTitle . '%';

    $sqlSimilar = "
        SELECT id, title, location, price_usd, rating, img
        FROM tour
        WHERE id <> ?
          AND (
            category = ?
            OR title LIKE ?
            OR location LIKE ?
          )
        LIMIT 15
    ";

    if ($stmtSimilar = $mysqli->prepare($sqlSimilar)) {
        $stmtSimilar->bind_param("isss", $tourId, $currentCategory, $titlePattern, $currentLocation);
        if ($stmtSimilar->execute()) {
            $resultSimilar = $stmtSimilar->get_result();
            while ($tourRow = $resultSimilar->fetch_assoc()) {
                $similarTours[] = $tourRow;
            }
        }
        $stmtSimilar->close();
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
    <title>Tour <?php 
      if(isset($rows[0]['title'])){
        echo $rows[0]['title'];
      }
      ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  </head>
  <body>
    <?php 
    require_once '../config.php';
    ?>
    <main class="content bg-buke-tours">
      <section  class="py-5">
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
                src="<?php echo htmlspecialchars($iframeSrc); ?>"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            <?php else: ?>
              <iframe
                src="<?php echo htmlspecialchars($fallback); ?>"
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
          <?php $highlighted = !empty($similarTours) ? array_slice($similarTours, 0, 3) : []; ?>
          <?php if (!empty($highlighted)): ?>
            <?php foreach ($highlighted as $highlight): ?>
              <div class="card shadow-sm border-5">
                <div class="row g-0">
                  <div class="col-4">
                    <img
                      src="<?php echo htmlspecialchars($highlight['img']); ?>"
                      class="img-fluid rounded-start"
                      alt="<?php echo htmlspecialchars($highlight['title']); ?>"
                    >
                  </div>
                  <div class="col-8 p-2">
                    <h6 class="mb-1"><?php echo htmlspecialchars($highlight['title']); ?></h6>
                    <small class="text-muted">
                      <?php echo $lang['label_duracion']; ?>
                      <?php echo number_format((float)($highlight['duration_hours'] ?? 0), 1); ?>
                      <?php echo $lang['opcion_horas']; ?>
                    </small>
                    <div class="mt-1">
                      <span class="text-success fw-semibold">
                        <?php echo number_format((float)($highlight['rating'] ?? 0), 1); ?> ★
                      </span>
                    </div>
                    <a
                      class="stretched-link"
                      href="/Buke-Tours/tour/?tourID=<?php echo (int)$highlight['id']; ?>"
                      aria-label="View tour <?php echo htmlspecialchars($highlight['title']); ?>"
                    ></a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="card shadow-sm border-5 p-3 text-center">
              <small class="text-muted"><?php echo $lang['no_similar_tours']; ?></small>
            </div>
          <?php endif; ?>
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
    <?php if (!empty($similarTours)): ?>
    <h4 class="mt-5 mb-3 titulo"><?php echo $lang ['similar_experiences']; ?></h4>
    <div class="swiper mySwiperSimilar">
      <div class="swiper-wrapper">
        <?php foreach ($similarTours as $similar): ?>
          <div class="swiper-slide">
            <div class="card shadow-sm border-0 h-100 position-relative">
              <img
                src="<?php echo htmlspecialchars($similar['img']); ?>"
                class="card-img-top"
                alt="<?php echo htmlspecialchars($similar['title']); ?>"
              >
              <div class="card-body">
                 <a
                class=""
                href="/Buke-Tours/tour/?tourID=<?php echo (int)$similar['id']; ?>"
                aria-label="View tour <?php echo htmlspecialchars($similar['title']); ?>"
              ><h6><?php echo htmlspecialchars($similar['title']); ?></h6></a>
                
                <div class="d-flex align-items-center justify-content-between">
                  <span class="text-success me-2">
                    <?php echo number_format((float)($similar['rating'] ?? 0), 1); ?> ★
                  </span>
                  <small class="text-muted">
                    <?php echo htmlspecialchars($similar['location']); ?>
                  </small>
                </div>
                <small class="text-muted">
                  from $<?php echo number_format((float)($similar['price_usd'] ?? 0), 2); ?> per adult
                </small>
              </div>
             
            </div>
          </div>
        <?php endforeach; ?>
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
<?php endif; ?>
  </div>
</div>
  </div>
</section>
    </main>
    <?php 
    include '../php/components/cart-modal.php';
    include '../php/components/footer.php';
    include '../php/scripts/common-scripts.php';
    ?>
 
  </body>
</html>

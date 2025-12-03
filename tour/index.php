<?php
/**
 * Usado para renderizar un Tour
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

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
    include '../php/components/navbar.php';
    ?>
    <main class="content bg-buke-tours">
      <section
        id="tours"
        class="tours-section"
        style="margin: 1.5rem auto; max-width: 1100px; padding: 0 1rem"
      >
        <h2 style="margin-bottom: 1rem"><?php echo $lang['explora_tours'] ?></h2>
        <div
          id="tours-filters"
          class="tours-filters"
          style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem"
        >
          <label style="flex: 1; min-width: 240px">
            <span style="display: block; font-size: 0.9rem; opacity: 0.8"
              ><?php echo $lang['placeholder_buscar_titulo'] ?></span
            >
            <input
              id="tour-search"
              type="search"
              placeholder="<?php echo $lang['placeholder_ejemplo_ubicacion'] ?>"
              style="
                width: 100%;
                padding: 0.6rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
              "
            />
          </label>
          <label style="width: 200px">
            <span style="display: block; font-size: 0.9rem; opacity: 0.8"
              ><?php echo $lang['label_calificacion_minima'] ?></span
            >
            <select
              id="tour-min-rating"
              style="
                width: 100%;
                padding: 0.6rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
              "
            >
              <option value="0"><?php echo $lang['opcion_todas'] ?></option>
              <option value="4.5">4.5+</option>
              <option value="4.7">4.7+</option>
              <option value="4.8">4.8+</option>
            </select>
          </label>
        </div>
        <div id="tours-list" class="tours-grid"></div>
      </section>
      <section class="tour-section">
        <div class="mapa">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d16846.755301994162!2d-84.82101270477017!3d10.30521943638019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8fa01978f5f77c49%3A0x990decc8173f24d4!2sProvincia%20de%20Puntarenas%2C%20Monteverde!5e1!3m2!1ses-419!2scr!4v1743633737842!5m2!1ses-419!2scr"
            loading="lazy"
          >
          </iframe>
        </div>

        <div class="tour-list">
          <div class="tour-card">
            <img
              src="../assets/img/tour1.jpg"
              alt="Tour 1"
              class="tour-imagen"
            />
            <p>
              <strong><?php echo $lang['label_ubicacion'] ?></strong> Monteverde<br /><strong
                ><?php echo $lang['label_duracion'] ?></strong
              >
             3 <?php echo $lang['opcion_horas'] ?>
            </p>
          </div>
          <div class="tour-card">
            <img
              src="../assets/img/tour2.jpg"
              alt="Tour 2"
              class="tour-imagen"
            />
            <p>
              <strong><?php echo $lang['label_ubicacion'] ?></strong> Manuel Antonio<br /><strong
                ><?php echo $lang['label_duracion'] ?></strong
              >
              5 <?php echo $lang['opcion_horas'] ?>
            </p>
          </div>
          <div class="tour-card">
            <img
              src="../assets/img/tour3.jpg"
              alt="Tour 3"
              class="tour-imagen"
            />
            <p>
              <strong><?php echo $lang['label_ubicacion'] ?></strong> La Fortuna<br /><strong
                ><?php echo $lang['label_duracion'] ?></strong
              >
              4 <?php echo $lang['opcion_horas'] ?>
            </p>
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

<?php
/**
 * Usado para renderizar las reseñas
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

$userID = isset($_SESSION['id'])? (int)$_SESSION['id']: 0;

if($userID<= 0){
    header("Location: ../auth/login/");
    exit();
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];

include '../php/config/db.php';


$mysqli = openConnection();

$sqlTours = 'SELECT `id`, `title` FROM tour';

$toursDisponibles= $mysqli->prepare($sqlTours);
$toursDisponibles->execute();
$toursResult = $toursDisponibles->get_result();

closeConnection($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reseñas</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
    <link rel="stylesheet" href="/Buke-Tours/assets/css/profile.css" />
  </head>
  <body>
   <?php
    require_once '../config.php';
    ?>
    <main>
      <section
        class="main-content bg-buke-tours mx-auto my-5 profile-form-section"
        style="max-width: 768px"
      >
        <!-- Sección principal -->
        <h1 class="titulo"><?php echo $lang['resenas'] ?? 'Reseñas'; ?></h1>
        <h2 class="subtitulo  w-100"><?php echo $lang['sobre_tours'] ?? 'Sobre los tours'; ?></h2>

        <div class="formulario-resena">
          <h3>⭐<?php echo $lang['titulo_resena']; ?></h3>
          <form action="#" method="post">
            <label for="nombre"><?php echo $lang['label_nombre'];?></label>
            <input type="text" id="nombre" name="nombre" required />
            <label for="tour-id">Nombre del Tour</label>
            <select id="tour-id" name="tour-id" required>
              <option value="no-seleccionado" selected>Seleccione un Tour</option>
              <?php
              if ($toursResult) {
                        while ($fila = $toursResult->fetch_assoc()):
                          echo  "<option value='". $fila['id']."'>". $fila['title']."</option>";

                        endwhile;
              }
              ?>
            </select>
            <label for="calificacion"><?php echo $lang['label_calificacion'];?></label>
            <select id="calificacion" name="calificacion" required>
              <option value="5" selected>⭐⭐⭐⭐⭐</option>
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

            <button type="button" id="btn-save-review"><?php echo $lang['boton_enviar'];?></button>
          </form>
        </div>

        <div class="resenas-container">
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="contenido">
              <div class="fw-bold">
                Valery Campos <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>Excelente experiencia en el tour de Manglar.</p>
            </div>
          </div>
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="contenido">
              <div class="fw-bold">
                Maria Villanueva <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>Muy bueno, lo recomiendo.</p>
            </div>
          </div>
          <div class="reseña">
            <div class="icono">👤</div>
            <div class="contenido">
              <div class="fw-bold">
                Jose Vargas <span class="estrellas">⭐⭐⭐⭐⭐</span>
              </div>
              <p>La experiencia fue increíble, logramos ver muchas especies.</p>
            </div>
          </div>
        </div>
      </section>
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
    <script src="/Buke-Tours/assets/js/reviews.pge.js"  type="module" defer></script>
  </body>
</html>

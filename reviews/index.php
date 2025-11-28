
<?php
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
    <title>Reseñas</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
  </head>
  <body>
   <?php
    include '../php/components/navbar.php';
    ?>
    <main>
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
  </body>
</html>

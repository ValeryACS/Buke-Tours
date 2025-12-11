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
        <h1 class="titulo"><?php echo $lang['resenas'] ?? 'Reseñas'; ?></h1>
        <h2 class="subtitulo  w-100"><?php echo $lang['sobre_tours'] ?? 'Sobre los tours'; ?></h2>

        <div class="formulario-resena">
          <h3>⭐<?php echo $lang['titulo_resena']; ?></h3>
          <?php include '../php/components/reviews-form.php';?>
        </div>
        <?php include '../php/components/reviews-list.php';?>
        
      </section>
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
    
  </body>
</html>

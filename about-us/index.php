<?php
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
    <title>Sobre Nosotros</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
  </head>
  <body>
    <?php
    require_once '../config.php';
    ?>
    <main class="content bg-light-subtle bg-opacity-75 mt-5">
      <section>
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
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

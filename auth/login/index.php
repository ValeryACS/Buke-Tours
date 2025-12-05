
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include '../../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];
?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio</title>
    <?php 
      include '../../php/styles/common-styles.php';
    ?>
  </head>
  <body>
    <?php
   require_once '../../config.php';
    ?>

    <section
      class="container bg-warning-subtle mx-auto p-4 mt-5 bg-buke-tours"
      style="max-width: 500px"
    >
      <h1><?php echo $lang['iniciar_sesion'];?></h1>
      <form>
        <input
          required
          type="email"
          class="login-textinput"
          placeholder="<?php echo $lang['correo_electronico_ph'];?>"
        />
        <br />
        <input
          required
          type="password"
          class="login-textinput"
          placeholder="<?php echo $lang['contrasena_ph'];?>"
        />
        <br />
        <button class="login-boton btn"><?php echo $lang['iniciar_sesion'];?></button>
      </form>
      <br />
      <br />
      <br />
      <div class="login-no-tienes-una-cuenta">
        <h2><?php echo $lang['no_tienes_cuenta_msg'];?></h2>
        <a href="/Buke-Tours/auth/signup/"
          ><button class="login-boton btn"><?php echo $lang['registrarme'];?></button></a
        >
      </div>
    </section>
    <?php 
      include '../../php/components/footer.php';
      include '../../php/components/cart-modal.php';
      include '../../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

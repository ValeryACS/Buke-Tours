<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include '../../../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio</title>
    <?php 
include '../../../php/components/admin/styles/admin-common-styles.php';    ?>

  </head>
  <body>
    <?php
include '../../../php/components/admin/nav-bar-admin.php'; ?>

    <section
      class="container bg-warning-subtle mx-auto p-4 mt-5 bg-buke-tours profile-form-section"
      style="max-width: 600px"
    >
      <h1 class="titulo mb-4"><?php echo $lang['iniciar_sesion'];?></h1>
      <form id="login-formadmin" class="mb-2" novalidate>
        <label
          for="email"
          class="form-label d-flex text-start d-flex text-start"
          ><?php echo $lang['correo_electronico_ph'] ?></label
        >
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="<?php echo $lang['correo_electronico_ph'];?>"
        />
        <br />
        <label
          for="password"
          class="form-label d-flex text-start d-flex text-start"
          ><?php echo $lang['contrasena_ph'] ?></label
        >
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="<?php echo $lang['contrasena_ph'];?>"
        />
        <br />
        <button type="submit" id="btn-login" class="login-boton btn w-100">
          <?php echo $lang['iniciar_sesion'];?>
        </button>
      </form>
      <!-- <div class="login-no-tienes-una-cuenta">
        <h2 class="subtitulo"><?php echo $lang['no_tienes_cuenta_msg'];?></h2>
        <a href="/Buke-Tours/auth/signup/"
          ><button class="login-boton btn w-100">
            <?php echo $lang['registrarme'];?>
          </button></a
        >
      </div> -->
    </section>
    <?php 
      include '../../../php/components/cart-modal.php';
      include '../../../php/scripts/common-scripts.php';
    ?>
    <script type="module" src="/Buke-Tours/assets/js/admins/login-page-admin.js"  defer></script>
  </body>
</html>
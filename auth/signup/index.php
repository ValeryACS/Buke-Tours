
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
      include '../../php/components/navbar.php';
    ?>
    <section
      class="container bg-warning-subtle mx-auto p-4 mt-5 bg-buke-tours"
      style="max-width: 500px"
    >
      <h1><?php echo $lang['registrarse']; ?></h1>
      <form>
        <input
          required
          type="text"
          class="login-textinput"
          placeholder="<?php echo $lang['nombre_completo']; ?>..."
        />
        <br />
        <input
          required
          type="email"
          class="login-textinput"
          placeholder="<?php echo $lang['correo_electronico']; ?>..."
        />
        <br />
        <input
          type="tel"
          minlength="8"
          maxlength="8"
          onkeypress="return /[0-9]/i.test(event.key)"
          class="login-textinput"
          placeholder="<?php echo $lang['numero_telefono']; ?>..."
        />
        <br />
        <input
          required
          type="password"
          minlength="5"
          class="login-textinput"
          placeholder="<?php echo $lang['contrasena']; ?>..."
        />
        <br />
        <input
          required
          type="password"
          minlength="5"
          class="login-textinput"
          placeholder="<?php echo $lang['conf_contrasena']; ?>..."
        />
        <br />
        <button class="login-boton btn"><?php echo $lang['registrarse']; ?></button>
      </form>
    </section>
    <?php 
      include '../../php/components/footer.php';
      include '../../php/components/cart-modal.php';
      include '../../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

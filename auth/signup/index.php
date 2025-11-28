<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
      <h1>Registrarse</h1>
      <form>
        <input
          required
          type="text"
          class="login-textinput"
          placeholder="Nombre completo..."
        />
        <br />
        <input
          required
          type="email"
          class="login-textinput"
          placeholder="Correo electrónico..."
        />
        <br />
        <input
          type="tel"
          minlength="8"
          maxlength="8"
          onkeypress="return /[0-9]/i.test(event.key)"
          class="login-textinput"
          placeholder="Telefono..."
        />
        <br />
        <input
          required
          type="password"
          minlength="5"
          class="login-textinput"
          placeholder="Contraseña..."
        />
        <br />
        <input
          required
          type="password"
          minlength="5"
          class="login-textinput"
          placeholder="Confirmar contraseña..."
        />
        <br />
        <button class="login-boton btn">Registrarse</button>
      </form>
    </section>
    <?php 
      include '../../php/components/footer.php';
      include '../../php/components/cart-modal.php';
      include '../../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

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
      <h1>Iniciar Sesión</h1>
      <form>
        <input
          required
          type="email"
          class="login-textinput"
          placeholder="Correo electrónico..."
        />
        <br />
        <input
          required
          type="password"
          class="login-textinput"
          placeholder="Contraseña..."
        />
        <br />
        <button class="login-boton btn">Iniciar Sesión</button>
      </form>
      <br />
      <br />
      <br />
      <div class="login-no-tienes-una-cuenta">
        <h2>No tienes una cuenta?</h2>
        <a href="/Buke-Tours/auth/signup/"
          ><button class="login-boton btn">Registrarme</button></a
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

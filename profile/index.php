<?php

/**
 * Usado para renderizar el formulario del perfil del usuario
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
    <title>Perfil</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
  </head>
  <body>
    <?php
    include '../php/components/navbar.php';
    ?>

    <main class="perfil-contenedor">
      <div class="perfil-p"></div>
      <div class="perfil-formulario">
        <div class="container py-5">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
              <div class="card shadow-sm">
                <div class="card-body p-4">
                  <h1 class="titulo mb-4"><?php echo $lang['perfil_titulo_editar'] ?></h1>

                  <form action="" novalidate>
                    <div class="row g-3">
                      <!-- Columna izquierda -->
                      <div class="col-12 col-md-6">
                        <label for="fullName" class="form-label"
                          ><?php echo $lang['nombre_completo'] ?></label
                        >
                        <input
                          id="fullName"
                          name="fullName"
                          type="text"
                          class="form-control"
                          placeholder="<?php echo $lang['nombre_completo'] ?>"
                          autocomplete="name"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="email" class="form-label"
                          ><?php echo $lang['correo_electronico'] ?></label
                        >
                        <input
                          id="email"
                          name="email"
                          type="email"
                          class="form-control"
                          placeholder="<?php echo $lang['correo_electronico'] ?>"
                          autocomplete="email"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="phone" class="form-label"
                          ><?php echo $lang['numero_telefono'] ?></label
                        >
                        <input
                          id="phone"
                          name="phone"
                          type="tel"
                          class="form-control"
                          placeholder="<?php echo $lang['numero_telefono'] ?>"
                          autocomplete="tel"
                          inputmode="tel"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="password" class="form-label"
                          ><?php echo $lang['contrasena'] ?></label
                        >
                        <input
                          id="password"
                          name="password"
                          type="password"
                          class="form-control"
                          placeholder="<?php echo $lang['contrasena'] ?>"
                          autocomplete="new-password"
                          required
                        />
                      </div>

                      <!-- Columna derecha -->
                      <div class="col-12">
                        <label for="address" class="form-label"
                          ><?php echo $lang['direccion_completa'] ?></label
                        >
                        <input
                          id="address"
                          name="address"
                          type="text"
                          class="form-control"
                          placeholder="<?php echo $lang['direccion_completa'] ?>"
                          autocomplete="street-address"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="country" class="form-label"><?php echo $lang['pais'] ?></label>
                        <input
                          id="country"
                          name="country"
                          type="text"
                          class="form-control"
                          placeholder="<?php echo $lang['pais'] ?>"
                          autocomplete="country-name"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="language" class="form-label"><?php echo $lang['idioma'] ?></label>
                        <input
                          id="language"
                          name="language"
                          type="text"
                          class="form-control"
                          placeholder="<?php echo $lang['idioma'] ?>"
                          required
                        />
                      </div>

                      <div class="col-12 d-flex justify-content-end pt-2">
                        <button type="submit" class="btn btn-danger px-4">
                          <?php echo $lang['boton_guardar_cambios'] ?>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

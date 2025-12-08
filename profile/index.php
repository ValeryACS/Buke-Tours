<?php

/**
 * Usado para renderizar el formulario del perfil del usuario
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
include '../helpers/get-country.php';

$html_lang = $_SESSION['lang']; 

$profileSession = [
    'nombre' => $_SESSION['nombre'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'telefono' => $_SESSION['telefono'] ?? '',
    'fecha_nacimiento' => $_SESSION['fecha_nacimiento'] ?? '',
    'pais' => $_SESSION['pais'] ?? '',
    'genero' => $_SESSION['genero'] ?? '',
    'idioma' => $_SESSION['idioma'] ?? '',
    'passport' => $_SESSION['passport'] ?? '',
    'direccion' => $_SESSION['direccion'] ?? '',
    'ciudad' => $_SESSION['ciudad'] ?? '',
    'provincia' => $_SESSION['provincia'] ?? '',
    'codigo_postal' => $_SESSION['codigo_postal'] ?? '',
];

$sessionValue = function (string $key) use ($profileSession): string {
    return htmlspecialchars((string)($profileSession[$key] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Perfil</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
     <link rel="stylesheet" href="/Buke-Tours/assets/css/profile.css" />
  </head>
  <body>
    <?php
    require_once '../config.php';
    ?>
    <?php echo htmlspecialchars($html_lang, ENT_QUOTES, 'UTF-8'); ?>
    <main class="perfil-contenedor">
      <section class="container bg-warning-subtle mx-auto p-4 mt-5 bg-buke-tours profile-form-section">
        <h1 class="titulo mb-4">Editar Usuario</h1>
        <form id="profile-form" novalidate>
          <div class="row g-3">
            <!-- Columna izquierda -->
            <div class="col-12">
              <label
                for="fullName"
                class="form-label d-flex text-start d-flex text-start"
                ><?php echo $lang['nombre_completo']; ?></label
              >
              <input
                id="fullName"
                name="fullName"
                type="text"
                class="form-control"
                placeholder="<?php echo $lang['nombre_completo']; ?>"
                autocomplete="name"
                value="<?php echo $sessionValue('nombre'); ?>"
              />
            </div>

            <div class="col-12 col-md-6">
              <label for="email" class="form-label d-flex text-start"
                ><?php echo $lang['correo_electronico_ph']; ?></label
              >
              <input
                id="email"
                name="email"
                type="email"
                class="form-control"
                placeholder="<?php echo $lang['correo_electronico_ph']; ?>"
                autocomplete="email"
                value="<?php echo $sessionValue('email'); ?>"
              />
            </div>

            <div class="col-12 col-md-6">
              <label for="phone" class="form-label d-flex text-start"
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
                value="<?php echo $sessionValue('telefono'); ?>"
              />
            </div>

            <div class="col-12 col-md-6">
              <label
                for="password"
                class="form-label d-flex text-start"
                ><?php echo $lang['contrasena'] ?></label
              >
              <input
                id="password"
                name="password"
                type="password"
                class="form-control"
                placeholder="<?php echo $lang['contrasena'] ?>"
                autocomplete="new-password"
              />
            </div>
            <div class="col-12 col-md-6">
              <label
                for="confirm-password"
                class="form-label d-flex text-start"
                >Confirmar
                <?php echo $lang['contrasena'] ?></label
              >
              <input
                id="confirm-password"
                name="confirm-password"
                type="password"
                class="form-control"
                placeholder="<?php echo $lang['contrasena'] ?>"
                required
              />
            </div>
            <div class="col-12 col-md-6 form-group text-start mb-3">
              <label
                for="birthdate"
                class="form-label d-flex text-start"
                >Fecha de nacimiento</label
              >
              <input
                type="date"
                class="form-control"
                id="birthdate"
                name="birthdate"
                value="<?php echo $sessionValue('fecha_nacimiento'); ?>"
              />
            </div>
            <div class="col-12 col-md-6 form-group text-start mb-3">
              <label class="form-label d-block d-flex text-start"
                >Género</label
              >
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input genre-radio-button"
                  type="radio"
                  name="genero"
                  id="masculino"
                  value="Masculino"
                  <?php
                if($profileSession['genero'] ==='Masculino'){
                  echo 'checked';
                }
                ?>
                  checked
                />
                <label
                  class="form-check-label d-flex text-start"
                  for="masculino"
                  >Masculino</label
                >
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input genre-radio-button"
                  type="radio"
                  name="genero"
                  id="femenino"
                  value="Femenino"
                   <?php
                    if($profileSession['genero'] ==='Femenino'){
                      echo 'checked';
                    }
                ?>
                />
                <label
                  class="form-check-label d-flex text-start"
                  for="femenino"
                  >Femenino</label
                >
              </div>
            </div>
            <div class="col-12 col-md-6 form-group text-start">
              <label for="idioma" class="form-label d-flex text-start"
                >Idioma</label
              >
              <select
                id="idioma"
                name="idioma"
                class="form-select"
                aria-label="Idioma Seleccionado"
              >
                <option value="en"  <?php if($profileSession['idioma'] ==='en'){ echo 'selected'; } ?>>Ingles</option>
                <option value="es" <?php if($profileSession['idioma'] ==='es'){ echo 'selected'; } ?>>Español</option>
              </select>
            </div>
            <div class="col-12 col-md-6 form-group">
              <span
                id="flagCountry"
                class="fi d-none"
                aria-hidden="true"
              ></span>
              <label for="country" class="form-label"
                >País de residencia</label
              >
              <select
                class="form-select"
                autocomplete="country-name"
                id="country"
                name="country"
                placeholder="<?php echo $lang['pais'] ?>"
                required
              >
                <?php 
                echo getCountrySelected($sessionValue('pais'));
                ?>
              </select>
            </div>
            <div class="col-12 col-md-12 form-group text-start">
              <label
                for="documento"
                class="form-label d-flex text-start"
                >Cédula / Pasaporte</label
              >
              <input
                id="documento"
                name="documento"
                type="text"
                class="form-control"
                placeholder="Número de Cédula"
                maxlength="20"
                value="<?php echo $sessionValue('passport'); ?>"
              />
            </div>
            <div class="col-12 text-start">
              <label for="direccion" class="form-label"
                ><?php echo $lang['direccion_completa'] ?></label
              >
              <input
                id="direccion"
                name="direccion"
                type="text"
                class="form-control"
                placeholder="<?php echo $lang['direccion_completa'] ?>"
                autocomplete="street-address"
                maxlength="200"
                value="<?php echo $sessionValue('direccion'); ?>"
              />
            </div>
            <div class="col-12 col-md-6">
              <label for="ciudad" class="form-label d-flex text-start"
                >Ciudad</label
              >
              <input
                id="ciudad"
                name="ciudad"
                type="text"
                class="form-control"
                maxlength="50"
                value="<?php echo $sessionValue('ciudad'); ?>"
              />
            </div>
            <div class="col-6 col-md-3">
              <label
                for="provincia"
                class="form-label d-flex text-start"
                >Provincia</label
              >
              <input
                id="provincia"
                name="provincia"
                type="text"
                class="form-control"
                maxlength="60"
                value="<?php echo $sessionValue('provincia'); ?>"
              />
            </div>
            <div class="col-6 col-md-3">
              <label for="zip" class="form-label d-flex text-start"
                >Código Postal</label
              >
              <input
                id="zip"
                name="zip"
                type="text"
                class="form-control"
                autocomplete="postal-code"
                maxlength="30"
                value="<?php echo $sessionValue('codigo_postal'); ?>"
              />
            </div>
            <div class="col-12 d-flex justify-content-end pt-2">
              <button
                type="submit"
                class="btn btn-success w-100 px-4"
                id="btn-profile"
              >
                <?php echo $lang['boton_guardar_cambios'] ?>
              </button>
            </div>
          </div>
        </form>
      </section>
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
    <script
      type="module"
      src="/Buke-Tours/assets/js/profile-page.js"
      defer
    ></script>
  </body>
</html>

<?php
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../../php/config/db.php");
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

$adminID = isset($_SESSION['admin_id'])? (int)$_SESSION['admin_id']: 0;

if($adminID<= 0){
    header("Location: ../auth/login/");
    exit();
}

include '../../language/lang_' . $_SESSION['lang'] . '.php'; 
include '../../php/helpers/get-country.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Agregar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>
  
<div class="container py-4">

  <h1>Agregar Cliente</h1>
   <form id="admin-form" novalidate>
          <div class="row g-3">
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
                <option value="en">Ingles</option>
                <option value="es">Español</option>
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
                echo getCountrySelected("");
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="module" src="/Buke-Tours/assets/js/customers/customers-profile-page.js"  defer></script>

</body>
</html>

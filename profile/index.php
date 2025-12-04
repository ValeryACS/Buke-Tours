<?php

/**
 * Usado para renderizar el formulario del perfil del usuario
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                  <h1 class="titulo mb-4">
                    <?php echo $lang['perfil_titulo_editar'] ?>
                  </h1>

                  <form id="profile-form" novalidate>
                    <div class="row g-3">
                      <!-- Columna izquierda -->
                      <div class="col-12 ">
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
                      <div class="col-12 col-md-6">
                        <label for="confirm-password" class="form-label"
                          >Confirmar <?php echo $lang['contrasena'] ?></label
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
                        <label for="fecha" class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="fecha">
                      </div>
                      <div class="col-12 col-md-6 form-group text-start mb-3">
                        <label class="form-label d-block">Género</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="genero" id="masculino" value="Masculino">
                          <label class="form-check-label" for="masculino">Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="genero" id="femenino" value="Femenino">
                          <label class="form-check-label" for="femenino">Femenino</label>
                        </div>
                      </div>
                      <div class="col-12 col-md-6 form-group text-start">
                        <label for="idioma" class="form-label"
                          >Idioma</label
                        >
                        <select
                          id="idioma"
                          name="idioma"
                          class="form-select"
                          aria-label="Idioma Seleccionado"
                        >
                          <option selected value="no-seleccionado">
                            Seleccione un Idioma
                          </option>
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
                          <option value="" selected disabled>
                            Selecciona un país
                          </option>
                          <option value="AF">Afganistán</option>
                          <option value="AL">Albania</option>
                          <option value="DE">Alemania</option>
                          <option value="AD">Andorra</option>
                          <option value="AO">Angola</option>
                          <option value="AI">Anguila</option>
                          <option value="AQ">Antártida</option>
                          <option value="AG">Antigua y Barbuda</option>
                          <option value="SA">Arabia Saudita</option>
                          <option value="DZ">Argelia</option>
                          <option value="AR">Argentina</option>
                          <option value="AM">Armenia</option>
                          <option value="AW">Aruba</option>
                          <option value="AU">Australia</option>
                          <option value="AT">Austria</option>
                          <option value="AZ">Azerbaiyán</option>
                          <option value="BS">Bahamas</option>
                          <option value="BH">Baréin</option>
                          <option value="BD">Bangladés</option>
                          <option value="BB">Barbados</option>
                          <option value="BE">Bélgica</option>
                          <option value="BZ">Belice</option>
                          <option value="BJ">Benín</option>
                          <option value="BM">Bermudas</option>
                          <option value="BY">Bielorrusia</option>
                          <option value="BO">Bolivia</option>
                          <option value="BA">Bosnia y Herzegovina</option>
                          <option value="BW">Botsuana</option>
                          <option value="BR">Brasil</option>
                          <option value="BN">Brunéi</option>
                          <option value="BG">Bulgaria</option>
                          <option value="BF">Burkina Faso</option>
                          <option value="BI">Burundi</option>
                          <option value="BT">Bután</option>
                          <option value="CV">Cabo Verde</option>
                          <option value="KH">Camboya</option>
                          <option value="CM">Camerún</option>
                          <option value="CA">Canadá</option>
                          <option value="QA">Catar</option>
                          <option value="CL">Chile</option>
                          <option value="CN">China</option>
                          <option value="CY">Chipre</option>
                          <option value="CO">Colombia</option>
                          <option value="KM">Comoras</option>
                          <option value="CG">Congo</option>
                          <option value="CD">Congo (Rep. Dem.)</option>
                          <option value="KP">Corea del Norte</option>
                          <option value="KR">Corea del Sur</option>
                          <option value="CR">Costa Rica</option>
                          <option value="CI">Costa de Marfil</option>
                          <option value="HR">Croacia</option>
                          <option value="CU">Cuba</option>
                          <option value="DK">Dinamarca</option>
                          <option value="DM">Dominica</option>
                          <option value="EC">Ecuador</option>
                          <option value="EG">Egipto</option>
                          <option value="SV">El Salvador</option>
                          <option value="AE">Emiratos Árabes Unidos</option>
                          <option value="ER">Eritrea</option>
                          <option value="SK">Eslovaquia</option>
                          <option value="SI">Eslovenia</option>
                          <option value="ES">España</option>
                          <option value="US">Estados Unidos</option>
                          <option value="EE">Estonia</option>
                          <option value="ET">Etiopía</option>
                          <option value="PH">Filipinas</option>
                          <option value="FI">Finlandia</option>
                          <option value="FR">Francia</option>
                          <option value="GA">Gabón</option>
                          <option value="GM">Gambia</option>
                          <option value="GE">Georgia</option>
                          <option value="GH">Ghana</option>
                          <option value="GI">Gibraltar</option>
                          <option value="GR">Grecia</option>
                          <option value="GD">Granada</option>
                          <option value="GL">Groenlandia</option>
                          <option value="GT">Guatemala</option>
                          <option value="GN">Guinea</option>
                          <option value="GQ">Guinea Ecuatorial</option>
                          <option value="GW">Guinea-Bisáu</option>
                          <option value="GY">Guyana</option>
                          <option value="HT">Haití</option>
                          <option value="HN">Honduras</option>
                          <option value="HU">Hungría</option>
                          <option value="IN">India</option>
                          <option value="ID">Indonesia</option>
                          <option value="IR">Irán</option>
                          <option value="IQ">Irak</option>
                          <option value="IE">Irlanda</option>
                          <option value="IS">Islandia</option>
                          <option value="IL">Israel</option>
                          <option value="IT">Italia</option>
                          <option value="JM">Jamaica</option>
                          <option value="JP">Japón</option>
                          <option value="JO">Jordania</option>
                          <option value="KZ">Kazajistán</option>
                          <option value="KE">Kenia</option>
                          <option value="KG">Kirguistán</option>
                          <option value="KI">Kiribati</option>
                          <option value="KW">Kuwait</option>
                          <option value="LA">Laos</option>
                          <option value="LS">Lesoto</option>
                          <option value="LV">Letonia</option>
                          <option value="LB">Líbano</option>
                          <option value="LR">Liberia</option>
                          <option value="LY">Libia</option>
                          <option value="LI">Liechtenstein</option>
                          <option value="LT">Lituania</option>
                          <option value="LU">Luxemburgo</option>
                          <option value="MG">Madagascar</option>
                          <option value="MY">Malasia</option>
                          <option value="MW">Malaui</option>
                          <option value="MV">Maldivas</option>
                          <option value="ML">Malí</option>
                          <option value="MT">Malta</option>
                          <option value="MA">Marruecos</option>
                          <option value="MU">Mauricio</option>
                          <option value="MR">Mauritania</option>
                          <option value="MX">México</option>
                          <option value="FM">Micronesia</option>
                          <option value="MD">Moldavia</option>
                          <option value="MC">Mónaco</option>
                          <option value="MN">Mongolia</option>
                          <option value="ME">Montenegro</option>
                          <option value="MZ">Mozambique</option>
                          <option value="NA">Namibia</option>
                          <option value="NR">Nauru</option>
                          <option value="NP">Nepal</option>
                          <option value="NI">Nicaragua</option>
                          <option value="NE">Níger</option>
                          <option value="NG">Nigeria</option>
                          <option value="NO">Noruega</option>
                          <option value="NZ">Nueva Zelanda</option>
                          <option value="OM">Omán</option>
                          <option value="NL">Países Bajos</option>
                          <option value="PK">Pakistán</option>
                          <option value="PW">Palaos</option>
                          <option value="PA">Panamá</option>
                          <option value="PG">Papúa Nueva Guinea</option>
                          <option value="PY">Paraguay</option>
                          <option value="PE">Perú</option>
                          <option value="PL">Polonia</option>
                          <option value="PT">Portugal</option>
                          <option value="GB">Reino Unido</option>
                          <option value="CF">República Centroafricana</option>
                          <option value="CZ">República Checa</option>
                          <option value="DO">República Dominicana</option>
                          <option value="RO">Rumania</option>
                          <option value="RU">Rusia</option>
                          <option value="RW">Ruanda</option>
                          <option value="WS">Samoa</option>
                          <option value="SM">San Marino</option>
                          <option value="ST">Santo Tomé y Príncipe</option>
                          <option value="SN">Senegal</option>
                          <option value="RS">Serbia</option>
                          <option value="SC">Seychelles</option>
                          <option value="SL">Sierra Leona</option>
                          <option value="SG">Singapur</option>
                          <option value="SY">Siria</option>
                          <option value="SO">Somalia</option>
                          <option value="ZA">Sudáfrica</option>
                          <option value="SD">Sudán</option>
                          <option value="SS">Sudán del Sur</option>
                          <option value="SE">Suecia</option>
                          <option value="CH">Suiza</option>
                          <option value="SR">Surinam</option>
                          <option value="TH">Tailandia</option>
                          <option value="TZ">Tanzania</option>
                          <option value="TJ">Tayikistán</option>
                          <option value="TL">Timor Oriental</option>
                          <option value="TG">Togo</option>
                          <option value="TO">Tonga</option>
                          <option value="TT">Trinidad y Tobago</option>
                          <option value="TN">Túnez</option>
                          <option value="TM">Turkmenistán</option>
                          <option value="TR">Turquía</option>
                          <option value="TV">Tuvalu</option>
                          <option value="UA">Ucrania</option>
                          <option value="UG">Uganda</option>
                          <option value="UY">Uruguay</option>
                          <option value="UZ">Uzbekistán</option>
                          <option value="VU">Vanuatu</option>
                          <option value="VA">Vaticano</option>
                          <option value="VE">Venezuela</option>
                          <option value="VN">Vietnam</option>
                          <option value="YE">Yemen</option>
                          <option value="ZM">Zambia</option>
                          <option value="ZW">Zimbabue</option>
                        </select>
                      </div>
                      <div class="col-12 col-md-12 form-group text-start">
                        <label for="documento" class="form-label"
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
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input
                          id="ciudad"
                          name="ciudad"
                          type="text"
                          class="form-control"
                          maxlength="50"
                        />
                      </div>
                      <div class="col-6 col-md-3">
                        <label for="provincia" class="form-label"
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
                        <label for="zip" class="form-label"
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
                          class="btn btn-danger-buke-tours px-4"
                          id="btn-profile"
                        >
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

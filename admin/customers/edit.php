<?php
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

$adminID = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;

if($adminID <= 0){
    header("Location: ../auth/login/");
    exit();
}

include '../../language/lang_' . $_SESSION['lang'] . '.php'; 
include '../../php/helpers/get-country.php';
include("../../php/config/db.php");

// 1. Redirección si no hay ID, con exit()
if (!isset($_GET['id'])) {
    header('Location: ../admin/customers/');
    exit();
}

$mysqli = openConnection();

// Consulta preparada para obtener el cliente
$sqlCustomer = 'SELECT * FROM `customer` WHERE id = ? LIMIT 1';

$customers = $mysqli->prepare($sqlCustomer);

$customers->bind_param("i", $_GET['id']);
$customers->execute();
$resultadocustomers = $customers->get_result();

// 2. Simplificación: Obtener el resultado directamente como array asociativo
$customerseleccionado = $resultadocustomers->fetch_assoc();

if ($resultadocustomers) {
    $resultadocustomers->free();
}

// 3. Redirección si el administrador no existe
if (!$customerseleccionado) {
    header('Location: ../admin/customers/');
    exit();
}

// 4. Variables para la selección en el formulario (Usando nombres de columnas de la tabla)
// Nota: La tabla usa 'genre', 'lang', 'country', 'birth_date', 'passport', 'home_addres', 'city', 'province', 'zip_code'
$generoAdmin = $customerseleccionado['genre'] ?? '';
$idiomaAdmin = $customerseleccionado['lang'] ?? 'es';
$paisAdmin = $customerseleccionado['country'] ?? ''; 
$adminIdActual = $customerseleccionado['id'] ?? 0; // Se obtiene el ID para el campo oculto
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>
    
<div class="container py-4">

    <h1>Editar Cliente</h1>
<?php if(!empty($errors)):?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach($errors as $e):?>
                        <li><?=htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php endif; ?>

    <form id="admin-form" novalidate>
        <input type="hidden" id="admin_id" name="admin_id" value="<?= $adminIdActual ?>">

            <div class="row g-3">
            <div class="col-12">
                <label
                    for="fullName"
                    class="form-label d-flex text-start"
                    ><?php echo $lang['nombre_completo']; ?></label
                >
                <input
                    id="fullName"
                    name="fullName"
                    type="text"
                    class="form-control"
                    placeholder="<?php echo $lang['nombre_completo']; ?>"
                    autocomplete="name"
                    value="<?= htmlspecialchars($customerseleccionado['full_name'] ?? '')?>"
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
                    value="<?= htmlspecialchars($customerseleccionado['email'] ?? '')?>"
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
                    value="<?= htmlspecialchars($customerseleccionado['phone'] ?? '')?>"
                />
            </div>

            <div class="col-12 col-md-6">
                <label
                    for="password"
                    class="form-label d-flex text-start"
                    >Contraseña (Opcional)</label
                >
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    placeholder="Dejar vacío si no desea cambiar"
                    autocomplete="new-password"
                />
            </div>
            <div class="col-12 col-md-6">
                <label
                    for="confirm-password"
                    class="form-label d-flex text-start"
                    >Confirmar Contraseña (Opcional)</label
                >
                <input
                    id="confirm-password"
                    name="confirm-password"
                    type="password"
                    class="form-control"
                    placeholder="Dejar vacío si no desea cambiar"
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
                    value="<?= htmlspecialchars($customerseleccionado['birth_date'] ?? '')?>"
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
                        <?= (strtolower($generoAdmin) === 'masculino') ? 'checked' : '' ?>
                        
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
                        <?= (strtolower($generoAdmin) === 'femenino') ? 'checked' : '' ?>
                        
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
                    <option value="en" <?= $idiomaAdmin === 'en' ? 'selected' : '' ?>>Ingles</option>
                    <option value="es" <?= $idiomaAdmin === 'es' ? 'selected' : '' ?>>Español</option>
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
                    echo getCountrySelected($paisAdmin);
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
                    value="<?= htmlspecialchars($customerseleccionado['passport'] ?? '')?>"
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
                    value="<?= htmlspecialchars($customerseleccionado['home_addres'] ?? '')?>"
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
                    value="<?= htmlspecialchars($customerseleccionado['city'] ?? '')?>"
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
                    value="<?= htmlspecialchars($customerseleccionado['province'] ?? '')?>"

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
                    value="<?= htmlspecialchars($customerseleccionado['zip_code'] ?? '')?>"

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
<script type="module" src="/Buke-Tours/assets/js/customers/edit-profile-page.js"  defer></script>
</body>
</html>
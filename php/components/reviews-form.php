<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

$langFile = dirname(__DIR__, 1) . '/../language/lang_' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    include $langFile;
} else {
    include dirname(__DIR__, 1) . '/../language/lang_es.php';
}

$html_lang = $_SESSION['lang'];

require_once __DIR__ . '/../config/db.php';

$userID = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;


$mysqli = openConnection();

$sqlTours = 'SELECT * FROM `customer_purchased_tours` WHERE `customer_id`= ?';
$toursDisponibles= $mysqli->prepare($sqlTours);
$toursDisponibles->bind_param("i", $userID);
$toursDisponibles->execute();
$toursResult = $toursDisponibles->get_result();

closeConnection($mysqli);

?>
<div class="formulario-resena">
        <form id="formulario-resena">
            <input value="<?php echo $userID; ?>" type="hidden" name="customerId" id="customerId" readonly class="d-none" />
            <label for="full_name"><?php echo $lang['label_nombre'];?></label>
            <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $_SESSION['nombre'] ?? ''; ?>"/>
            <label for="tour-id">Nombre del Tour</label>
            <select id="tour-id" name="tour-id" class="form-select">
                <option value="no-seleccionado" selected>Seleccione un Tour</option>
                <?php
                if ($toursResult) {
                        while ($fila = $toursResult->fetch_assoc()):
                            echo  "<option value='". $fila['tour_id']."'>". $fila['tour_title']."</option>";

                        endwhile;
                }
                ?>
            </select>
            <label for="calificacion"><?php echo $lang['label_calificacion'];?></label>
            <select id="calificacion" name="calificacion"  class="form-select">
                <option value="5" selected>⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <label for="comentario"><?php echo $lang['comentario'];?></label>
            <textarea
                id="comentario"
                name="comentario"
                rows="4"
                class="form-control"
            ></textarea>

            <button type="submit" id="btn-save-review"><?php echo $lang['boton_enviar'];?></button>
        </form>
</div>
<script src="/Buke-Tours/assets/js/reviews-page.js"  type="module" defer></script>

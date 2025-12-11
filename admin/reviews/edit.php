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

if (!isset($_GET['id'])) {
    header('Location: ../admin/reviews/');
    exit();
}

$mysqli = openConnection();

$sqlreview = 'SELECT * FROM `feedback` WHERE id = ? LIMIT 1';

$reviews = $mysqli->prepare($sqlreview);

$reviews->bind_param("i", $_GET['id']);
$reviews->execute();
$resultadoreviews = $reviews->get_result();

$reviewseleccionado = $resultadoreviews->fetch_assoc();

if ($resultadoreviews) {
    $resultadoreviews->free();
}

if (!$reviewseleccionado) {
    header('Location: ../admin/reviews/');
    exit();
}
$validStatuses = ['Aprobada', 'Denegada', 'Pendiente'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Reseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>
<div class="container py-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="h4 m-auto titulo px-5">Editar Reseña</h4>
  </div>

    <?php if(!empty($errors)):?>
        <?php endif; ?>

    <form id="review-edit-form" novalidate>
        <input type="hidden" name="review_id" value="<?= htmlspecialchars($reviewseleccionado['id'] ?? '0') ?>">
        <div class="row g-3">
            <div class="col-12">
                <label for="fullName" class="form-label d-flex text-start"><?php echo $lang['nombre_completo']; ?></label>
                <input id="fullName" name="fullName" type="text" class="form-control" value="<?= htmlspecialchars($reviewseleccionado['full_name'] ?? '')?>" disabled />
            </div>
            <div class="col-12 col-md-6">
                <label for="idTour" class="form-label d-flex text-start">ID del tour</label>
                <input id="idTour" name="idTour" type="text" class="form-control" value="<?= htmlspecialchars($reviewseleccionado['tour_id'] ?? '')?>" disabled />
            </div>
            <div class="col-12 col-md-6">
                <label for="score" class="form-label d-flex text-start">Puntuación</label>
                <input id="score" name="score" type="text" class="form-control" value="<?= htmlspecialchars($reviewseleccionado['score'] ?? '')?>⭐" disabled />
            </div>
            <div class="col-12 col-md-6">
                <label for="comment" class="form-label d-flex text-start">Comentario</label>
                <input id="comment" name="comment" type="text" class="form-control" value="<?= htmlspecialchars($reviewseleccionado['comment'] ?? '')?>" disabled />
            </div>
            <div class="col-12 col-md-6">
                <label for="status" class="form-label d-flex text-start">Estado</label>
                <select id="status" name="status" class="form-select" required>
                    <?php foreach ($validStatuses as $status): ?>
                        <option value="<?php echo $status; ?>" 
                            <?php echo ($status === $reviewseleccionado['status']) ? 'selected' : ''; ?>>
                            <?php echo $status; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end pt-2">
                <button
                    type="submit"
                    class="btn btn-danger w-100 px-4"
                    id="btn-save-status" >
                    <?php echo $lang['boton_guardar_cambios'] ?>
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="/Buke-Tours/assets/js/reviews/edit-reviews-page.js" defer></script>
</body>
</html>
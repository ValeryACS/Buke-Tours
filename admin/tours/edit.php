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
    $_SESSION['lang'] = 'es';
}

$adminID = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
if ($adminID <= 0) {
    header("Location: ../auth/login/");
    exit();
}

include '../../language/lang_' . $_SESSION['lang'] . '.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: /Buke-Tours/admin/tours/index.php?error=" . urlencode("ID de tour inválido."));
    exit();
}

$mysqli = openConnection();

$stmt = $mysqli->prepare("SELECT * FROM tour WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tour   = $result ? $result->fetch_assoc() : null;
$stmt->close();
closeConnection($mysqli);

if (!$tour) {
    header("Location: /Buke-Tours/admin/tours/index.php?error=" . urlencode("Tour no encontrado."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container py-4 mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="h4 m-auto titulo px-5">Editar Tour</h4>
  </div>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <form id="tour-edit-form" novalidate>
    <input type="hidden" name="id" id="tour_id" value="<?php echo (int)$tour['id']; ?>" />

    <div class="row g-3">
      <div class="col-12 col-md-6">
        <label for="sku" class="form-label d-flex text-start">SKU</label>
        <input
          id="sku"
          name="sku"
          type="text"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['sku']); ?>"
          required
        />
      </div>

      <div class="col-12 col-md-6">
        <label for="title" class="form-label d-flex text-start">Nombre del Tour</label>
        <input
          id="title"
          name="title"
          type="text"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['title']); ?>"
          required
        />
      </div>

      <div class="col-12">
        <label for="location" class="form-label d-flex text-start">Ubicación</label>
        <input
          id="location"
          name="location"
          type="text"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['location']); ?>"
          required
        />
      </div>

      <div class="col-12">
        <label for="description" class="form-label d-flex text-start">Descripción</label>
        <textarea
          id="description"
          name="description"
          rows="4"
          class="form-control"
          required
        ><?php echo htmlspecialchars($tour['description']); ?></textarea>
      </div>

      <div class="col-12 col-md-4">
        <label for="price_usd" class="form-label d-flex text-start">Precio (USD)</label>
        <input
          id="price_usd"
          name="price_usd"
          type="number"
          step="0.01"
          min="0"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['price_usd']); ?>"
          required
        />
      </div>

      <div class="col-12 col-md-4">
        <label for="rating" class="form-label d-flex text-start">Rating (1.0 - 5.0)</label>
        <input
          id="rating"
          name="rating"
          type="number"
          step="0.1"
          min="1"
          max="5"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['rating']); ?>"
          required
        />
      </div>

      <div class="col-12 col-md-4">
        <label for="duration_hours" class="form-label d-flex text-start">Duración (horas)</label>
        <input
          id="duration_hours"
          name="duration_hours"
          type="number"
          step="0.5"
          min="0"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['duration_hours']); ?>"
          required
        />
      </div>

      <div class="col-12 col-md-4">
        <label for="adults_limit" class="form-label d-flex text-start">Límite adultos</label>
        <input
          id="adults_limit"
          name="adults_limit"
          type="number"
          min="0"
          class="form-control"
          value="<?php echo (int)$tour['adults_limit']; ?>"
          required
        />
      </div>

      <div class="col-12 col-md-4">
        <label for="children_limit" class="form-label d-flex text-start">Límite niños</label>
        <input
          id="children_limit"
          name="children_limit"
          type="number"
          min="0"
          class="form-control"
          value="<?php echo (int)$tour['children_limit']; ?>"
          required
        />
      </div>

      <div class="col-12 col-md-4">
        <label for="discount" class="form-label d-flex text-start">Descuento (%)</label>
        <input
          id="discount"
          name="discount"
          type="number"
          min="0"
          max="100"
          class="form-control"
          value="<?php echo (int)$tour['discount']; ?>"
          required
        />
      </div>

      <div class="col-12">
        <label for="img" class="form-label d-flex text-start">URL de imagen</label>
        <input
          id="img"
          name="img"
          type="text"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['img']); ?>"
          required
        />
      </div>

      <div class="col-12 col-md-6">
        <label for="cupon_code" class="form-label d-flex text-start">Código de cupón (opcional)</label>
        <input
          id="cupon_code"
          name="cupon_code"
          type="text"
          class="form-control"
          value="<?php echo htmlspecialchars($tour['cupon_code'] ?? ''); ?>"
        />
      </div>

      <div class="col-12 col-md-6">
        <label for="iframe" class="form-label d-flex text-start">Iframe / mapa (opcional)</label>
        <textarea
          id="iframe"
          name="iframe"
          rows="2"
          class="form-control"
        ><?php echo htmlspecialchars($tour['iframe'] ?? ''); ?></textarea>
      </div>

      <div class="col-12 d-flex justify-content-end pt-2">
        <button
          type="submit"
          class="btn btn-danger w-100 px-4"
          id="btn-update-tour"
        >
          Guardar cambios
        </button>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script type="module" src="/Buke-Tours/assets/js/tours/edit-tour-page.js" defer></script>
</body>
</html>




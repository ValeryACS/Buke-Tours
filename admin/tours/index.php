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

$mysqli = openConnection();

$sql = "SELECT id, sku, title, location, price_usd, discount 
        FROM tour
        ORDER BY created_at DESC";

$result = $mysqli->query($sql);
$tours  = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
}

closeConnection($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Administrar Tours</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
</head>

<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container py-4 admin-container">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0 admin-page-title">Administrar Tours</h1>
    <a href="/Buke-Tours/admin/tours/create.php" class="btn btn-success">
      + Nuevo Tour
    </a>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']); ?></div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']); ?></div>
  <?php endif; ?>

  <?php if (empty($tours)): ?>
    <p>No hay tours registrados.</p>
  <?php else: ?>
    <div class="table-responsive admin-card">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Nombre</th>
            <th>Ubicación</th>
            <th>Precio (USD)</th>
            <th>Desc. (%)</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>

        <tbody>
        <?php foreach ($tours as $tour): ?>
          <tr data-tour-row="<?= (int)$tour['id']; ?>">
            <td><?= (int)$tour['id']; ?></td>
            <td><?= htmlspecialchars($tour['sku']); ?></td>
            <td><?= htmlspecialchars($tour['title']); ?></td>
            <td><?= htmlspecialchars($tour['location']); ?></td>
            <td><?= number_format((float)$tour['price_usd'], 2); ?></td>
            <td><?= (int)$tour['discount']; ?></td>

            <td class="text-end">

              
              <a
                href="/Buke-Tours/admin/tours/edit.php?id=<?= (int)$tour['id']; ?>"
                class="btn btn-sm btn-primary me-1"
              >
                Editar
              </a>

              
              <button
                type="button"
                class="btn btn-sm btn-danger btn-delete-tour"
                data-tour-id="<?= (int)$tour['id']; ?>"
              >
                Eliminar
              </button>

            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>

      </table>
    </div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script type="module" src="/Buke-Tours/assets/js/tours/index-tours-page.js" defer></script>

</body>
</html>

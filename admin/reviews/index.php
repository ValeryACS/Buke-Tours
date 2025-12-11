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

$adminID = isset($_SESSION['admin_id'])? (int)$_SESSION['admin_id']: 0;

if($adminID<= 0){
    header("Location: ../auth/login/");
    exit();
}

include '../../language/lang_' . $_SESSION['lang'] . '.php'; 
include '../../php/helpers/get-country.php';

include '../../php/config/db.php';


$mysqli = openConnection();

$sqlreviews = 'SELECT * FROM feedback';

$reviewsDisponibles= $mysqli->prepare($sqlreviews);
$reviewsDisponibles->execute();
$reviewsResult = $reviewsDisponibles->get_result();

closeConnection($mysqli);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reseñas</title>
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de reseñas y fidelización</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ID del tour</th>
                    <th>Cliente (Nombre)</th>
                    <th>Puntuación</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($reviewsResult) {
                        while ($fila = $reviewsResult->fetch_assoc()):

                        ?>
                        
                <!-- Fila de ejemplo 1 -->
                <tr>
                    <td><?php echo $fila["id"];?></td>
                    <td><?php echo $fila["tour_id"];?></td>
                    <td><?php echo $fila["full_name"];?></td>
                    <td><?php echo $fila["score"];?>⭐</td>
                    <td><?php echo $fila["comment"];?></td>
                    <td><?php echo $fila["status"];?></td>

                    <td>
                        <a href="edit.php?id=<?php echo $fila["id"];?>" class="btn btn-sm btn-warning">Editar</a>
                        <a 
                        href="#" 
                        data-task-id="<?= htmlspecialchars($fila['id']); ?>" 
                        class="btn btn-danger btn-eliminar-resenna">
                        Eliminar
                    </a>              
                    </td>
                </tr>
                    <?php endwhile;
                    }?>
              
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="/Buke-Tours/assets/js/reviews/delete-reviews-page.js" defer></script>


</body>
</html>

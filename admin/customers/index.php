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

$sqlCustomers = 'SELECT * FROM customer';

$customersDisponibles= $mysqli->prepare($sqlCustomers);
$customersDisponibles->execute();
$customersResult = $customersDisponibles->get_result();

closeConnection($mysqli);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cliente</title>
    <?php include '../../php/components/admin/styles/admin-common-styles.php'; ?>
    <link rel="stylesheet" href="/Buke-Tours/assets/css/admin/footer.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>
<div class="container py-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="h4 m-auto titulo px-5">Lista de Clientes</h4>
        <a href="create.php" class="btn btn-primary"> + Agregar Cliente</a>
  </div>


    <div class="table-responsive">
        <table class="table table-striped table-bordered" id = "table-customers">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($customersResult) {
                        while ($fila = $customersResult->fetch_assoc()):

                        ?>
                        
                <!-- Fila de ejemplo 1 -->
                <tr>
                    <td><?php echo $fila["id"];?></td>
                    <td><?php echo $fila["full_name"];?></td>
                    <td><?php echo $fila["phone"];?></td>
                    <td><?php echo $fila["email"];?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $fila["id"];?>" class="btn btn-sm btn-warning">Editar</a>
                        <a 
                        href="#" 
                        data-task-id="<?= htmlspecialchars($fila['id']); ?>" 
                        class="btn btn-danger btn-eliminar-customer">
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

<?php 
      include '../../php/components/admin/styles/footer.php'; ?>
<script defer async>
    $(document).ready(() => {
        $('#table-customers').dataTable({ 
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        })
    })
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="/Buke-Tours/assets/js/customers/delete-profile-page.js" defer></script>


</body>
</html>

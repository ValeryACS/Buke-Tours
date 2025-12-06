<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userID = isset($_SESSION['id'])? (int)$_SESSION['id']: 0;


$mysqli = openConnection();

$sql = 'SELECT id, full_name, email, telephone, country, passport, adults, children, idioma, breakfast, lunch, dinner, transport, travel_insurance, photo_package, home_address, city, province, postal_code, total, subtotal, created_at, updated_at FROM reservation WHERE userId = ? ORDER BY created_at DESC ';

$invoices= $mysqli->prepare($sql);


$invoices->bind_param("i", $userID);
$invoices->execute();
$result = $invoices->get_result();
$rows = [];
closeConnection($mysqli);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buke-Tours - Mis compras</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
    <link rel="stylesheet" href="/Buke-Tours/assets/css/invoices.css" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  </head>
  <body>
    <?php
    include '../config.php';
    ?>
    <main>
      <section id="invoices" class="py-5">
        
        <script defer>
            
        </script>
        <div class="container-lg bg-yellow-light  py-5">
            <div class="row">
                <aside class="col-12 col-lg-2 mb-4">
                    <nav class="card shadow-sm">
                        <div class="card-body"> 
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action active" href="#" >Mis compras</a>
                                <a class="list-group-item list-group-item-action" href="mailto:soporte@buke-tours.cr">Soporte</a>
                            </div>
                        </div>
                    </nav>
                </aside>

                <section class="col-12 col-lg-10">
                    <header class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h1 class="h4 m-auto titulo px-5">Facturas</h1>
                        </div>
                        <div>
                            <button title="Imprimir en PDF" id="print-invoices-to-pdf"  type="button" class="btn btn-danger"><i class="bi bi-file-earmark-pdf mx-1 display-6"></i></button>
                        </div>
                    </header>
                    <?php 
                    
    if($userID === 0){
    ?>
    <p class="d-flex text-center">Necesitas iniciar sesion para ver tus facturas.</p>
    <a href="/Buke-Tours/auth/login/index.php" class="btn btn-success ">Ir al Formulario de Login</a>
    <?php
    }

    else{
        ?>
        <div class="table-responsive shadow-sm" id="invoices_table">
                        <table id="table-invoices-data" class="table table-hover table-dark table-striped table-responsive align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Contacto</th>
                                    <th>Dirección</th>
                                    <th>Detalles</th>
                                    <th>Total</th>
                                    <th>Creada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result) :
                                    foreach ($rows as $filaDesktop):
                                        $extras = [];
                                        if ($filaDesktop['breakfast']) $extras[] = 'Desayuno';
                                        if ($filaDesktop['lunch']) $extras[] = 'Almuerzo';
                                        if ($filaDesktop['dinner']) $extras[] = 'Cena';
                                        if ($filaDesktop['transport']) $extras[] = 'Transporte';
                                        if ($filaDesktop['travel_insurance']) $extras[] = 'Seguro';
                                        if ($filaDesktop['photo_package']) $extras[] = 'Fotos';

                                        $details = "Adultos: {$filaDesktop['adults']}";
                                        $details .= is_null($filaDesktop['children']) ? '' : " | Niños: {$filaDesktop['children']}";
                                        
                                        if (!empty($extras)) $details .= " | Extras: " . implode(', ', $extras);
                                ?>
                                <tr>
                                    <td>
                                        <div><?= htmlspecialchars($filaDesktop['email']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($filaDesktop['telephone']) ?></small>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($filaDesktop['home_address']) ?></div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($filaDesktop['city']) ?>, <?= htmlspecialchars($filaDesktop['province']) ?>, <?= htmlspecialchars($filaDesktop['country']) ?> <?= htmlspecialchars($filaDesktop['postal_code']) ?>
                                        </small>
                                    </td>
                                    <td><?= htmlspecialchars($details) ?></td>
                                    
                                    <td class="fw-bold">$<?= number_format((float)$filaDesktop['total'], 2) ?></td>
                                    <td>
                                        <time datetime="<?= htmlspecialchars($filaDesktop['created_at']) ?>">
                                            <?= htmlspecialchars(date('Y-m-d H:i', strtotime($filaDesktop['created_at']))) ?>
                                        </time>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                                <?php if (empty($invoices)) { ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">No hay facturas disponibles.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <style>
                    /* Hide table on small screens, show on lg and up */
                    @media (max-width: 1024px) {
                        #invoices_table { display: none !important; }
                    }
                    @media (min-width: 1023px) {
                        #mobile-invoices { display: none; }
                    }
                    </style>

                    <div id="mobile-invoices" class="mt-3">
                        <?php
                        $hasResults = false;

                        if ($result) :
                        $hasResults = true;
                                    foreach ($rows as $filaMobile):

                                $extras = [];
                                if (!empty($filaMobile['breakfast'])) $extras[] = 'Desayuno';
                                if (!empty($filaMobile['lunch'])) $extras[] = 'Almuerzo';
                                if (!empty($filaMobile['dinner'])) $extras[] = 'Cena';
                                if (!empty($filaMobile['transport'])) $extras[] = 'Transporte';
                                if (!empty($filaMobile['travel_insurance'])) $extras[] = 'Seguro';
                                if (!empty($filaMobile['photo_package'])) $extras[] = 'Fotos';

                                $details = "Adultos: {$filaMobile['adults']}";
                                $details .= is_null($filaMobile['children']) ? '' : " | Niños: {$filaMobile['children']}";
                                
                                if (!empty($extras)) $details .= " | Extras: " . implode(', ', $extras);
                        ?>
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <hr class="my-2">
                                    <div class="me-2">
                                        <div class="fw-semibold"><?= htmlspecialchars($filaMobile['email']) ?></div>
                                        <p class="text-muted"><?= htmlspecialchars($filaMobile['telephone']) ?></p>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">$<?= number_format((float)$filaMobile['total'], 2) ?></div>
                                        <p class="text-muted">
                                            <time datetime="<?= htmlspecialchars($filaMobile['created_at']) ?>">
                                                <?= htmlspecialchars(date('Y-m-d H:i', strtotime($filaMobile['created_at']))) ?>
                                            </time>
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="mb-2">
                                    <h4 class="subtitle-invoice">Dirección</h4>
                                    <p><?= htmlspecialchars($filaMobile['home_address']) ?></p>
                                    <p class="text-muted">
                                        <?= htmlspecialchars($filaMobile['city']) ?>, <?= htmlspecialchars($filaMobile['province']) ?>, <?= htmlspecialchars($filaMobile['country']) ?> <?= htmlspecialchars($filaMobile['postal_code']) ?>
                                    </p>
                                </div>

                                <div>
                                    <h4 class="subtitle-invoice">Detalles</h4>
                                    <div><?= htmlspecialchars($details) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php if (!$hasResults) { ?>
                            <div class="text-center text-muted py-5">No hay facturas disponibles.</div>
                        <?php } ?>
                        <?php
                        endif; ?>
                    </div>
        <?php
    }
                    ?>
                    
                </section>
            </div>
        </div>
      </section>
    </main>
    <script type="module" src="/Buke-Tours/assets/js/invoices-page.js" defer></script>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
    ?>
    <script defer async>
        $(document).ready(() => {
            $('#table-invoices-data').dataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            })
        })
    </script>
  </body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/config/db.php';
include '../php/helpers/get-tours-reservation-dates.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include '../language/lang_' . $_SESSION['lang'] . '.php';

$html_lang = $_SESSION['lang'];

$userID = isset($_SESSION['id'])? (int)$_SESSION['id']: 0;

if($userID<= 0){
    header("Location: ../auth/login/");
    exit();
}


$mysqli = openConnection();

$sql = 'SELECT reservation_tour.*, tour.*, reservation.* FROM reservation_tour INNER JOIN tour ON reservation_tour.tour_id = tour.id INNER JOIN reservation ON reservation_tour.reservation_id = reservation.id WHERE reservation.userId = ? GROUP BY tour.id;';

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
<html lang="<?php echo htmlspecialchars($html_lang, ENT_QUOTES, 'UTF-8'); ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $lang['invoices_page_title']; ?></title>
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
        <div class="container-lg bg-yellow-light  py-5">
            <div class="row">
                <aside class="col-12 col-lg-2 mb-4">
                    <nav class="card shadow-sm">
                        <div class="card-body"> 
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action active" href="#" ><?php echo $lang['mis_compras']; ?></a>
                                <a class="list-group-item list-group-item-action" href="mailto:soporte@buke-tours.cr"><?php echo $lang['soporte']; ?></a>
                            </div>
                        </div>
                    </nav>
                </aside>

                <section class="col-12 col-lg-10">
                    <header class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h1 class="h4 m-auto titulo px-5"><?php echo $lang['facturas']; ?></h1>
                        </div>
                        <div>
                            <button title="<?php echo $lang['btn_print_pdf']; ?>" id="print-invoices-to-pdf"  type="button" class="btn btn-danger" aria-label="<?php echo $lang['btn_print_pdf']; ?>"><i class="bi bi-file-earmark-pdf mx-1 display-6"></i></button>
                        </div>
                    </header>
                    <?php 
                    
    if($userID === 0){
    ?>
        <p class="d-flex text-center w-100"><?php echo $lang['login_requerido_facturas']; ?></p>
        <a href="/Buke-Tours/auth/login/index.php" class="btn btn-success "><?php echo $lang['ir_al_login']; ?></a>
    <?php
    }
    else{
        ?>
        <?php if (!empty($rows)) : ?>
        <div class="table-responsive shadow-sm" id="invoices_table">
            <table id="table-invoices-data" class="table-invoices-data table table-hover table-dark table-striped align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th><?php echo $lang['tabla_contacto']; ?></th>
                        <th><?php echo $lang['tabla_detalles']; ?></th>
                        <th><?php echo $lang['tabla_total']; ?></th>
                        <th><?php echo $lang['tabla_ingresos']; ?></th>
                        <th><?php echo $lang['tabla_fecha']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $filaDesktop):
                        $extras = [];
                        if ($filaDesktop['breakfast']) $extras[] = $lang['extra_desayuno'];
                        if ($filaDesktop['lunch']) $extras[] = $lang['extra_almuerzo'];
                        if ($filaDesktop['dinner']) $extras[] = $lang['extra_cena'];
                        if ($filaDesktop['transport']) $extras[] = $lang['extra_transporte'];
                        if ($filaDesktop['travel_insurance']) $extras[] = $lang['extra_seguro'];
                        if ($filaDesktop['photo_package']) $extras[] = $lang['extra_fotos'];

                        $details = "{$lang['label_adultos']}: {$filaDesktop['adults']}";
                        $details .= is_null($filaDesktop['children']) ? '' : " | {$lang['label_ninos']}: {$filaDesktop['children']}";
                        if (!empty($extras)) {
                            $details .= " | {$lang['label_extras']}: " . implode(', ', $extras);
                        }
                    ?>
                    <tr>
                        <td>
                            <h4 class="subtitulo mb-2"><?php echo $lang['factura_numero']; ?><?php echo $filaDesktop['id']; ?></h4>
                            <p class="fw-semibold mb-1"><?php echo $lang['tour_label']; ?> <?= htmlspecialchars($filaDesktop['title'] ?? '') ?></p>
                            <p class="mb-0"><?= htmlspecialchars($filaDesktop['email']) ?></p>
                            <small><?= htmlspecialchars($filaDesktop['telephone']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($details) ?></td>
                        <td class="fw-bold">$<?= number_format((float)$filaDesktop['total'], 2) ?></td>
                        <td><?= getTourReservationDates($filaDesktop['id'] , $filaDesktop['tour_id']);?></td>
                        <td>
                            <time datetime="<?= htmlspecialchars($filaDesktop['created_at']) ?>">
                                <?= htmlspecialchars(date('Y-m-d H:i', strtotime($filaDesktop['created_at']))) ?>
                            </time>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else : ?>
            <div class="alert alert-info"><?php echo $lang['no_hay_facturas']; ?></div>
            <a href="/Buke-Tours/tours/" class="btn btn-success" title="<?php echo $lang['cta_comprar_tours']; ?>"><?php echo $lang['cta_comprar_tours']; ?></a>
        <?php endif; ?>

                    <div id="mobile-invoices" class="mt-3">
                        <?php
                        $hasResults = false;
                        (int)$counter = 0;
                        if ($result) :
                        $hasResults = true;
                            foreach ($rows as $filaMobile):
                                $counter++;
                                $extras = [];
                                if (!empty($filaMobile['breakfast'])) $extras[] = $lang['extra_desayuno'];
                                if (!empty($filaMobile['lunch'])) $extras[] = $lang['extra_almuerzo'];
                                if (!empty($filaMobile['dinner'])) $extras[] = $lang['extra_cena'];
                                if (!empty($filaMobile['transport'])) $extras[] = $lang['extra_transporte'];
                                if (!empty($filaMobile['travel_insurance'])) $extras[] = $lang['extra_seguro'];
                                if (!empty($filaMobile['photo_package'])) $extras[] = $lang['extra_fotos'];

                                $details = "{$lang['label_adultos']}: {$filaMobile['adults']}";
                                $details .= is_null($filaMobile['children']) ? '' : " | {$lang['label_ninos']}: {$filaMobile['children']}";
                                
                                if (!empty($extras)) $details .= " | {$lang['label_extras']}: " . implode(', ', $extras);
                        ?>
                        <div class="card shadow-sm mb-3">
                            <h3 class="subtitulo"><?php echo $lang['factura_numero']; ?><?php echo $counter;?></h3>
                            <div class="card-body">
                                <h4 class="subtitle-invoice w-100"><?php echo $lang['tabla_contacto']; ?></h4>
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
                                    <h4 class="subtitle-invoice"><?php echo $lang['direccion_label']; ?></h4>
                                    <p><?= htmlspecialchars($filaMobile['home_address']) ?></p>
                                    <p class="text-muted">
                                        <?= htmlspecialchars($filaMobile['city']) ?>, <?= htmlspecialchars($filaMobile['province']) ?>, <?= htmlspecialchars($filaMobile['country']) ?> <?= htmlspecialchars($filaMobile['postal_code']) ?>
                                    </p>
                                </div>

                                <div>
                                    <h4 class="subtitle-invoice"><?php echo $lang['tabla_detalles']; ?></h4>
                                    <div><?= htmlspecialchars($details) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php if (!$hasResults) { ?>
                            <div class="text-center text-muted py-5"><?php echo $lang['no_hay_facturas']; ?></div>
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
    
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/invoices-scripts.php';
    ?>
    <script defer async>
        $(document).ready(() => {
            $('.table-invoices-data').dataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            })
        })
    </script>
  </body>
</html>

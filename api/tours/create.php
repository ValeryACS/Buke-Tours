<?php


header('Content-Type: application/json; charset=UTF-8');


ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include '../../php/config/db.php';

$response = [
    'success'   => false,
    'message'   => '',
    'errors'    => [],
    'createdId' => null,
];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = 'Método no permitido. Usa POST.';
        $response['errors'][] = 'Método no permitido. Usa POST.';
        echo json_encode($response);
        exit;
    }

    
    $sku           = trim($_POST['sku']           ?? '');
    $title         = trim($_POST['title']         ?? '');
    $location      = trim($_POST['location']      ?? '');
    $description   = trim($_POST['description']   ?? '');
    $price_usd     = trim($_POST['price_usd']     ?? '');
    $rating        = trim($_POST['rating']        ?? '');
    $duration      = trim($_POST['duration_hours'] ?? '');
    $adults_limit  = trim($_POST['adults_limit']  ?? '');
    $children_limit= trim($_POST['children_limit']?? '');
    $discount      = trim($_POST['discount']      ?? '');
    $img           = trim($_POST['img']           ?? '');
    $cupon_code    = trim($_POST['cupon_code']    ?? '');
    $iframe        = trim($_POST['iframe']        ?? '');

    
    if ($sku === '')         { $response['errors'][] = 'El SKU es obligatorio.'; }
    if ($title === '')       { $response['errors'][] = 'El nombre del tour es obligatorio.'; }
    if ($location === '')    { $response['errors'][] = 'La ubicación es obligatoria.'; }
    if ($description === '') { $response['errors'][] = 'La descripción es obligatoria.'; }
    if ($price_usd === '' || !is_numeric($price_usd)) {
        $response['errors'][] = 'El precio debe ser numérico.';
    }
    if ($adults_limit === '' || !ctype_digit($adults_limit)) {
        $response['errors'][] = 'El límite de adultos debe ser un número entero.';
    }
    if ($children_limit === '' || !ctype_digit($children_limit)) {
        $response['errors'][] = 'El límite de niños debe ser un número entero.';
    }
    if ($discount === '' || !ctype_digit($discount)) {
        $response['errors'][] = 'El descuento debe ser un número entero.';
    }

    if (!empty($response['errors'])) {
        $response['message'] = 'Errores de validación.';
        echo json_encode($response);
        exit;
    }

    
    $price_usd      = (float)$price_usd;
    $rating         = $rating === '' ? null : (float)$rating;
    $duration       = $duration === '' ? null : (float)$duration;
    $adults_limit   = (int)$adults_limit;
    $children_limit = (int)$children_limit;
    $discount       = (int)$discount;

    
    $mysqli = openConnection();

    $sql = "INSERT INTO tour (
                sku, title, location, description,
                price_usd, rating, duration_hours,
                adults_limit, children_limit, discount,
                img, cupon_code, iframe
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error al preparar el statement: ' . $mysqli->error);
    }

    
    $stmt->bind_param(
        "ssssdddiiisss",
        $sku,
        $title,
        $location,
        $description,
        $price_usd,
        $rating,
        $duration,
        $adults_limit,
        $children_limit,
        $discount,
        $img,
        $cupon_code,
        $iframe
    );

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar el INSERT: ' . $stmt->error);
    }

    $createdId = $stmt->insert_id ?: $mysqli->insert_id;

    $stmt->close();
    closeConnection($mysqli);

    $response['success']   = true;
    $response['message']   = 'Tour creado correctamente.';
    $response['createdId'] = $createdId;

    echo json_encode($response);
    exit;

} catch (Throwable $e) {
    
    $response['success'] = false;
    $response['message'] = 'Error interno al crear el tour.';
    $response['errors'][] = $e->getMessage();

    echo json_encode($response);
    exit;
}

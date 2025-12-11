<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include __DIR__ . "/../../php/config/db.php";

$mysqli  = null;
$tours   = [];
$success = false;
$error   = null;

try {
    
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        throw new Exception('Método no permitido. Usa GET.');
    }

    $mysqli = openConnection();
    if (!$mysqli) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }

    
    $tomorrowTimestamp      = strtotime('+1 day');
    $afterTomorrowTimestamp = strtotime('+2 day');

    $tomorrowDay      = date('Y-m-d', $tomorrowTimestamp);
    $dayAfterTomorrow = date('Y-m-d', $afterTomorrowTimestamp);

    
    $checkInParam  = isset($_GET['check_in_date']) ? $_GET['check_in_date'] : null;
    $checkOutParam = isset($_GET['check_out_date']) ? $_GET['check_out_date'] : null;

   
    $checkInDate = $tomorrowDay;
    if ($checkInParam) {
        $checkInDateTime = DateTime::createFromFormat('Y-m-d', $checkInParam);
        if ($checkInDateTime && $checkInDateTime->format('Y-m-d') === $checkInParam) {
            $checkInDate = $checkInParam;
        }
    }

    $checkOutDate = $dayAfterTomorrow;
    if ($checkOutParam) {
        $checkOutDateTime = DateTime::createFromFormat('Y-m-d', $checkOutParam);
        if ($checkOutDateTime && $checkOutDateTime->format('Y-m-d') === $checkOutParam) {
            $checkOutDate = $checkOutParam;
        }
    }

    
    if (strtotime($checkOutDate) < strtotime($checkInDate)) {
        $checkOutDate = date('Y-m-d', strtotime($checkInDate . ' +1 day'));
    }

    
    $sqlTours = '
        SELECT
            t.id,
            t.sku,
            t.title,
            t.location,
            t.price_usd,
            t.cupon_code,
            t.cupon_discount,
            t.rating,
            t.duration_hours,
            t.discount,
            t.img,
            t.description,
            t.iframe,
            (t.adults_limit - IFNULL(r.reserved_adults, 0))   AS adults_available,
            (t.children_limit - IFNULL(r.reserved_children, 0)) AS children_available
        FROM tour t
        LEFT JOIN (
            SELECT
                tour_id,
                SUM(adults)   AS reserved_adults,
                SUM(children) AS reserved_children
            FROM reservation_tour
            WHERE check_in_date <= ?
              AND check_out_date >= ?
            GROUP BY tour_id
        ) AS r ON r.tour_id = t.id
        WHERE (t.adults_limit - IFNULL(r.reserved_adults, 0)) > 0
          AND (t.children_limit - IFNULL(r.reserved_children, 0)) > 0
        ORDER BY t.created_at DESC
    ';

    $stmt = $mysqli->prepare($sqlTours);
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . $mysqli->error);
    }

    $stmt->bind_param("ss", $checkInDate, $checkOutDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception('Error al ejecutar la consulta: ' . $mysqli->error);
    }

    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }

    $stmt->close();
    $success = true;

} catch (Exception $e) {
    $error = $e->getMessage();
    http_response_code(500);
} finally {
    if (isset($result) && $result instanceof mysqli_result) {
        $result->free();
    }
    if ($mysqli) {
        closeConnection($mysqli);
    }
}

echo json_encode(
    [
        "success" => $success,
        "count"   => count($tours),
        "error"   => $success ? null : $error,
        "data"    => $tours,
    ],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
);

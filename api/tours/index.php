<?php
/**
 * API GET - Usado para retornar todos los tours almacenados en la base de datos
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../php/config/db.php");

if($_SERVER["REQUEST_METHOD"] === "GET"){
    $mysqli = openConnection();
    // Obtiene la fecha actual y le suma un día
    $tomorrowTimestamp = strtotime('+1 day');
    // Obtiene la fecha actual y le suma 2 días
    $afterTomorrowTimestamp = strtotime('+2 day');

    // Formatea el timestamp al formato 'yyyy-mm-dd'
    $tomorrowDay =  date('Y-m-d', $tomorrowTimestamp);
    $dayAfterTomorrow =  date('Y-m-d', $afterTomorrowTimestamp);

    $checkInParam = isset($_GET['check_in_date']) ? $_GET['check_in_date'] : null;
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
            (t.adults_limit - IFNULL(r.reserved_adults, 0)) AS adults_available,
            (t.children_limit - IFNULL(r.reserved_children, 0)) AS children_available
        FROM tour t
        LEFT JOIN (
            SELECT
                tour_id,
                SUM(adults) AS reserved_adults,
                SUM(children) AS reserved_children
            FROM reservation_tour
            WHERE check_in_date <= ?
              AND check_out_date >= ?
            GROUP BY tour_id
        ) AS r ON r.tour_id = t.id
        WHERE (t.adults_limit - IFNULL(r.reserved_adults, 0)) > 0
          AND (t.children_limit - IFNULL(r.reserved_children, 0)) > 0
    ';
    
    // Ejecuta la consulta y almacena los resultados en un arreglo
    $toursStmt = $mysqli->prepare($sqlTours);
    $toursStmt->bind_param("ss", $checkInDate, $checkOutDate);
    $toursStmt->execute();
    $toursResult = $toursStmt->get_result();

    if(!$toursResult){
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error"   => "Error al ejecutar la consulta: " . $mysqli->error
        ], JSON_UNESCAPED_UNICODE);
        closeConnection($mysqli);
        exit;
    }
    $tours = [];
    while ($row = $toursResult->fetch_assoc()) {
        $tours[] = $row;
    }
    $toursStmt->close();
    
    closeConnection($mysqli);

    /**
     * Retornando los datos guardados en BD en formato JSON
     */
    echo json_encode(
        [
            "success" => true,
            "count"   => count($tours),
            "data"    => $tours
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
    );
}

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

    $resultado = $mysqli->query('SELECT id,sku,title,location,price_usd,cupon_code, cupon_discount,rating,duration_hours, discount,img,description,iframe from tour');
    if ($resultado === false) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error"   => "Error al ejecutar la consulta: " . $mysqli->error
        ], JSON_UNESCAPED_UNICODE);
        closeConnection($mysqli);
        exit;
    }

    // Inicializando el arreglo con los tours
    $tours = [];
    while ($row = $resultado->fetch_assoc()) {
        $tours[] = $row;
    }

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
<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../php/config/db.php";

$response = [
    "success"   => false,
    "message"   => "No se pudo eliminar el tour.",
    "errors"    => [],
    "deletedId" => null,
];


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response["message"] = "Método no permitido. Usa POST.";
    $response["errors"][] = "Método no permitido. Usa POST.";
    echo json_encode($response);
    exit;
}


$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
if ($id <= 0) {
    $response["message"] = "ID de tour inválido.";
    $response["errors"][] = "ID de tour inválido.";
    echo json_encode($response);
    exit;
}


$mysqli = openConnection();
if (!$mysqli || $mysqli->connect_errno) {
    $response["message"] = "No se pudo conectar a la base de datos.";
    $response["errors"][] = "Error de conexión: " . $mysqli->connect_error;
    echo json_encode($response);
    exit;
}


$sql = "DELETE FROM tour WHERE id = ? LIMIT 1";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    $response["message"] = "Error al preparar la consulta.";
    $response["errors"][] = "Prepare failed: " . $mysqli->error;
    echo json_encode($response);
    closeConnection($mysqli);
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    
    $response["message"] = "Error al ejecutar la eliminación.";
    $response["errors"][] = "Execute failed: " . $stmt->error;
    echo json_encode($response);
    $stmt->close();
    closeConnection($mysqli);
    exit;
}

if ($stmt->affected_rows <= 0) {
    $response["message"] = "No se encontró el tour o no se pudo eliminar.";
    $response["errors"][] = "No rows affected.";
    echo json_encode($response);
    $stmt->close();
    closeConnection($mysqli);
    exit;
}


$response["success"]   = true;
$response["message"]   = "Tour eliminado correctamente.";
$response["deletedId"] = $id;

$stmt->close();
closeConnection($mysqli);

echo json_encode($response);


<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include __DIR__ . "/../../php/config/db.php";

$mysqli = null;
$errors = [];
$data = [];
$success = false;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        throw new Exception('Método no permitido. Usa GET.');
    }

    $mysqli = openConnection();

    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

    if ($q !== '') {
        $sql = "SELECT 
                    id, sku, title, location, price_usd, cupon_code, cupon_discount,
                    rating, duration_hours, discount, img, description, iframe,
                    adults_limit, children_limit
                FROM tour
                WHERE title LIKE ? 
                   OR location LIKE ? 
                   OR description LIKE ?
                ORDER BY created_at DESC";

        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error al preparar la consulta: ' . $mysqli->error);
        }

        $like = '%' . $q . '%';
        $stmt->bind_param('sss', $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT 
                    id, sku, title, location, price_usd, cupon_code, cupon_discount,
                    rating, duration_hours, discount, img, description, iframe,
                    adults_limit, children_limit
                FROM tour
                ORDER BY created_at DESC";

        $result = $mysqli->query($sql);
        if (!$result) {
            throw new Exception('Error al ejecutar la consulta: ' . $mysqli->error);
        }
    }

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $success = true;

} catch (Exception $e) {
    $errors[] = $e->getMessage();
} finally {
    if (isset($result) && $result instanceof mysqli_result) {
        $result->free();
    }
    if ($mysqli) {
        closeConnection($mysqli);
    }
}

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Tours obtenidos correctamente.' : ($errors[0] ?? 'Error al obtener tours.'),
    'error'   => $success ? null : $errors,
    'data'    => $data
], JSON_UNESCAPED_UNICODE);

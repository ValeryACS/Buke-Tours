<?php
// /Buke-Tours/api/tours/update.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include __DIR__ . "/../../php/config/db.php";

$mysqli  = null;
$errors  = [];
$success = false;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método no permitido. Usa POST.');
    }

    $fromAdmin = isset($_POST['from_admin']) && $_POST['from_admin'] === '1';

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $title       = trim($_POST['nombre']      ?? '');
    $description = trim($_POST['descripcion'] ?? '');
    $location    = trim($_POST['ubicacion']   ?? '');
    $img         = trim($_POST['img']         ?? '');

    $price_usd      = isset($_POST['price_usd'])      ? (float)$_POST['price_usd']      : 0;
    $rating         = isset($_POST['rating'])         ? (float)$_POST['rating']         : 0;
    $duration_hours = isset($_POST['duration_hours']) ? (float)$_POST['duration_hours'] : 0;
    $adults_limit   = isset($_POST['adults_limit'])   ? (int)$_POST['adults_limit']     : 0;
    $children_limit = isset($_POST['children_limit']) ? (int)$_POST['children_limit']   : 0;
    $discount       = isset($_POST['discount'])       ? (int)$_POST['discount']         : 0;

    $cupon_code = trim($_POST['cupon_code'] ?? '');
    $cupon_code = ($cupon_code === '') ? null : $cupon_code;

    $iframe = trim($_POST['iframe'] ?? '');
    $iframe = ($iframe === '') ? null : $iframe;

    $cupon_discount = 0;

    if ($id <= 0) {
        $errors[] = 'ID de tour inválido.';
    }

    $missing_required =
        $title === '' ||
        $description === '' ||
        $location === '' ||
        $img === '';

    $invalid_values =
        $price_usd <= 0 ||
        $rating < 1 || $rating > 5 ||
        $duration_hours <= 0 ||
        $adults_limit <= 0 ||
        $discount < 0 || $discount > 100;

    if ($missing_required) {
        $errors[] = 'Faltan campos obligatorios (nombre, descripción, ubicación, img).';
    }
    if ($invalid_values) {
        $errors[] = 'Valores inválidos en precio, rating, duración, límite de adultos o descuento.';
    }

    if (!empty($errors)) {
        throw new Exception($errors[0]);
    }

    $mysqli = openConnection();

    $sql = "UPDATE tour
            SET title = ?,
                location = ?,
                price_usd = ?,
                cupon_code = ?,
                cupon_discount = ?,
                rating = ?,
                duration_hours = ?,
                discount = ?,
                img = ?,
                description = ?,
                iframe = ?,
                adults_limit = ?,
                children_limit = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error al preparar UPDATE: ' . $mysqli->error);
    }

    // Tipos: s s d s i d d i s s s i i i
    $types = "ssdsiddisssiii";

    $stmt->bind_param(
        $types,
        $title,
        $location,
        $price_usd,
        $cupon_code,
        $cupon_discount,
        $rating,
        $duration_hours,
        $discount,
        $img,
        $description,
        $iframe,
        $adults_limit,
        $children_limit,
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar UPDATE: ' . $stmt->error);
    }

    $success = true;
    $stmt->close();

} catch (Exception $e) {
    $errors[] = $e->getMessage();
} finally {
    if ($mysqli) {
        closeConnection($mysqli);
    }
}

$isFromAdmin = isset($fromAdmin) && $fromAdmin;

if ($isFromAdmin) {
    if ($success) {
        header("Location: /Buke-Tours/admin/tours/index.php?success=" . urlencode('Tour actualizado correctamente.'));
        exit;
    } else {
        $msg = $errors[0] ?? 'Error al actualizar tour.';
        // Redirige de nuevo a edit con el id
        $idParam = isset($id) ? (int)$id : 0;
        header("Location: /Buke-Tours/admin/tours/edit.php?id={$idParam}&error=" . urlencode($msg));
        exit;
    }
}

// JSON para Postman / JS
echo json_encode([
    'success' => $success,
    'message' => $success ? 'Tour actualizado correctamente.' : ($errors[0] ?? 'Error al actualizar tour.'),
    'error'   => $success ? null : $errors
], JSON_UNESCAPED_UNICODE);

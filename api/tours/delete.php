<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

include __DIR__ . "/../../php/config/db.php";

$mysqli  = null;
$errors  = [];
$success = false;
$message = '';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método no permitido. Usa POST.');
    }

    $fromAdmin = isset($_POST['from_admin']) && $_POST['from_admin'] === '1';

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id <= 0) {
        $errors[] = 'ID de tour inválido.';
        throw new Exception($errors[0]);
    }

    $mysqli = openConnection();

    $stmt = $mysqli->prepare("DELETE FROM tour WHERE id = ? LIMIT 1");
    if (!$stmt) {
        throw new Exception('Error al preparar DELETE: ' . $mysqli->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar DELETE: ' . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        $message = 'No se encontró el tour a eliminar.';
    } else {
        $message = 'Tour eliminado correctamente.';
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
        header("Location: /Buke-Tours/admin/tours/index.php?success=" . urlencode($message));
        exit;
    } else {
        $msg = $errors[0] ?? 'Error al eliminar tour.';
        header("Location: /Buke-Tours/admin/tours/index.php?error=" . urlencode($msg));
        exit;
    }
}


echo json_encode([
    'success' => $success,
    'message' => $success ? $message : ($errors[0] ?? 'Error al eliminar tour.'),
    'error'   => $success ? null : $errors
], JSON_UNESCAPED_UNICODE);

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = [];
$success = false;
$language = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'es';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lang'])) {
        $requestedLang = $_POST['lang'];
        if (in_array($requestedLang, ['es', 'en'], true)) {
            $_SESSION['lang'] = $requestedLang;
            $language = $requestedLang;
            $success = true;
        } else {
            $errors[] = 'Idioma no soportado.';
        }
    } else {
        $errors[] = 'Parámetro lang requerido.';
    }
} else {
    $errors[] = 'Método HTTP no permitido.';
}

$data = [
    'lang'    => $language,
    'success' => $success,
    'message' => $success ? 'Idioma actualizado correctamente.' : ($errors[0] ?? 'Error al cambiar de idioma.'),
    'error'   => $success ? null : $errors,
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
?>

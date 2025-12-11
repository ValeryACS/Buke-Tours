<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: /Buke-Tours/admin/auth/login-admin.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID no proporcionado");
}

$tourId = intval($_GET['id']);


$apiUrl = "http://localhost/Buke-Tours/api/tours/delete.php?id=" . $tourId;

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data["success"]) {
    header("Location: /Buke-Tours/admin/tours/index.php?deleted=1");
    exit;
} else {
    echo "Error al eliminar: " . ($data["message"] ?? "Error desconocido");
}


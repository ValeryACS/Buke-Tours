<?php

//Endpoint para actualizar estado de la reseña

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminID = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;

if ($adminID <= 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'ID del usuario es obligatorio para la edición.']);
    exit();
}

include("../../../php/config/db.php"); 

$mysqli = openConnection();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $reviewId = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;
    $status = isset($_POST['status']) ? trim((string)$_POST['status']) : '';

    if ($reviewId <= 0) {
        $errors[] = 'ID de la reseña (review_id) es obligatorio y debe ser un número válido.';
    }
    
    $valid_statuses = ['Aprobada', 'Denegada', 'Pendiente'];
    if (!in_array($status, $valid_statuses)) {
        $errors[] = 'Estado de reseña no válido. Debe ser "Aprobada", "Denegada" o "Pendiente".';
    }
    
    if (empty($errors)) {
        $stmtCheck = $mysqli->prepare("SELECT id FROM feedback WHERE id = ? LIMIT 1");
        $stmtCheck->bind_param("i", $reviewId);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        
        if ($resCheck->num_rows === 0) {
            $errors[] = 'La reseña con el ID proporcionado no existe en la base de datos.';
        }
        $stmtCheck->close();

        if (empty($errors)) {
            $mysqli->begin_transaction();

            try {
                $sqlFeedback = "UPDATE `feedback` SET 
                    `status` = ?
                    WHERE `id` = ?";
                
                $stmt = $mysqli->prepare($sqlFeedback);
                
                if (!$stmt) {
                    throw new Exception("Error al preparar la consulta de actualización: " . $mysqli->error);
                }
                
                $stmt->bind_param(
                    'si', 
                    $status, 
                    $reviewId
                );
                
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error ?: "Error al ejecutar la actualización del estado de la reseña.");
                }
                
                $stmt->close();
                $mysqli->commit();

            } catch (Exception $e) {
                $mysqli->rollback();
                $errors[] = 'Error de Transacción: ' . ($e->getMessage() ?? 'Error Inesperado en el Servidor.');
            }
        }
    }

    $data = [
        'success' => empty($errors),
        'message' => empty($errors) ? 'El estado de la reseña ha sido actualizado exitosamente' . $adminID . '.' : $errors[0] ?? 'Error al procesar la solicitud',
        'errors'  => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    
} else {
    // Si no es POST
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Solo se acepta POST.']);
}

closeConnection($mysqli);
?>
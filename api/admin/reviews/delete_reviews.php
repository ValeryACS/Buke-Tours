<?php
/**
 * Endpoint para eliminar un usuario. 
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../php/config/db.php");

$mysqli = openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM feedback WHERE id = ?');

        if ($stmt) {
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Reseña eliminada exitosamente.'];
            } else {
                $response = ['success' => false, 'message' => 'Error al eliminar reseña: ' . $stmt->error];
            }
            $stmt->close();
        } else {
            $response = ['success' => false, 'message' => 'Error al preparar la consulta: ' . $mysqli->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'ID de reseña no válido.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Método no permitido o ID faltante.'];
}

closeConnection($mysqli);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit(); 
?>
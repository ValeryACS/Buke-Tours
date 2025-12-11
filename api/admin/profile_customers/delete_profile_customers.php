<?php
/**
 * Endpoint para eliminar un usuario. Espera el ID vía POST.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Asegúrate de que las rutas a tus archivos de conexión son correctas
include("../../../php/config/db.php");

$mysqli = openConnection();

// 1. Verificar si se recibió el ID por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM customer WHERE id = ?');

        if ($stmt) {
            $stmt->bind_param("i", $id);
            
            // 2. Ejecutar la eliminación
            if ($stmt->execute()) {
                // Éxito: Enviar respuesta JSON
                $response = ['success' => true, 'message' => 'Usuario eliminado exitosamente.'];
            } else {
                // Error en la ejecución SQL
                $response = ['success' => false, 'message' => 'Error al eliminar usuario: ' . $stmt->error];
            }
            $stmt->close();
        } else {
            // Error en la preparación SQL
            $response = ['success' => false, 'message' => 'Error al preparar la consulta: ' . $mysqli->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'ID de usuario no válido.'];
    }
} else {
    // Si no es POST o falta el ID
    $response = ['success' => false, 'message' => 'Método no permitido o ID faltante.'];
}

closeConnection($mysqli);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit(); 
?>
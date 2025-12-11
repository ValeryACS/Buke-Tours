<?php
/**
 * API endpoint para los mensajes recibidos atraves del Formulario de Contacto.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../php/helpers/send-email.php'

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim((string)$_POST['telefono']) : '';
    $mensaje = isset($_POST['mensaje']) ? trim((string)$_POST['mensaje']) : '';
    $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $asunto = isset($_POST['asunto']) ? trim((string)$_POST['asunto']) : '';
    
    if ($nombre === '') {
        $errors[] = 'Nombre es obligatorio';
    }

    if ($telefono === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $telefono)) {
        $errors[] = 'Telefono invalido';
    }

    if ($mensaje === '') {
        $errors[] = 'El mensaje es obligatorio';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email es obligatorio y debe ser válido';
    }

    if ($asunto === '') {
        $errors[] = 'El asunto es obligatorio.';
    }
    if (empty($errors)) {
        $success = true;
        $body = "<h1>Buke Tours</h2>";
        $body.="
        <p style='width:100%;'>Nombre: </p><p>". $nombre ."</p>
        <p style='width:100%;'>Telefono: </p><p>". $telefono ."</p>
        <p style='width:100%;'>Mensaje: </p><p>". $mensaje ."</p>
        ";
        try {
            sendEmail($email,$asunto,$body);
        } catch (\Throwable $th) {
            $mysqli->rollback();
            $errors[] = $e->getMessage() ?? 'Error Inesperado en el Servidor al enviar formulario.';
            $success = false;
        }
    }
    $data = [
        'success' => empty($errors),
        'message' => empty($errors) ? 'El Formulaio ha sido enviado exitosamente' : $errors[0] ?? 'Error al crear Usuario',
        'error'   => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>
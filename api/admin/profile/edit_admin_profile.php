<?php
/**
 * endpoint para actualizar un administrador
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../php/config/db.php"); 

$mysqli = openConnection();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = isset($_POST['admin_id']) ? (int)$_POST['admin_id'] : 0;

    if ($adminId <= 0) {
        $errors[] = 'ID del administrador es obligatorio para la edición.';
    }

    $nombre = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim((string)$_POST['telefono']) : '';
    $pais = isset($_POST['pais']) ? trim((string)$_POST['pais']) : '';
    $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $password = isset($_POST['password']) ? trim((string)$_POST['password']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? trim((string)$_POST['confirmPassword']) : '';
    $direccion = isset($_POST['direccion']) ? trim((string)$_POST['direccion']) : '';
    $ciudad = isset($_POST['ciudad']) ? trim((string)$_POST['ciudad']) : '';
    $provincia = isset($_POST['provincia']) ? trim((string)$_POST['provincia']) : '';
    $codigoPostal = isset($_POST['codigoPostal']) ? trim((string)$_POST['codigoPostal']) : '';
    $genero = isset($_POST['genero']) ? trim((string)$_POST['genero']) : '';
    $idioma = isset($_POST['idioma']) ? trim((string)$_POST['idioma']) : '';
    $fechaDeNacimiento = isset($_POST['fechaDeNacimiento']) ? trim((string)$_POST['fechaDeNacimiento']) : '';
    $pasaporteOdocumento = isset($_POST['pasaporteOdocumento']) ? trim((string)$_POST['pasaporteOdocumento']) : '';

    // VALIDACIONES

    if ($nombre === '') { $errors[] = 'Nombre es obligatorio'; }
    if ($telefono === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $telefono)) { $errors[] = 'Telefono invalido'; }
    if ($pais === '') { $errors[] = 'Pais es obligatorio'; }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email es obligatorio y debe ser válido'; }
    if ($direccion === '') { $errors[] = 'Direccion es obligatoria'; }
    if ($ciudad === '') { $errors[] = 'Ciudad es obligatoria'; }
    if ($provincia === '') { $errors[] = 'Provincia es obligatoria'; }
    if ($codigoPostal === '' || !preg_match('/^[A-Za-z0-9\-]{3,10}$/', $codigoPostal)) { $errors[] = 'Codigo postal invalido'; }
    
    if ($genero === '' || ($genero !== 'Masculino' && $genero !== 'Femenino')) { $errors[] = 'Genero invalido'; }
    
    if ($idioma === '' || $idioma === 'no-seleccionado') { $errors[] = 'Idioma es obligatorio'; }
    if ($pasaporteOdocumento === '') { $errors[] = 'Documento es obligatorio'; }

    $isValidDate = function ($dateStr) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) { return false; }
        [$y, $m, $d] = explode('-', $dateStr);
        return checkdate((int)$m, (int)$d, (int)$y);
    };
    if ($fechaDeNacimiento !== '' && !$isValidDate($fechaDeNacimiento)) {
        $errors[] = 'Fecha de nacimiento invalida (formato YYYY-MM-DD)';
    }

    $updatePassword = false;
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = 'La nueva Contraseña debe tener al menos 6 caracteres';
        }
        if ($confirmPassword !== $password) {
            $errors[] = 'Las nuevas contraseñas no coinciden';
        }
        $updatePassword = true; 
    } elseif (!empty($confirmPassword)) {
        $errors[] = 'El campo Contraseña está vacío, pero la Confirmación no.';
    }

    if (empty($errors) && $adminId > 0) {
        $stmtCheck = $mysqli->prepare("SELECT id FROM admins WHERE id = ? LIMIT 1");
        $stmtCheck->bind_param("i", $adminId);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows === 0) {
            $errors[] = 'El administrador con el ID proporcionado no existe.';
        }
        $stmtCheck->close();

        if (empty($errors)) {
            $stmtExist = $mysqli->prepare("SELECT id FROM admins WHERE (email = ? OR passport = ?) AND id != ? LIMIT 1");
            if ($stmtExist) {
                $stmtExist->bind_param("ssi", $email, $pasaporteOdocumento, $adminId);
                $stmtExist->execute();
                $resultadoExist = $stmtExist->get_result();
                if ($resultadoExist && $resultadoExist->num_rows > 0) {
                    $errors[] = 'Ya existe otro administrador registrado con ese correo o documento.';
                }
                $stmtExist->close();
            } else {
                $errors[] = 'No se pudo validar la existencia del usuario.';
            }
        }
    }


    if (empty($errors)) {
        $mysqli->begin_transaction();

        try {
    
            $sqlCustomer = "UPDATE `admins` SET 
                `full_name` = ?, 
                `email` = ?, 
                `phone` = ?, 
                `country` = ?, 
                `passport` = ?, 
                `lang` = ?, 
                `genre` = ?, 
                `home_addres` = ?, 
                `city` = ?, 
                `province` = ?, 
                `zip_code` = ?, 
                `birth_date` = ? ";
            
            $types = 'ssssssssssis'; 
            $bindParams = [
                &$nombre, 
                &$email, 
                &$telefono, 
                &$pais, 
                &$pasaporteOdocumento, 
                &$idioma, 
                &$genero, 
                &$direccion, 
                &$ciudad, 
                &$provincia, 
                &$codigoPostal, 
                &$fechaDeNacimiento 
            ];

            if ($updatePassword) {
                $sqlCustomer .= ", `password_hash` = ? ";
                $types .= 's';
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $bindParams[] = &$passwordHash;
            }

            $sqlCustomer .= " WHERE `id` = ?";
            $types .= 'i';
            $bindParams[] = &$adminId;


            $stmt = $mysqli->prepare($sqlCustomer);
            if (!$stmt) {
                 throw new Exception("Error al preparar UPDATE admins: " . $mysqli->error);
            }
            
            array_unshift($bindParams, $types);
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
            
            if (!$stmt->execute()) {
                throw new Exception($stmt->error ?: "Error al actualizar administrador");
            }
            
            $stmt->close();
            $mysqli->commit();
            $success = true;

        } catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = $e->getMessage() ?? 'Error Inesperado en el Servidor al actualizar el administrador.';
            $success = false;
        }
    }


    $data = [
        'success' => empty($errors),
        'message' => empty($errors) ? 'El Administrador ha sido Actualizado Exitosamente' : $errors[0] ?? 'Error al actualizar Administrador',
        'errors'  => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeConnection($mysqli);
?>
<?php
/**
 * API endpoint para actualizar los datos de un cliente existente.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../php/config/db.php"); 

$mysqli = openConnection();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. OBTENER Y VALIDAR EL ID DEL CLIENTE
    $adminId = isset($_POST['admin_id']) ? (int)$_POST['admin_id'] : 0;

    if ($adminId <= 0) {
        $errors[] = 'ID del usuario es obligatorio para la edición.';
    }

    // 2. OBTENER TODOS LOS CAMPOS
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

    // 3. VALIDACIONES
    // Campos obligatorios que no han cambiado su obligatoriedad
    if ($nombre === '') { $errors[] = 'Nombre es obligatorio'; }
    if ($telefono === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $telefono)) { $errors[] = 'Telefono invalido'; }
    if ($pais === '') { $errors[] = 'Pais es obligatorio'; }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email es obligatorio y debe ser válido'; }
    if ($direccion === '') { $errors[] = 'Direccion es obligatoria'; }
    if ($ciudad === '') { $errors[] = 'Ciudad es obligatoria'; }
    if ($provincia === '') { $errors[] = 'Provincia es obligatoria'; }
    if ($codigoPostal === '' || !preg_match('/^[A-Za-z0-9\-]{3,10}$/', $codigoPostal)) { $errors[] = 'Codigo postal invalido'; }
    
    // **CORRECCIÓN**: Usar 'Masculino'/'Femenino' para ENUM
    if ($genero === '' || ($genero !== 'Masculino' && $genero !== 'Femenino')) { $errors[] = 'Genero invalido'; }
    
    if ($idioma === '' || $idioma === 'no-seleccionado') { $errors[] = 'Idioma es obligatorio'; }
    if ($pasaporteOdocumento === '') { $errors[] = 'Documento es obligatorio'; }

    // Validación de fecha
    $isValidDate = function ($dateStr) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) { return false; }
        [$y, $m, $d] = explode('-', $dateStr);
        return checkdate((int)$m, (int)$d, (int)$y);
    };
    if ($fechaDeNacimiento !== '' && !$isValidDate($fechaDeNacimiento)) {
        $errors[] = 'Fecha de nacimiento invalida (formato YYYY-MM-DD)';
    }

    // Validación condicional de Contraseña (Mantiene la lógica de opcionalidad)
    $updatePassword = false;
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = 'La nueva Contraseña debe tener al menos 6 caracteres';
        }
        if ($confirmPassword !== $password) {
            $errors[] = 'Las nuevas contraseñas no coinciden';
        }
        $updatePassword = true; // Flag para incluir la contraseña en el UPDATE
    } elseif (!empty($confirmPassword)) {
        // Si solo se llena la confirmación, pero no la contraseña principal
        $errors[] = 'El campo Contraseña está vacío, pero la Confirmación no.';
    }

    // 4. VERIFICACIÓN DE EXISTENCIA Y DUPLICIDAD DE EMAIL/DOCUMENTO (EXCLUYENDO EL ADMINISTRADOR ACTUAL)
    if (empty($errors) && $adminId > 0) {
        // 4.1 Verificar que el admin exista antes de intentar actualizar
        $stmtCheck = $mysqli->prepare("SELECT id FROM customer WHERE id = ? LIMIT 1");
        $stmtCheck->bind_param("i", $adminId);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows === 0) {
            $errors[] = 'El usuario con el ID proporcionado no existe.';
        }
        $stmtCheck->close();

        // 4.2 Verificar duplicidad de email/documento con OTROS administradores
        if (empty($errors)) {
            $stmtExist = $mysqli->prepare("SELECT id FROM customer WHERE (email = ? OR passport = ?) AND id != ? LIMIT 1");
            if ($stmtExist) {
                $stmtExist->bind_param("ssi", $email, $pasaporteOdocumento, $adminId);
                $stmtExist->execute();
                $resultadoExist = $stmtExist->get_result();
                if ($resultadoExist && $resultadoExist->num_rows > 0) {
                    $errors[] = 'Ya existe otro usuario registrado con ese correo o documento.';
                }
                $stmtExist->close();
            } else {
                $errors[] = 'No se pudo validar la existencia del usuario.';
            }
        }
    }


    // 5. EJECUTAR UPDATE
    if (empty($errors)) {
        $mysqli->begin_transaction();

        try {
            // Construir la consulta UPDATE dinámicamente
            // **CORRECCIÓN DE NOMBRES DE COLUMNA**: lang, genre
            $sqlCustomer = "UPDATE `customer` SET 
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
            
            $types = 'ssssssssssis'; // Tipos para los campos fijos
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
                &$fechaDeNacimiento // Usamos el valor directamente
            ];

            // Añadir campos de contraseña si se actualizará
            if ($updatePassword) {
                $sqlCustomer .= ", `password_hash` = ? ";
                $types .= 's';
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $bindParams[] = &$passwordHash;
            }

            // Cláusula WHERE final
            $sqlCustomer .= " WHERE `id` = ?";
            $types .= 'i';
            $bindParams[] = &$adminId;


            $stmt = $mysqli->prepare($sqlCustomer);
            if (!$stmt) {
                 throw new Exception("Error al preparar UPDATE customers: " . $mysqli->error);
            }
            
            // Reajustar el array para bind_param (requiere referenciar elementos individualmente)
            array_unshift($bindParams, $types);
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
            
            if (!$stmt->execute()) {
                throw new Exception($stmt->error ?: "Error al actualizar usuario");
            }
            
            $stmt->close();
            $mysqli->commit();
            $success = true;

        } catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = $e->getMessage() ?? 'Error Inesperado en el Servidor al actualizar el usuario.';
            $success = false;
        }
    }


    // 6. RESPUESTA JSON
    $data = [
        'success' => empty($errors),
        'message' => empty($errors) ? 'El usuario ha sido Actualizado Exitosamente' : $errors[0] ?? 'Error al actualizar usuario',
        'errors'  => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
} else {
    // Si no es una solicitud POST
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeConnection($mysqli);
?>
<?php
/**
 * endpoint para agregar administradores
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../php/config/db.php");
include("../../../php/helpers/is-date.php");

$mysqli = openConnection();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if ($nombre === '') {
        $errors[] = 'Nombre es obligatorio';
    }

    if ($telefono === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $telefono)) {
        $errors[] = 'Telefono invalido';
    }

    if ($pais === '') {
        $errors[] = 'Pais es obligatorio';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email es obligatorio y debe ser válido';
    }

    if ($password === '' || strlen($password) < 6) {
        $errors[] = 'Password es obligatorio y debe tener al menos 6 caracteres';
    }

    if ($confirmPassword === '' || $confirmPassword !== $password) {
        $errors[] = 'Las contraseñas no coinciden';
    }

    if ($direccion === '') {
        $errors[] = 'Direccion es obligatoria';
    }
    if ($ciudad === '') {
        $errors[] = 'Ciudad es obligatoria';
    }
    if ($provincia === '') {
        $errors[] = 'Provincia es obligatoria';
    }
    if ($codigoPostal === '' || !preg_match('/^[A-Za-z0-9\-]{3,10}$/', $codigoPostal)) {
        $errors[] = 'Codigo postal invalido';
    }

    if ($genero === '' || ($genero !== 'Masculino' && $genero !== 'Femenino')) {
        $errors[] = 'Genero invalido';
    }

    if ($idioma === '' || $idioma === 'no-seleccionado') {
        $errors[] = 'Idioma es obligatorio';
    }

    if ($fechaDeNacimiento !== '' && !isValidDate($fechaDeNacimiento)) {
        $errors[] = 'Fecha de nacimiento invalida (formato YYYY-MM-DD)';
    }

    if ($pasaporteOdocumento === '') {
        $errors[] = 'Documento es obligatorio';
    }

    if (empty($errors)) {
        $stmtExist = $mysqli->prepare("SELECT id FROM admins WHERE email = ? OR passport = ? LIMIT 1");
        if ($stmtExist) {
            $stmtExist->bind_param("ss", $email, $pasaporteOdocumento);
            $stmtExist->execute();
            $resultadoExist = $stmtExist->get_result();
            if ($resultadoExist && $resultadoExist->num_rows > 0) {
                $errors[] = 'Ya existe un administrador registrado con ese correo o documento.';
            }
            $stmtExist->close();
        } else {
            $errors[] = 'No se pudo validar la existencia del usuario.';
        }
    }

    if (empty($errors)) {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }

        $mysqli->begin_transaction();

        try {
            $sqlCustomer = "INSERT INTO `admins` ( `full_name`, `email`, `password_hash`, `phone`, `country`, `passport`, `lang`, `genre`, `home_addres`, `city`, `province`, `zip_code`, `birth_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $mysqli->prepare($sqlCustomer);
            if (!$stmt) {
                throw new Exception("Error al preparar INSERT admins: " . $mysqli->error);
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $birthdateVal = ($fechaDeNacimiento !== '') ? $fechaDeNacimiento : null;

            $types = 'sssssssssssis';

            $bindParams = [];
            $bindParams[] = &$nombre;
            $bindParams[] = &$email;
            $bindParams[] = &$passwordHash;
            $bindParams[] = &$telefono;
            $bindParams[] = &$pais;
            $bindParams[] = &$pasaporteOdocumento;
            $bindParams[] = &$idioma;
            $bindParams[] = &$genero;
            $bindParams[] = &$direccion;
            $bindParams[] = &$ciudad;
            $bindParams[] = &$provincia;
            $bindParams[] = &$codigoPostal;
            $bindParams[] = &$birthdateVal;

            $stmt->bind_param($types, ...$bindParams);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error ?: "Error al insertar administrador");
            }

            $stmt->close();
            $userId = $mysqli->insert_id;

            if (!$userId || $userId <= 0) {
                $sqlSelectUserId = 'SELECT id FROM admins WHERE email = ? LIMIT 1';
                $idDelUsuario = $mysqli->prepare($sqlSelectUserId);
                if ($idDelUsuario) {
                    $idDelUsuario->bind_param("s", $email);
                    $idDelUsuario->execute();
                    $resultadoUserID = $idDelUsuario->get_result();
                    if ($resultadoUserID && $resultadoUserID->num_rows > 0) {
                        $filaUserId = $resultadoUserID->fetch_assoc();
                        $userId = (int)($filaUserId['id'] ?? 0);
                    }
                    $idDelUsuario->close();
                }
            }

            if (!$userId || $userId <= 0) {
                throw new Exception('No se pudo obtener el identificador del administrador recién creado.');
            }

            $sqlUser = "SELECT id, full_name, email, phone, passport, country, lang, genre, home_addres, city, province, zip_code, birth_date FROM admins WHERE id = ?";
            $stmtUserData = $mysqli->prepare($sqlUser);

            if (!$stmtUserData) {
                throw new Exception("Error al obtener la información del administrador: " . $mysqli->error);
            }

            $stmtUserData->bind_param("i", $userId);
            $stmtUserData->execute();
            $resultado = $stmtUserData->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                $success = true;


                
            } else {
                throw new Exception("El administrador no esta disponible. Favor intentar mas tarde.");
            }

            $stmtUserData->close();
            $mysqli->commit();
        } catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = $e->getMessage() ?? 'Error Inesperado en el Servidor al crear un nuevo administrador.';
            $success = false;
        }
    }

    $data = [
        'success' => empty($errors),
        'message' => empty($errors) ? 'El Administrador ha sido Creado Exitasamente' : $errors[0] ?? 'Error al crear Administrador',
        'error'   => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}

closeConnection($mysqli);
?>

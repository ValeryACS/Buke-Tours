<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include "../../../php/config/db.php";

$response = [
    'mensaje'=> 'Error inesperado',
    'debug'=> 'inicio',
    'status'=> 'error',
];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $rawInput = file_get_contents('php://input');
        $decodedJson = json_decode($rawInput ?? '', true);
        $hasJsonPayload = json_last_error() === JSON_ERROR_NONE && is_array($decodedJson);

        $email = '';
        $password = '';

        if ($hasJsonPayload) {
            $email = trim((string)($decodedJson['email'] ?? ''));
            $password = trim((string)($decodedJson['password'] ?? ''));
        } else {
            $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
            $password = isset($_POST['password']) ? trim((string)$_POST['password']) : '';
        }

        if($email === ''){
            $response['mensaje']= 'El email es requerido';
            $response['debug'] = 'Datos del usuario json invalidos.';
            echo json_encode($response);
            exit();
        }
        if($password === ''){
            $response['mensaje']= 'La contraseña es requerida';
            $response['debug'] = 'Datos del usuario json invalidos.';
            echo json_encode($response);
            exit();
        }

        if($email === '' && $password === ''){
            $response['mensaje'] = 'Email y contraseña vacios.';
            $response['debug'] = 'Campos vacios en el login.';
            echo json_encode($response);
            exit();
        }

        $mysqli = openConnection();

        $sql = "SELECT `id`, `full_name`, `email`, `password_hash`, `phone`, `country`, `passport`, `lang`, `genre`, `home_addres`, `city`, `province`, `zip_code`, `birth_date`, `password_hash` FROM customer WHERE email = ?";

        $stmt= $mysqli->prepare($sql);

        if(!$stmt){
            $response['mensaje'] = 'Error al prepara la consulta de login.';
            $response['debug'] = 'SQL fallo en el Login';
            echo json_encode($response);
            exit();
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $stmt->bind_param("s", $email);
        if($stmt->execute()){
            $resultado = $stmt->get_result();

            if($resultado && $resultado->num_rows > 0){
                $fila= $resultado->fetch_assoc();

                if(password_verify($password, $fila['password_hash'])){
                    $_SESSION['id'] = (int)$fila['id'];
                    $_SESSION['userId'] = (int)$fila['id'];
                    $_SESSION['nombre'] = $fila['full_name'];
                    $_SESSION['email'] = $fila['email'];
                    $_SESSION['telefono'] = $fila['phone'];
                    $_SESSION['pais'] = $fila['country'];
                    $_SESSION['passport'] = $fila['passport'];
                    $_SESSION['idioma'] = $fila['lang'];
                    $_SESSION['genero'] = $fila['genre'];
                    $_SESSION['direccion'] = $fila['home_addres'];
                    $_SESSION['ciudad'] = $fila['city'];
                    $_SESSION['provincia'] = $fila['province'];
                    $_SESSION['codigo_postal'] = $fila['zip_code'];
                    $_SESSION['fecha_nacimiento'] = $fila['birth_date'];
                    $response = [
                        'status'=> 'ok',
                        'name'=> $fila['full_name'],
                        'debug'=> 'El usuario del correo'. $fila['email']. ' ha hecho un login exitoso.',
                    ];
                }
                else{
                    $response['mensaje'] = 'Contraseña incorrecta';
                    $response['debug'] = 'Fallo de contraseña';
                }    
            }else{
                $response['mensaje'] = 'Usuario no encontrado';
                $response['debug'] = 'Usuario no existe';
            }
        }
        else{
            $response['mensaje'] = 'Error al Consultar Usuario.';
            $response['debug'] = 'Usuario no encontrado';
        }

        
    } 
    catch (\Throwable $th) {
        $response['mensaje'] = 'Sucedio un error al realizar login';
        $response['debug'] = 'Catch exception: '. $th->getMessage();
    }
    finally{
        closeConnection($mysqli);
        echo json_encode($response);
        exit();
    }
}
?>

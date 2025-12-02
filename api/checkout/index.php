<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../php/config/db.php");

$mysqli = openConnection();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = isset($_POST['nombre']) ? trim((string)$_POST['nombre']) : '';
    $telefono = isset($_POST['telefono']) ? trim((string)$_POST['telefono']) : '';
    $pais = isset($_POST['pais']) ? trim((string)$_POST['pais']) : '';
    $adultos = isset($_POST['adultos']) ? trim((string)$_POST['adultos']) : '0';
    $ninos = isset($_POST['ninos']) ? trim((string)$_POST['ninos']) : '0';
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    $idioma = isset($_POST['idioma']) ? trim((string)$_POST['idioma']) : '';
    $pasaporteOdocumento = isset($_POST['pasaporteOdocumento']) ? trim((string)$_POST['pasaporteOdocumento']) : '';

    // Expect JSON strings or plain strings; avoid trim() on arrays to prevent TypeError
    $fechasDeingresos = isset($_POST['fechasDeingresos'])
        ? (is_array($_POST['fechasDeingresos'])
            ? json_encode($_POST['fechasDeingresos'])
            : trim((string)$_POST['fechasDeingresos']))
        : '';

    $fechasDeSalidas = isset($_POST['fechasDeSalidas'])
        ? (is_array($_POST['fechasDeSalidas'])
            ? json_encode($_POST['fechasDeSalidas'])
            : trim((string)$_POST['fechasDeSalidas']))
        : '';

    // Booleans o checkbox
    $seguro = isset($_POST['seguro'])
        ? (is_bool($_POST['seguro']) ? ($_POST['seguro'] ? '1' : '0') : trim((string)$_POST['seguro']))
        : '';

    $transporte = isset($_POST['transporte'])
        ? (is_bool($_POST['transporte']) ? ($_POST['transporte'] ? '1' : '0') : trim((string)$_POST['transporte']))
        : '';

    $fotos = isset($_POST['fotos'])
        ? (is_bool($_POST['fotos']) ? ($_POST['fotos'] ? '1' : '0') : trim((string)$_POST['fotos']))
        : '';

    $desayuno = isset($_POST['desayuno'])
        ? (is_bool($_POST['desayuno']) ? ($_POST['desayuno'] ? '1' : '0') : trim((string)$_POST['desayuno']))
        : '';

    $almuerzo = isset($_POST['almuerzo'])
        ? (is_bool($_POST['almuerzo']) ? ($_POST['almuerzo'] ? '1' : '0') : trim((string)$_POST['almuerzo']))
        : '';

    $cena = isset($_POST['cena'])
        ? (is_bool($_POST['cena']) ? ($_POST['cena'] ? '1' : '0') : trim((string)$_POST['cena']))
        : '';

    $direccion = isset($_POST['direccion']) ? trim((string)$_POST['direccion']) : '';
    $ciudad = isset($_POST['ciudad']) ? trim((string)$_POST['ciudad']) : '';
    $provincia = isset($_POST['provincia']) ? trim((string)$_POST['provincia']) : '';
    $codigoPostal = isset($_POST['codigoPostal']) ? trim((string)$_POST['codigoPostal']) : '';
    $nombreDelTitular = isset($_POST['nombreDelTitular']) ? trim((string)$_POST['nombreDelTitular']) : '';
    $numeroDeLaTarjeta = isset($_POST['numeroDeLaTarjeta']) ? trim((string)$_POST['numeroDeLaTarjeta']) : '';
    $mes = isset($_POST['mes']) ? trim((string)$_POST['mes']) : (string)date('n');
    $year = isset($_POST['year']) ? trim((string)$_POST['year']) : (string)date('Y');
    $cvv = isset($_POST['cvv']) ? trim((string)$_POST['cvv']) : '';

    // Helpers para fechas
    $parseJson = function ($raw) {
        $raw = trim($raw);
        if ($raw === '') return [];
        $first = substr($raw, 0, 1);
        if ($first === '[' || $first === '{') {
            $decoded = json_decode($raw, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
        }
        return [];
    };
    /**
     * Valida si las fechas cumplen con el formato esperado
     */
    $isValidDate = function ($dateStr) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) return false;
        [$y, $m, $d] = explode('-', $dateStr);
        return checkdate((int)$m, (int)$d, (int)$y);
    };

    $ingresos = $parseJson($fechasDeingresos);
    $salidas  = $parseJson($fechasDeSalidas);

    // Validar ingresos
    if (!is_array($ingresos) || empty($ingresos)) {
        $errors[] = 'Fechas de ingresos inválidas o vacías';
    } else {
        foreach ($ingresos as $idx => $item) {
            if (!is_array($item)) {
                $errors[] = "Ingreso #$idx inválido";
                continue;
            }
            $date = $item['check_in_date'] ?? '';
            if (!$isValidDate($date)) {
                $errors[] = "Fecha de ingreso #$idx inválida (formato YYYY-MM-DD)";
            }
            if(!$item['tour_id'] || $item['tour_id']<=0){
                $errors[] = "El Tour ID es inválido";
            }
        }
    }

    // Validar salidas
    if (!is_array($salidas) || empty($salidas)) {
        $errors[] = 'Fechas de salidas inválidas o vacías';
    } else {
        foreach ($salidas as $idx => $item) {
            if (!is_array($item)) {
                $errors[] = "Salida #$idx inválida";
                continue;
            }
            $date = $item['check_out_date'] ?? '';
            if (!$isValidDate($date)) {
                $errors[] = "Fecha de salida #$idx inválida (formato YYYY-MM-DD)";
            }
            if(!$item['tour_id'] || $item['tour_id']<=0){
                $errors[] = "El Tour ID es inválido";
            }
        }
    }

    // Validar que la cantidad de ingresos y salidas por tour coincida
    if (empty($errors)) {
        $mapIngresos = [];
        foreach ($ingresos as $item) {
            $tid = (int)($item['tour_id'] ?? 0);
            $mapIngresos[$tid] = ($mapIngresos[$tid] ?? 0) + 1;
        }

        $mapSalidas = [];
        foreach ($salidas as $item) {
            $tid = (int)($item['tour_id'] ?? 0);
            $mapSalidas[$tid] = ($mapSalidas[$tid] ?? 0) + 1;
        }

        ksort($mapIngresos);
        ksort($mapSalidas);

        if ($mapIngresos !== $mapSalidas) {
            $errors[] = 'La cantidad de ingresos y salidas por tour no coincide';
        }
    }

    // Validaciones de datos personales
    if ($nombre === '') {
        $errors[] = 'Nombre es obligatorio';
    }

    if ($telefono === '' || !preg_match('/^[0-9+\-()\s]{7,20}$/', $telefono)) {
        $errors[] = 'Telefono invalido';
    }

    if ($pais === '') {
        $errors[] = 'Pais es obligatorio';
    }

    if ($adultos === '' || !ctype_digit($adultos) || (int)$adultos < 1) {
        $errors[] = 'Adultos debe ser entero >= 1';
    }

    if ($ninos === '' || !ctype_digit($ninos) || (int)$ninos < 0) {
        $errors[] = 'Ninos debe ser entero >= 0';
    }

    if ($idioma === '' || $idioma === 'no-seleccionado') {
        $errors[] = 'Idioma es obligatorio';
    }

    if($total <= 10){
        $errors[] = 'El total no puede ser menor a 10';
    }
     if($subtotal <= 10){
        $errors[] = 'El subtotal no puede ser menor a 10';
    }

    if (!isset($_POST['email']) || trim((string)$_POST['email']) === '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email es obligatorio y debe ser válido';
    }

    if ($pasaporteOdocumento === '') {
        $errors[] = 'Documento es obligatorio';
    }

    $boolFields = [
        'seguro'     => $seguro,
        'transporte' => $transporte,
        'fotos'      => $fotos,
        'desayuno'   => $desayuno,
        'almuerzo'   => $almuerzo,
        'cena'       => $cena,
    ];

    foreach ($boolFields as $label => $value) {
        if (!in_array($value, ['0','1','true','false','', 'on'], true)) {
            $errors[] = "Valor invalido para $label";
        }
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

    if ($nombreDelTitular === '') {
        $errors[] = 'Nombre del titular es obligatorio';
    }

    if ($numeroDeLaTarjeta === '') {
        $errors[] = 'Numero de tarjeta invalido';
    }

    if (!ctype_digit($mes) || (int)$mes < 1 || (int)$mes > 12) {
        $errors[] = 'Mes de expiracion invalido';
    }

    if (!ctype_digit($year)) {
        $errors[] = 'Año de expiracion invalido';
    }

    if ($cvv === '' || !preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = 'CVV invalido';
    }

    // Si no hay errores, procesar DB
    if (empty($errors)) {

        // Cerrar stmt previo si existiera
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }

        // Email normalizado
        $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';

        // Normalizar booleans a 0/1
        $toBool = function ($v) {
            $v = strtolower((string)$v);
            return in_array($v, ['1','true','on'], true) ? 1 : 0;
        };

        $breakfast        = $toBool($desayuno);
        $lunch            = $toBool($almuerzo);
        $dinner           = $toBool($cena);
        $transport        = $toBool($transporte);
        $travel_insurance = $toBool($seguro);
        $photo_package    = $toBool($fotos);

        // Para poder hacer los pares de fechas por tour
        $ingresosPorTour = [];
        foreach ($ingresos as $item) {
            $tid = (int)($item['tour_id'] ?? 0);
            $ingresosPorTour[$tid][] = $item['check_in_date'] ?? '';
        }

        $salidasPorTour = [];
        foreach ($salidas as $item) {
            $tid = (int)($item['tour_id'] ?? 0);
            $salidasPorTour[$tid][] = $item['check_out_date'] ?? '';
        }

        $mysqli->begin_transaction();

        try {
            // INSERT en reservation
            $sqlReservation = "INSERT INTO reservation (
                full_name,
                email,
                telephone,
                country,
                passport,
                adults,
                children,
                idioma,
                breakfast,
                lunch,
                dinner,
                transport,
                travel_insurance,
                photo_package,
                home_address,
                city,
                province,
                postal_code,
                total,
                subtotal
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $mysqli->prepare($sqlReservation);
            if (!$stmt) {
                throw new Exception("Error al preparar INSERT reservation: " . $mysqli->error);
            }

            $adults_i           = (int)$adultos;
            $children_i         = (int)$ninos;
            $total_i         = (float)$total;
            $subtotal_i         = (float)$subtotal;
            $breakfast_i        = (int)$breakfast;
            $lunch_i            = (int)$lunch;
            $dinner_i           = (int)$dinner;
            $transport_i        = (int)$transport;
            $travel_insurance_i = (int)$travel_insurance;
            $photo_package_i    = (int)$photo_package;

            // Tipos: sssss i i s i i i i i i ssss (18 en total)
            $types = 'sssssiisiiiiiissssii';

            $stmt->bind_param(
                $types,
                $nombre,
                $email,
                $telefono,
                $pais,
                $pasaporteOdocumento,
                $adults_i,
                $children_i,
                $idioma,
                $breakfast_i,
                $lunch_i,
                $dinner_i,
                $transport_i,
                $travel_insurance_i,
                $photo_package_i,
                $direccion,
                $ciudad,
                $provincia,
                $codigoPostal,
                $total_i,
                $subtotal_i
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar reservation: " . $stmt->error);
            }

            $reservationId = $mysqli->insert_id;
            $stmt->close();

            // INSERT en reservation_tour
            $sqlRT = "INSERT INTO reservation_tour (
                reservation_id,
                tour_id,
                check_in_date,
                check_out_date,
                adults,
                children
            ) VALUES (?,?,?,?,?,?)";

            $stmtRT = $mysqli->prepare($sqlRT);
            if (!$stmtRT) {
                throw new Exception("Error al preparar INSERT reservation_tour: " . $mysqli->error);
            }

            $typesRT = 'iissii';

            foreach ($ingresosPorTour as $tourId => $checkIns) {
                $checkOuts = $salidasPorTour[$tourId] ?? [];
                $pairs     = min(count($checkIns), count($checkOuts));

                for ($i = 0; $i < $pairs; $i++) {
                    $checkInDate   = $checkIns[$i];
                    $checkOutDate  = $checkOuts[$i];
                    $tour_id_i     = (int)$tourId;
                    $reservation_i = (int)$reservationId;
                    $adults_i      = (int)$adultos;
                    $children_i    = (int)$ninos;

                    $stmtRT->bind_param(
                        $typesRT,
                        $reservation_i,
                        $tour_id_i,
                        $checkInDate,
                        $checkOutDate,
                        $adults_i,
                        $children_i
                    );

                    if (!$stmtRT->execute()) {
                        throw new Exception("Error al crear la Reservacion (reservation_tour): " . $stmtRT->error);
                    }
                }
            }

            $stmtRT->close();

            $mysqli->commit();
            $success = true;

        } catch (Exception $e) {
            $mysqli->rollback();
            $errors[] = $e->getMessage();
            $success = false;
        }
    }

    // Respuesta JSON
    $data = [
        'success' => $success && empty($errors),
        'message' => ($success && empty($errors))
            ? 'Tour reservado exitosamente'
            : 'Error al procesar pago.',
        'error'   => empty($errors) ? null : $errors,
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}

closeConnection($mysqli);
?>
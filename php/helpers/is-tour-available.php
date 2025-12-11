<?php
/**
 * Devuelve true si el tour tiene disponibilidad para la fecha de checkIn.
 * Si no se pasa un mysqli se abre una conexión temporal.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/db.php';

function isTourAvailable($tourId, $checkInDate, $mysqli = null) {
    if ((int)$tourId <= 0 || !is_string($checkInDate) || $checkInDate === '') {
        return false;
    }

    $closeConnection = false;
    if ($mysqli === null) {
        $mysqli = openConnection();
        $closeConnection = true;
    } elseif (!($mysqli instanceof mysqli)) {
        return false;
    }

    $sql = "SELECT is_available
            FROM tour_availability
            WHERE tour_id = ?
              AND check_in_date = ?
            LIMIT 1";

    $isAvailable = true;
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("is", $tourId, $checkInDate);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                $isAvailable = (int)$row['is_available'] === 1;
            } else {
                $isAvailable = true;
            }
        } else {
            $isAvailable = false;
        }
        $stmt->close();
    } else {
        $isAvailable = false;
    }

    if ($closeConnection) {
        closeConnection($mysqli);
    }

    return $isAvailable;
}

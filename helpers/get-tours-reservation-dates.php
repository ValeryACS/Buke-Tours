<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../php/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function getTourReservationDates($reservationId){
    $userID = isset($_SESSION['id'])? (int)$_SESSION['id']: 0;
    if($userID<= 0){
        echo "Usuario inválido.";
    }
    else{
        $sqlReservationTour = "SELECT `id`,`reservation_id`,`tour_id`,`check_in_date`,`check_out_date` FROM `reservation_tour` WHERE reservation_id = ?";

        $mysqli = openConnection();
        $tourDates = $mysqli->prepare($sqlReservationTour);
        if (!$tourDates) {
            closeConnection($mysqli);
            throw new RuntimeException('No se pudo preparar la consulta: ' . $mysqli->error);
        }

        $tourDates->bind_param("i", $reservationId);
        if($tourDates->execute()){
            $resultTourDates = $tourDates->get_result();
            $tourDatesHTML = "";

            if ($resultTourDates) {
                while ($row = $resultTourDates->fetch_assoc()) {
                    $tourDatesHTML .= "<p class='w-100'>Check In: " . htmlspecialchars($row['check_in_date']) . "</p>";
                    $tourDatesHTML .= "<p class='w-100'>Check Out: " . htmlspecialchars($row['check_out_date']) . "</p>";
                }
            }

            $tourDates->close();
            closeConnection($mysqli);
            echo $tourDatesHTML;
        }
        else{
            echo "Error al consultar la reservacion";
        }
    }
    
}
?>

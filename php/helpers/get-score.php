<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Usada para retornar la califiacion del Tour en base a la puntuacion numerica
 */
function getScore($score){
    $scoreParsed = (int)$score;
    switch ($scoreParsed) {
        case 1:
            echo "<span class='estrellas'>⭐⭐⭐⭐⭐</span>";
            break;
        case 2:
            echo "<span class='estrellas'>⭐⭐</span>";
            break;
        case 3:
            echo "<span class='estrellas'>⭐⭐⭐</span>";
            break;
        case 4:
            echo "<span class='estrellas'>⭐⭐⭐⭐</span>";
            break;
        case 5:
            echo "<span class='estrellas'>⭐⭐⭐⭐⭐</span>";
            break;
        
        default:
            echo "<span class='estrellas'>⭐⭐⭐⭐⭐</span>";
            break;
    }
}
?>
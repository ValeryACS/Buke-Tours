<?php
/**
 * Usada para crear la conexion a la base de datos MySQL
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Usada para retornar la conexion con MySQL
 */
function openConnection(){
    $host = "localhost";
    $user = "root";
    $password = "";
    $db="buke_tours_db";
    $port= 3306;

    $mysqli = new mysqli($host,$user,$password,$db, $port);

    if($mysqli->connect_errno){
       throw new Exception("Error de conexion", $mysqli->connect_error);
    }
    
    $mysqli->set_charset("utf8mb4");
    return $mysqli;
}

function closeConnection($mysqli){
    $mysqli->close();
}


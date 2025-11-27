<?php
session_start();

if(isset($_POST['lang'])){
    $language = $_POST['lang'];
    if(in_array($language, ['es', 'en'])){
        $_SESSION['lang'] = $language;
        echo json_encode(['status' => 'success', 'lang' => $language]);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Idioma no válido']);

    }

}

?>
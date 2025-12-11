<?php
define('ROOT_PATH', __DIR__ . '/');
require_once 'config.php'; 
include 'php/components/navbar.php';

include ROOT_PATH . 'language/lang_' . $_SESSION['lang'] . '.php';
?>
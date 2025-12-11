<?php 
/**
 * Funcion usada para convertir un string en boolean
 * @return {int} - Si es true retorna 1 si es false retorna 0
 */
function parseBoolean($v) {
    $v = strtolower((string)$v);
    return in_array($v, ['1','true','on'], true) ? 1 : 0;
};
?>
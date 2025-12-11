<?php 
/**
 * Funcion usada para parsear las fechas ya que normalmente puede que vengan con otro formato
 * O con un formato inesperado
 */
function isValidDate($dateStr) {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) return false;
    [$y, $m, $d] = explode('-', $dateStr);
    return checkdate((int)$m, (int)$d, (int)$y);
};
?>
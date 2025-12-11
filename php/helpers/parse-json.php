<?php 
/**
 * Usada para parsear un JSON, ya que normalmente se envian como string usando JSON.stringify()
 */
function parseJson($raw) {
    $raw = trim($raw);
    if ($raw === '') return [];
    $first = substr($raw, 0, 1);
    if ($first === '[' || $first === '{') {
        $decoded = json_decode($raw, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
    }
    return [];
}
?>
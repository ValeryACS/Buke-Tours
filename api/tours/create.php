<?php
require_once 'api/tours/index.php'; 

// Establecer cupon_discount a 0 por defecto (ya que es NOT NULL con DEFAULT 0 en la DB, pero lo forzamos aquí)
$cupon_discount = 0; 

// 1. Verificar si la solicitud es un POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Obtener y sanear TODOS los datos del formulario

    // Campos de texto requeridos
    $title = trim($_POST['nombre'] ?? '');
    $description = trim($_POST['descripcion'] ?? '');
    $location = trim($_POST['ubicacion'] ?? '');
    $img = trim($_POST['img'] ?? '');
    
    // Campos numéricos requeridos (convertidos a su tipo, con fallback seguro)
    $price_usd = floatval($_POST['price_usd'] ?? 0);
    $rating = floatval($_POST['rating'] ?? 0);
    $duration_hours = floatval($_POST['duration_hours'] ?? 0);
    $adults_limit = intval($_POST['adults_limit'] ?? 0);
    $children_limit = intval($_POST['children_limit'] ?? 0);
    $discount = intval($_POST['discount'] ?? 0);

    // Campos opcionales (pueden ser NULL, por eso no se sanitizan tanto si están vacíos)
    $cupon_code = trim($_POST['cupon_code'] ?? null);
    // Si el código de cupón está vacío, lo forzamos a NULL, si no, lo usamos.
    $cupon_code = empty($cupon_code) ? null : $cupon_code; 
    
    $iframe = trim($_POST['iframe'] ?? null);
    $iframe = empty($iframe) ? null : $iframe; 

    // Generar el SKU único
    $sku = "TOUR-" . time(); 
    
    // 3. Validación Estricta del lado del Servidor (NOT NULL y límites)
    $missing_required = empty($title) || empty($description) || empty($location) || empty($img);
    $invalid_values = $price_usd <= 0 || $rating < 1 || $rating > 5 || $duration_hours <= 0 || $adults_limit <= 0 || $discount < 0 || $discount > 100;

    if ($missing_required || $invalid_values) {
        // En un entorno de producción, es mejor redirigir al formulario con mensajes de error.
        header("Location: /admin/tours/create.php?error=validation_failed");
        exit;
    }

    $mysqli = null; 
    try {
        // 4. Establecer la conexión
        $mysqli = openConnection();

        // 5. Sentencia SQL con TODAS las 14 columnas de la tabla `tour`
        $sql = "INSERT INTO tour (
                    sku, title, location, price_usd, cupon_code, 
                    cupon_discount, rating, duration_hours, discount, 
                    img, description, iframe, adults_limit, children_limit
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $mysqli->prepare($sql)) {
            
            // 6. Enlazar variables a la sentencia preparada
            // Tipos: sss | d | s | i | d | d | i | s | s | s | i | i
            $stmt->bind_param("sssdidisdsssisii", 
                $sku, $title, $location, $price_usd, $cupon_code, 
                $cupon_discount, $rating, $duration_hours, $discount, 
                $img, $description, $iframe, $adults_limit, $children_limit
            );

            // 7. Ejecutar la sentencia
            if ($stmt->execute()) {
                // Éxito: Redirigir al listado de tours
                header("Location: /admin/tours/?success=created");
                exit;
            } else {
                throw new Exception("Error al ejecutar la inserción: " . $stmt->error);
            }

            $stmt->close();
        } else {
             throw new Exception("Error al preparar la consulta: " . $mysqli->error);
        }

    } catch (Exception $e) {
        // Registrar error y redirigir
        error_log("Error al crear tour: " . $e->getMessage());
        header("Location: /admin/tours/create.php?error=" . urlencode("Error en el servidor: " . $e->getMessage()));
        exit;

    } finally {
        // 8. Asegurar el cierre de la conexión
        if ($mysqli) {
            closeConnection($mysqli);
        }
    }
} else {
    // Si se accede directamente sin POST, redirigir al formulario
    header("Location: /admin/tours/create.php");
    exit;
}
?>
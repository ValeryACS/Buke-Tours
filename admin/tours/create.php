<?php
header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<?php include '../../php/components/admin/nav-bar-admin.php'; ?>

<div class="container py-4">
    <h1 class="mb-4">Crear Tour</h1>

    <form action="index.php" method="POST" id="createTourForm">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Tour</label>
            <input type="text" name="nombre" id="nombre" class="form-control">
            <div class="invalid-feedback">El nombre es obligatorio y no debe contener números.</div>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" placeholder="Costa Rica">
            <div class="invalid-feedback">La ubicación es obligatoria y no debe contener números.</div>
        </div>
        
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
            <div class="invalid-feedback">La descripción es obligatoria y debe tener al menos 20 caracteres.</div>
        </div>

        <hr>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="price_usd" class="form-label">Precio ($ USD)</label>
                <input type="text" name="price_usd" id="price_usd" class="form-control input-number">
                <div class="invalid-feedback">El precio es obligatorio y debe ser un número positivo.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="rating" class="form-label">Rating (1.0 - 5.0)</label>
                <input type="text" name="rating" id="rating" class="form-control input-number">
                <div class="invalid-feedback">El rating es obligatorio (entre 1.0 y 5.0).</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="duration_hours" class="form-label">Duración (Horas)</label>
                <input type="text" name="duration_hours" id="duration_hours" class="form-control input-number">
                <div class="invalid-feedback">La duración es obligatoria.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="adults_limit" class="form-label">Límite Adultos</label>
                <input type="text" name="adults_limit" id="adults_limit" class="form-control input-number">
                <div class="invalid-feedback">El límite de adultos es obligatorio.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="children_limit" class="form-label">Límite Niños</label>
                <input type="text" name="children_limit" id="children_limit" class="form-control input-number">
                <div class="invalid-feedback">El límite de niños es obligatorio.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="discount" class="form-label">Descuento (%)</label>
            <input type="text" name="discount" id="discount" class="form-control input-number">
            <div class="invalid-feedback">El descuento es obligatorio (0-100).</div>
        </div>

        <hr>

        <div class="mb-3">
            <label for="img" class="form-label">URL de Imagen</label>
            <input type="text" name="img" id="img" class="form-control" placeholder="http://ejemplo.com/imagen.jpg">
            <div class="invalid-feedback">La URL de la imagen es obligatoria.</div>
        </div>

        <div class="mb-3">
            <label for="cupon_code" class="form-label">Código de Cupón (Opcional)</label>
            <input type="text" name="cupon_code" id="cupon_code" class="form-control">
        </div>

        <div class="mb-3">
            <label for="iframe" class="form-label">Iframe / Mapa (Opcional)</label>
            <textarea name="iframe" id="iframe" class="form-control" rows="2"></textarea>
        </div>
        
        <button type="submit" class="btn btn-success">Guardar Tour</button>
        <a href="/admin/tours/" class="btn btn-secondary">Cancelar</a>

    </form>
</div>

<script type="module" src=".././create-tour.js"></script>

</body>
</html>
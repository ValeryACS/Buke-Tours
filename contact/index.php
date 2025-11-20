<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de contacto</title>
     <?php 
      include '../php/styles/common-styles.php';
    ?>
</head>

<body>
    <?php
    include '../php/components/navbar.php';
    ?>

    <div class="container py-4">
        
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 700px;">
        <h1 class="titulo mb-4">Contactenos</h1>
        <form>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre Completo" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="Correo Electrónico" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" placeholder="Número de teléfono">
                </div>
                <div class="col-md-6">
                    <label for="asunto" class="form-label">Asunto</label>
                    <input type="text" class="form-control" id="asunto"
                        placeholder="Ej: Consulta sobre Tour a Guanacaste">
                </div>
            </div>

            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label>
                <textarea class="form-control" id="mensaje" rows="4" placeholder="Escribe tu mensaje aquí..."
                    required></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5 py-2 w-100">Enviar mensaje</button>
            </div>
        </form>
    </div>
    </div>
    <br>

     <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
</body>

</html>
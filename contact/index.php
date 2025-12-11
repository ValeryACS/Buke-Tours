<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];

?>

<!DOCTYPE html>
<html lang="<?php echo $html_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['contact_page_title']; ?></title>
     <?php 
      include '../php/styles/common-styles.php';
    ?>
    <link rel="stylesheet" href="/Buke-Tours/assets/css/profile.css" />
</head>

<body>
    <?php
    require_once '../config.php';
    ?>
    <main class="container py-4">    
        <section class=" bg-buke-tours card shadow-sm p-4 mx-auto profile-form-section" style="max-width: 700px;">
            <h1 class="titulo mb-4"><?php echo $lang['contactenos']; ?></h1>
            <form id="contact-form">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label"><?php echo $lang['nombre_completo']; ?></label>
                        <input name="nombre" type="text" class="form-control" id="nombre" placeholder="<?php echo $lang['nombre_completo']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label"><?php echo $lang['correo_electronico']; ?></label>
                        <input type="email" class="form-control" id="email" placeholder="<?php echo $lang['correo_electronico']; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="telefono" class="form-label"><?php echo $lang['numero_telefono']; ?></label>
                        <input name="telefono" type="tel" class="form-control" id="telefono" placeholder="<?php echo $lang['numero_telefono']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="asunto" class="form-label"><?php echo $lang['asunto']; ?></label>
                        <input type="text" class="form-control" id="asunto" name="asunto"
                            placeholder="<?php echo $lang['ejemplo']; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label"><?php echo $lang['mensaje']; ?></label>
                    <textarea name="mensaje" class="form-control" id="mensaje" rows="4" placeholder="<?php echo $lang['detalle']; ?>"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success px-5 py-2 w-100"><?php echo $lang['enviar mensaje']; ?></button>
                </div>
            </form>
        </section>
    </main>
     <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
    <script
      type="module"
      src="/Buke-Tours/assets/js/contact-page.js"
      defer
    ></script>
</body>
</html>

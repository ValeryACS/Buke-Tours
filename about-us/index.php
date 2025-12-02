<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sobre Nosotros</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
  </head>
  <body>
    <?php
    include '../php/components/navbar.php';
    ?>
    <!-- Sección principal -->
    <main class="content bg-light-subtle bg-opacity-75 mt-5">
      <section>
        <article class="hero">
          <h1 class="titulo">Bukë Tours</h1>
          <p class="m-4">
            En Bukë Tours nos especializamos en ofrecer experiencias únicas y
            auténticas en Costa Rica. Nuestro objetivo es brindarte los mejores
            tours para descubrir la naturaleza, la cultura y la aventura en los
            lugares más impresionantes del país.
          </p>
        </article>

        <article class="rectangle-bbf6cb004687">
          <h2 class="titulo">Nuestra Misión</h2>
          <p class="m-4">
            Queremos que cada viaje sea una experiencia inolvidable, llena de
            emoción y conexión con la naturaleza.
          </p>

          <h2 class="titulo">Nuestro Equipo</h2>
          <p class="m-4">
            Contamos con un equipo de guías profesionales y apasionados por
            compartir la belleza de Costa Rica contigo.
          </p>
        </article>
      </section>
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

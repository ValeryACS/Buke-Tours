<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
  </head>
  <body>
    <?php
    include '../php/components/navbar.php';
    ?>

    <main class="perfil-contenedor">
      <div class="perfil-p"></div>
      <div class="perfil-formulario">
        <div class="container py-5">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
              <div class="card shadow-sm">
                <div class="card-body p-4">
                  <h1 class="titulo mb-4">Editar perfil</h1>

                  <form action="" novalidate>
                    <div class="row g-3">
                      <!-- Columna izquierda -->
                      <div class="col-12 col-md-6">
                        <label for="fullName" class="form-label"
                          >Nombre completo</label
                        >
                        <input
                          id="fullName"
                          name="fullName"
                          type="text"
                          class="form-control"
                          placeholder="Nombre Completo"
                          autocomplete="name"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="email" class="form-label"
                          >Correo electrónico</label
                        >
                        <input
                          id="email"
                          name="email"
                          type="email"
                          class="form-control"
                          placeholder="Correo Electrónico"
                          autocomplete="email"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="phone" class="form-label"
                          >Número de teléfono</label
                        >
                        <input
                          id="phone"
                          name="phone"
                          type="tel"
                          class="form-control"
                          placeholder="Número de Teléfono"
                          autocomplete="tel"
                          inputmode="tel"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="password" class="form-label"
                          >Contraseña</label
                        >
                        <input
                          id="password"
                          name="password"
                          type="password"
                          class="form-control"
                          placeholder="Contraseña"
                          autocomplete="new-password"
                          required
                        />
                      </div>

                      <!-- Columna derecha -->
                      <div class="col-12">
                        <label for="address" class="form-label"
                          >Dirección completa</label
                        >
                        <input
                          id="address"
                          name="address"
                          type="text"
                          class="form-control"
                          placeholder="Dirección Completa"
                          autocomplete="street-address"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="country" class="form-label">País</label>
                        <input
                          id="country"
                          name="country"
                          type="text"
                          class="form-control"
                          placeholder="País"
                          autocomplete="country-name"
                          required
                        />
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="language" class="form-label">Idioma</label>
                        <input
                          id="language"
                          name="language"
                          type="text"
                          class="form-control"
                          placeholder="Idioma"
                          required
                        />
                      </div>

                      <div class="col-12 d-flex justify-content-end pt-2">
                        <button type="submit" class="btn btn-danger px-4">
                          Guardar
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
  </body>
</html>

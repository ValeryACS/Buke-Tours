<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrito de Compras</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
    <link
      rel="stylesheet"
      href="/Buke-Tours/assets/css/cart-skeleton.css"
      type="text/css"
    />
  </head>
  <body>
    <?php
    include '../php/components/navbar.php';
    ?>
    <main>
      <section id="cart" class="py-5">
        <div class="container bg-light container-content float-lg-none float-start">
          <!-- Encabezado -->
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="titulo h3 mb-0">Bukë Tours</h1>
            <span class="badge text-bg-danger tours-added d-none d-lg-flex">3 artículos</span>
          </div>

          <div class="row g-4">
            <!-- Lista de productos -->
            <div class="col-12 col-lg-8">
              <div class="list-group" id="cart-list-tours">
                <?php 
                    include '../php/components/skeletons/cart-tour-list-skeleton.php';
                ?>
              </div>

              <!-- CTA inferior en móvil -->
              <div class="d-grid d-lg-none mt-4 gap-2">
                <a href="/Buke-Tours/checkout/" class="btn btn-dark btn-lg"
                  >Finalizar compra</a
                >
                <a href="/Buke-Tours/tours/" class="btn btn-danger-buke-tours"
                  >Seguir comprando</a
                >
              </div>
            </div>

            <!-- Resumen -->
            <aside class="col-12 col-lg-4 resumen-del-pedido">
              <?php 
                    include '../php/components/skeletons/cart-summary-skeleton.php';
                ?>
              <div class="card shadow-sm sticky-lg-top d-none" id="summary-content" style="top: 1rem">
                <div class="card-body">
                  <h2 class="h6 mb-3">Resumen del pedido</h2>

                  <!-- Cupón -->
                  <div class="mb-3">
                    <label for="coupon" class="form-label small"
                      >Cupón de descuento</label
                    >
                    <div class="input-group">
                      <input
                        id="coupon"
                        type="text"
                        class="form-control"
                        placeholder="Ingresa tu cupón"
                      />
                      <button
                        class="btn btn-success"
                        type="button"
                        id="btn-coupon"
                      >
                        Aplicar
                      </button>
                    </div>
                  </div>

                  <!-- Totales -->
                  <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between mb-1">
                      <span>Subtotal</span>
                      <span id="subtotal-cart">$152.29</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Descuento</span>
                      <span id="discount-cart">−$10.00</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Coupon Codes</span> <span id="cupon-cart"></span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Coupon Discounts</span>
                      <span id="cupon-discounts-cart"></span>
                    </li>
                    <li
                      class="d-flex justify-content-between border-top pt-2 fw-semibold"
                    >
                      <span>Total</span> <span id="total-cart">$147.28</span>
                    </li>
                  </ul>

                  <div class="d-none gap-2 d-lg-grid">
                    <a href="/Buke-Tours/checkout/" class="btn btn-dark btn-lg"
                      >Finalizar compra</a
                    >
                    <a href="/Buke-Tours/tours/" class="btn btn-danger-buke-tours"
                      >Seguir comprando</a
                    >
                  </div>
                </div>
              </div>
            </aside>
          </div>
        </div>
      </section>
    </main>
    <?php 
      include '../php/components/footer.php';
      include '../php/components/cart-modal.php';
      include '../php/scripts/common-scripts.php';
    ?>
    <script type="module" src="/Buke-Tours/assets/js/cart-page.js" defer></script>
  </body>
</html>


<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($html_lang, ENT_QUOTES, 'UTF-8'); ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $lang['cart_page_title']; ?></title>
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
    require_once '../config.php';
    ?>
    <main>
      <section id="cart" class="py-5">
        <div class="container bg-light container-content float-lg-none float-start">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="titulo h3 mb-0"><?php echo $lang['cart_brand_title']; ?></h1>
            <span class="badge text-bg-danger tours-added d-none d-lg-flex"><?php echo $lang['cart_items_badge']; ?></span>
          </div>

          <div class="row g-4">
            <div class="col-12 col-lg-8">
              <div class="list-group" id="cart-list-tours">
                <?php 
                    include '../php/components/skeletons/cart-tour-list-skeleton.php';
                ?>
              </div>

              <div class="d-grid d-lg-none mt-4 gap-2">
                <a href="/Buke-Tours/checkout/" class="btn btn-dark btn-lg">
                  <?php echo $lang['checkout_cta']; ?>
                </a>
                <a href="/Buke-Tours/tours/" class="btn btn-danger-buke-tours">
                  <?php echo $lang['continue_shopping']; ?>
                </a>
              </div>
            </div>
            <aside class="col-12 col-lg-4 resumen-del-pedido">
              <?php 
                    include '../php/components/skeletons/cart-summary-skeleton.php';
                ?>
              <div class="card shadow-sm sticky-lg-top d-none" id="summary-content" style="top: 1rem">
                <div class="card-body">
                  <h2 class="h6 mb-3"><?php echo $lang['order_summary']; ?></h2>

                  <div class="mb-3">
                    <label for="coupon" class="form-label small"
                      ><?php echo $lang['discount_coupon']; ?></label
                    >
                    <div class="input-group">
                      <input
                        id="coupon"
                        type="text"
                        class="form-control"
                        placeholder="<?php echo $lang['coupon_placeholder']; ?>"
                      />
                      <button
                        class="btn btn-success"
                        type="button"
                        id="btn-coupon"
                      >
                        <?php echo $lang['apply_coupon']; ?>
                      </button>
                    </div>
                  </div>

                  <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between mb-1">
                      <span><?php echo $lang['subtotal_label']; ?></span>
                      <span id="subtotal-cart">$152.29</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span><?php echo $lang['discount_label']; ?></span>
                      <span id="discount-cart">−$10.00</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span><?php echo $lang['coupon_codes_label']; ?></span> <span id="cupon-cart"></span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span><?php echo $lang['coupon_discounts_label']; ?></span>
                      <span id="cupon-discounts-cart"></span>
                    </li>
                    <li
                      class="d-flex justify-content-between border-top pt-2 fw-semibold"
                    >
                      <span><?php echo $lang['total_label']; ?></span> <span id="total-cart">$147.28</span>
                    </li>
                  </ul>

                  <div class="d-none gap-2 d-lg-grid">
                    <a href="/Buke-Tours/checkout/" class="btn btn-dark btn-lg">
                      <?php echo $lang['checkout_cta']; ?>
                    </a>
                    <a href="/Buke-Tours/tours/" class="btn btn-danger-buke-tours">
                      <?php echo $lang['continue_shopping']; ?>
                    </a>
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

<?php
/**
 * Contiene el Modal usado para el carrito de compras en todas las paginas
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div
  class="modal fade"
  id="cartModal"
  tabindex="-1"
  aria-labelledby="cartModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="cartModalLabel"><?php echo $lang['modal_cart_title']; ?></h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="<?php echo $lang['close_label']; ?>"
        ></button>
      </div>

      <div class="modal-body">
        <div class="list-group" id="cartList"></div>
        <div class="text-center py-4 d-none" id="emptyState">
          <p class="lead mb-2"><?php echo $lang['modal_cart_empty']; ?></p>
          <a href="/Buke-Tours/tours/" class="btn btn-outline-secondary"><?php echo $lang['modal_cart_explore']; ?></a>
        </div>
      </div>

      <div
        class="modal-footer flex-column flex-sm-row gap-2 justify-content-between"
      >
        <div class="ms-auto me-sm-3">
          <div class="text-muted small"><?php echo $lang['modal_cost_per_person']; ?></div>
          <div class="fs-5 fw-semibold" aria-live="polite">
            <span id="cartTotal"></span>
          </div>
        </div>
        <div class="d-flex gap-2">
          <a href="/Buke-Tours/checkout/" type="button" class="btn btn-danger-buke-tours">
            <?php echo $lang['modal_pay']; ?>
          </a>
          <a href="/Buke-Tours/cart/" class="btn btn-primary"><?php echo $lang['modal_go_to_cart']; ?></a>
        </div>
      </div>
    </div>
  </div>
</div>

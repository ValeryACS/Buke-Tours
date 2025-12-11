<?php
/**
 * Contiene el skeleton del resumen del pedido
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="card shadow-sm sticky-lg-top" id="cart-summary-payment" style="top: 1rem">
  <div class="card-body">
    <div class="skeleton-line mb-3" style="width: 40%; height: 18px"></div>
    <div class="mb-3">
      <div class="skeleton-line" style="width: 60%; height: 12px"></div>
      <div class="input-group mt-2">
        <div class="skeleton-line" style="height: 40px; width: 70%"></div>
        <div class="skeleton-btn" style="width: 70px"></div>
      </div>
    </div>
    <ul class="list-unstyled mb-4">
      <li class="d-flex justify-content-between mb-2">
        <div class="skeleton-line" style="width: 30%"></div>
        <div class="skeleton-line" style="width: 25%"></div>
      </li>
      <li class="d-flex justify-content-between mb-2">
        <div class="skeleton-line" style="width: 30%"></div>
        <div class="skeleton-line" style="width: 20%"></div>
      </li>
      <li class="d-flex justify-content-between mb-2">
        <div class="skeleton-line" style="width: 35%"></div>
        <div class="skeleton-line" style="width: 25%"></div>
      </li>
      <li class="d-flex justify-content-between mb-2">
        <div class="skeleton-line" style="width: 35%"></div>
        <div class="skeleton-line" style="width: 20%"></div>
      </li>
      <li class="d-flex justify-content-between border-top pt-2 fw-semibold">
        <div class="skeleton-line" style="width: 30%"></div>
        <div class="skeleton-line" style="width: 30%; height: 18px"></div>
      </li>
    </ul>
    <div class="d-grid gap-2">
      <div class="skeleton-line" style="height: 45px"></div>
      <div class="skeleton-line" style="height: 40px"></div>
    </div>
  </div>
</div>

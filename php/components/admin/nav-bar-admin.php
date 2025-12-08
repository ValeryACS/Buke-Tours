<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/Buke-Tours/">Buke Tours</a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navMain"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/Buke-Tours/admin/tours/">Tours</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Buke-Tours/admin/invoices/">Facturas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Buke-Tours/admin/customers/">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Buke-Tours/admin/admins/">Administradores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/Buke-Tours/admin/customers/">Reseñas</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

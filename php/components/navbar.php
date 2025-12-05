<?php
/**
 * Usado para renderizar el Menu
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
?>
<nav class="barra-navegadora navbar navbar-expand-lg">
  <div class="container-fluid">
    <button
      class="menu-hamburger navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <div id="nav-icon3">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav m-auto">
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/profile/" class="nav-link" title="<?php echo $lang['editar_perfil']; ?>"
            ><i class="bi bi-person-bounding-box display-6"></i
          ></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/" class="nav-link"><?php echo $lang['inicio'] ?? 'Inicio'; ?></a>
        </li>

        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/tours/" class="nav-link"><?php echo $lang['tours'] ?? 'Tours'; ?></a>
        </li>

        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/"
            >
            <img class="logo" src="/Buke-Tours/assets/img/logo.png" alt="Buke Tours Logo" title="Buke Tours Logo" />
        </a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/reviews/" class="nav-link"><?php echo $lang['reseñas'] ?? 'Reseñas'; ?></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/about-us/" class="nav-link"><?php echo $lang['sobre_nosotros'] ?? 'Sobre Nosotros'; ?></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/auth/login/" class="nav-link" title="<?php echo $lang['iniciar_sesion']; ?>"
            ><i class="bi bi-person-circle display-6"></i
          ></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/contact/" class="nav-link" title="<?php echo $lang['contacto']; ?>"><i class="display-6 bi bi-person-lines-fill"></i></a>
        </li>
          <li class="nav-item barra-navegadora-li d-flex align-items-center">
            <select id="language-switcher" class="form-select form-select-sm">
                <option value="es" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'es') echo 'selected'; ?>>Español</option>
                <option value="en" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') echo 'selected'; ?>>English</option>
            </select>
        </li>
      </ul>
    </div>

    <button
      id="shopping-cart"
      class="btn btn-light position-relative"
      type="button"
      data-bs-toggle="modal"
      data-bs-target="#cartModal"
    >
      <i class="bi bi-cart3 display-5"></i>
      <span
        id="quantity"
        class="d-none position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger quantity"
      >
      </span>
    </button>
  </div>
</nav>

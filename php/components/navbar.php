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
          <a href="/Buke-Tours/profile/" class="nav-link" title="Editar Perfil"
            ><i class="bi bi-person-bounding-box display-6"></i
          ></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/" class="nav-link">Inicio</a>
        </li>

        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/tours/" class="nav-link">Tours</a>
        </li>

        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/"
            >
            <img class="logo" src="/Buke-Tours/assets/img/logo.png" alt="Buke Tours Logo" title="Buke Tours Logo" />
        </a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/reviews/" class="nav-link">Reseñas</a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/about-us/" class="nav-link">Sobre Nosotros</a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/auth/login/" class="nav-link" title="Iniciar Session"
            ><i class="bi bi-person-circle display-6"></i
          ></a>
        </li>
        <li class="nav-item barra-navegadora-li">
          <a href="/Buke-Tours/contact/" class="nav-link" title="Contacto"><i class="display-6 bi bi-person-lines-fill"></i></a>
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

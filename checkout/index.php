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

$userID = isset($_SESSION['id'])? (int)$_SESSION['id']: 0;

if($userID<= 0){
    header("Location: ../auth/login/");
    exit();
}

include '../language/lang_' . $_SESSION['lang'] . '.php'; 

$html_lang = $_SESSION['lang'];
include '../php/helpers/get-country.php';

$html_lang = $_SESSION['lang']; 

$profileSession = [
    'nombre' => $_SESSION['nombre'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'telefono' => $_SESSION['telefono'] ?? '',
    'fecha_nacimiento' => $_SESSION['fecha_nacimiento'] ?? '',
    'pais' => $_SESSION['pais'] ?? '',
    'genero' => $_SESSION['genero'] ?? '',
    'idioma' => $_SESSION['idioma'] ?? '',
    'passport' => $_SESSION['passport'] ?? '',
    'direccion' => $_SESSION['direccion'] ?? '',
    'ciudad' => $_SESSION['ciudad'] ?? '',
    'provincia' => $_SESSION['provincia'] ?? '',
    'codigo_postal' => $_SESSION['codigo_postal'] ?? '',
];

$sessionValue = function (string $key) use ($profileSession): string {
    return htmlspecialchars((string)($profileSession[$key] ?? ''), ENT_QUOTES, 'UTF-8');
};

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulario de Compra</title>
    <?php 
      include '../php/styles/common-styles.php';
    ?>
    <link
      rel="stylesheet"
      href="/Buke-Tours/assets/css/checkout.css"
      type="text/css"
    />
    <link
      rel="stylesheet"
      href="/Buke-Tours/assets/css/checkout-skeleton.css"
      type="text/css"
    />
  </head>
  <body>
    <?php
    require_once '../config.php';
    ?>
    <main>
      <section id="checkout" class="py-5">
        <div
          class="container bg-light container-content float-lg-none float-start"
        >
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="titulo h3 mb-0">Formulario de Compra</h1>
            <span class="badge text-bg-danger tours-added d-none d-lg-flex"
              >3 artículos</span
            >
          </div>
          <div class="row g-4">
            <article
              id="empty-checkout-cart"
              class="col-12 col-lg-8 d-flex flex-column justify-content-center align-items-center d-none"
            >
              <div class="text-center text-muted p-4">
                Tu carrito está vacío.
              </div>
              <a
                href="/Buke-Tours/tours/"
                class="btn btn-danger-buke-tours m-auto"
                >Comprar Tours</a
              >
            </article>
            <article
              class="col-12 col-lg-8 skeleton-wrapper"
              id="checkout-form-skeleton"
            >
              <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                  <div
                    class="mb-3 d-flex justify-content-between align-items-center"
                  >
                    <div
                      class="skeleton-line skeleton"
                      style="width: 40%; height: 22px"
                    ></div>
                    <span
                      class="badge skeleton skeleton-pill d-none d-lg-inline-block"
                    ></span>
                  </div>

                  <div class="accordion">
                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                      <div class="accordion-body pt-3">
                        <div class="row g-3">
                          <div class="col-12 col-md-6">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 60%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-12 col-md-6">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 70%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-12 col-md-6">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 55%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-12 col-md-6">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 65%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                    </div>

                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                    </div>

                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                    </div>

                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                    </div>

                    <div class="accordion-item mb-2">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button collapsed skeleton-accordion-btn skeleton"
                          type="button"
                          disabled
                        ></button>
                      </h2>
                      <div class="accordion-body pt-3">
                        <div class="row g-3">
                          <div class="col-12">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 70%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-12">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 60%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 40%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 40%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                          <div class="col-12 col-md-4">
                            <div
                              class="skeleton-line skeleton mb-2"
                              style="width: 30%"
                            ></div>
                            <div class="skeleton-input skeleton"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </article>
            <article
              class="col-12 col-lg-8 d-none"
              id="checkout-article-container"
            >
              <div id="checkout-form" class="card shadow-sm border-0">
                <div class="card-body p-4">
                  <div class="accordion" id="accordionCheckout">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingContacto">
                        <button
                          class="accordion-button"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseContacto"
                          aria-expanded="true"
                          aria-controls="collapseContacto"
                        >
                          Datos de contacto
                        </button>
                      </h2>
                      <div
                        id="collapseContacto"
                        class="accordion-collapse collapse show"
                        aria-labelledby="headingContacto"
                        data-bs-parent="#accordionCheckout"
                      >
                        <div class="accordion-body">
                          <div class="row g-3 text-start">
                            <div class="col-12 col-md-6 form-group">
                              <label for="nombre" class="form-label"
                                >Nombre completo</label
                              >
                              <input
                                id="nombre"
                                name="nombre"
                                type="text"
                                class="form-control"
                                placeholder="Ej: Ana Rodríguez"
                                autocomplete="name"
                                maxlength="50"
                                value="<?php 
                                if(isset($_SESSION['nombre'])){
                                  echo $_SESSION['nombre'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-12 col-md-6 form-group">
                              <label for="email" class="form-label"
                                >Correo electrónico</label
                              >
                              <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control"
                                placeholder="ejemplo@correo.com"
                                autocomplete="email"
                                maxlength="90"
                                value="<?php 
                                if(isset($_SESSION['email'])){
                                  echo $_SESSION['email'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-12 col-md-6 form-group">
                              <label for="telefono" class="form-label"
                                >Teléfono</label
                              >
                              <input
                                id="telefono"
                                name="telefono"
                                type="tel"
                                class="form-control"
                                placeholder="8888-8888"
                                autocomplete="tel"
                                maxlength="15"
                                value="<?php 
                                if(isset($_SESSION['telefono'])){
                                  echo $_SESSION['telefono'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-12 col-md-6 form-group">
                              <span
                                id="flagCountry"
                                class="fi d-none"
                                aria-hidden="true"
                              ></span>
                              <label for="pais" class="form-label"
                                >País de residencia</label
                              >
                              <input type="hidden" name="country-value" readonly value="<?php 
                                if(isset($_SESSION['pais'])){
                                  echo $_SESSION['pais'];
                                }
                                ?>"/>
                              <select
                                id="pais"
                                name="pais"
                                class="form-select"
                                autocomplete="country-name"
                              >
                               <?php 
                                echo getCountrySelected($sessionValue('pais'));
                                ?>
                              </select>
                              </select>
                            </div>
                            <div class="col-12 col-md-6 form-group text-start">
                              <label for="documento" class="form-label"
                                >Cédula / Pasaporte</label
                              >
                              <input
                                id="documento"
                                name="documento"
                                type="text"
                                class="form-control"
                                placeholder="Número de Cédula"
                                maxlength="20"
                                value="<?php 
                                if(isset($_SESSION['passport'])){
                                  echo $_SESSION['passport'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-12 col-md-6 form-group text-start">
                              <label for="idioma" class="form-label"
                                >Idioma</label
                              >
                              <select
                                id="idioma"
                                name="idioma"
                                class="form-select"
                                aria-label="Idioma Seleccionado"
                              >
                                <option selected value="no-seleccionado">
                                  Seleccione un Idioma
                                </option>
                                <option value="en" <?php 
                                if(isset($_SESSION['idioma']) && $_SESSION['idioma'] ==='en'){
                                  echo "selected";
                                }
                                ?> >Ingles</option>
                                <option value="es" <?php 
                                if(isset($_SESSION['idioma']) && $_SESSION['idioma'] ==='es'){
                                  echo "selected";
                                }
                                ?>>Español</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingTour">
                        <button
                          class="accordion-button collapsed"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseTour"
                          aria-expanded="false"
                          aria-controls="collapseTour"
                        >
                          Fechas de Ingreso y Salida
                        </button>
                      </h2>
                      <div
                        id="collapseTour"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingTour"
                        data-bs-parent="#accordionCheckout"
                      >
                        <div class="accordion-body">
                          <div
                            class="accordion"
                            id="accordionCheckoutTourList"
                          ></div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingExtras">
                        <button
                          class="accordion-button collapsed"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseExtras"
                          aria-expanded="false"
                          aria-controls="collapseExtras"
                        >
                          Extras opcionales
                        </button>
                      </h2>
                      <div
                        id="collapseExtras"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingExtras"
                        data-bs-parent="#accordionCheckout"
                      >
                        <div class="accordion-body">
                          <h6 class="mb-2">Costo por persona:</h6>
                          <div class="row g-3">
                            <div class="col-12 col-md-6">
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="desayuno"
                                  name="desayuno"
                                />
                                <label class="form-check-label" for="desayuno"
                                  >Desayuno incluido (+$11.00)</label
                                >
                              </div>
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="almuerzo"
                                  name="almuerzo"
                                />
                                <label class="form-check-label" for="almuerzo"
                                  >Almuerzo incluido (+$12.00)</label
                                >
                              </div>
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="cena"
                                  name="cena"
                                />
                                <label class="form-check-label" for="cena"
                                  >Cena incluida (+$17.00)</label
                                >
                              </div>
                            </div>
                            <div class="col-12 col-md-6">
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="transporte"
                                  name="transporte"
                                />
                                <label class="form-check-label" for="transporte"
                                  >Transporte(+$30.00)</label
                                >
                              </div>
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="seguro"
                                  name="seguro"
                                />
                                <label class="form-check-label" for="seguro"
                                  >Seguro de viaje (+$9.00)</label
                                >
                              </div>
                              <div class="form-check text-start">
                                <input
                                  class="form-check-input"
                                  type="checkbox"
                                  id="fotos"
                                  name="fotos"
                                />
                                <label class="form-check-label" for="fotos"
                                  >Paquete de fotografías (+$15.00)</label
                                >
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingFactura">
                        <button
                          class="accordion-button collapsed"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseFactura"
                          aria-expanded="false"
                          aria-controls="collapseFactura"
                        >
                          Datos de facturación
                        </button>
                      </h2>
                      <div
                        id="collapseFactura"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingFactura"
                        data-bs-parent="#accordionCheckout"
                      >
                        <div class="accordion-body">
                          <div class="row g-3">
                            <div class="col-12 text-start">
                              <label for="direccion" class="form-label"
                                >Dirección</label
                              >
                              <input
                                id="direccion"
                                name="direccion"
                                type="text"
                                class="form-control"
                                placeholder="Calle, número, apartamento"
                                autocomplete="street-address"
                                maxlength="200"
                                value="<?php 
                                if(isset($_SESSION['direccion'])){
                                  echo $_SESSION['direccion'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-12 col-md-6">
                              <label for="ciudad" class="form-label"
                                >Ciudad</label
                              >
                              <input
                                id="ciudad"
                                name="ciudad"
                                type="text"
                                class="form-control"
                                maxlength="50"
                                value="<?php 
                                if(isset($_SESSION['ciudad'])){
                                  echo $_SESSION['ciudad'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-6 col-md-3">
                              <label for="provincia" class="form-label"
                                >Provincia</label
                              >
                              <input
                                id="provincia"
                                name="provincia"
                                type="text"
                                class="form-control"
                                maxlength="60"
                                value="<?php 
                                if(isset($_SESSION['provincia'])){
                                  echo $_SESSION['provincia'];
                                }
                                ?>"
                              />
                            </div>
                            <div class="col-6 col-md-3">
                              <label for="zip" class="form-label"
                                >Código Postal</label
                              >
                              <input
                                id="zip"
                                name="zip"
                                type="text"
                                class="form-control"
                                autocomplete="postal-code"
                                maxlength="30"
                                value="<?php 
                                if(isset($_SESSION['codigo_postal'])){
                                  echo $_SESSION['codigo_postal'];
                                }
                                ?>"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingPago">
                        <button
                          class="accordion-button collapsed"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapsePago"
                          aria-expanded="false"
                          aria-controls="collapsePago"
                        >
                          Tarjeta de crédito / débito
                        </button>
                      </h2>
                      <div
                        id="collapsePago"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingPago"
                        data-bs-parent="#accordionCheckout"
                      >
                        <div class="accordion-body">
                          <div
                            id="pagoTarjeta"
                            class="accordion-collapse collapse show"
                            aria-labelledby="headingCard"
                            data-bs-parent="#accordionPago"
                          >
                            <div class="accordion-body">
                              <div class="row g-3">
                                <div class="col-12 text-start form-group">
                                  <label for="cardName" class="form-label"
                                    >Nombre del Titular de la tarjeta</label
                                  >
                                  <input
                                    id="cardName"
                                    name="cardName"
                                    type="text"
                                    class="form-control"
                                    autocomplete="cc-name"
                                    maxlength="80"
                                  />
                                </div>
                                <div class="col-12 text-start form-group">
                                  <label for="cardNumber" class="form-label"
                                    >Número de tarjeta</label
                                  >
                                  <input
                                    id="cardNumber"
                                    name="cardNumber"
                                    type="text"
                                    inputmode="numeric"
                                    class="form-control"
                                    placeholder="1234 5678 9012 3456"
                                    autocomplete="cc-number"
                                    maxlength="19"
                                  />
                                </div>
                                <div class="col-6 col-md-4 form-group">
                                  <label for="cardMonth" class="form-label"
                                    >Mes</label
                                  >
                                  <input
                                    id="cardMonth"
                                    name="cardMonth"
                                    type="text"
                                    step="01"
                                    class="form-control"
                                    placeholder="MM"
                                    inputmode="numeric"
                                    autocomplete="cc-exp-month"
                                    maxlength="2"
                                  />
                                </div>
                                <div class="col-6 col-md-4 form-group">
                                  <label for="cardYear" class="form-label"
                                    >Año</label
                                  >
                                  <input
                                    id="cardYear"
                                    name="cardYear"
                                    type="text"
                                    class="form-control"
                                    placeholder="YY"
                                    inputmode="numeric"
                                    autocomplete="cc-exp-year"
                                    maxlength="2"
                                  />
                                </div>
                                <div class="col-12 col-md-4 form-group">
                                  <label for="cardCvv" class="form-label"
                                    >CVV</label
                                  >
                                  <input
                                    id="cardCvv"
                                    name="cardCvv"
                                    type="password"
                                    class="form-control"
                                    placeholder="***"
                                    inputmode="numeric"
                                    autocomplete="cc-csc"
                                    maxlength="4"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </article>

            <aside class="col-12 col-lg-4 resumen-del-pedido d-none">
              <div class="card shadow-sm sticky-lg-top" style="top: 1rem">
                <div class="card-body">
                  <h2 class="h6 mb-3">Resumen del pedido</h2>
                  <div class="mb-3">
                    <label for="coupon" class="form-label small"
                      >Cupón de descuento</label
                    >
                    <div class="input-group">
                      <input
                        id="coupon"
                        name="coupon"
                        type="text"
                        class="form-control"
                        placeholder="Código de cupón"
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

                  <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between mb-1">
                      <span>D&iacute;as</span>
                      <span id="dias-cart">x1</span>
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
                    <li class="d-flex justify-content-between mb-1">
                      <span>Adultos</span>
                      <span id="adultos-sidebar-cart">1</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Niños</span>
                      <span id="children-sidebar-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <h3>Extras:</h3>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Desayunos</span><span id="breakfast-cart"> 0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Almuerzos</span><span id="launch-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Cena</span><span id="diner-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Transporte</span><span id="transport-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Seguro</span><span id="security-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <span>Fotografias</span><span id="photos-cart">0</span>
                    </li>
                    <li class="d-flex justify-content-between mb-1">
                      <h4>Subtotal</h4>
                      <span id="subtotal-cart">$152.29</span>
                      <input
                        type="number"
                        hidden
                        readonly
                        id="subtotal"
                        name="subtotal"
                        class="d-none"
                      />
                    </li>
                    <li
                      class="d-flex justify-content-between border-top pt-2 fw-semibold"
                    >
                      <h2>Total</h2>
                      <span id="total-cart">$147.28</span>
                      <input
                        type="number"
                        hidden
                        readonly
                        id="total"
                        name="total"
                        class="d-none"
                      />
                    </li>
                    <li>
                      <div class="form-check mb-3 mt-4 text-start">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          name="terms"
                          value=""
                          id="terms"
                        />
                        <label class="form-check-label" for="terms">
                          Acepto los
                          <a href="#terminos-y-condiciones"
                            >términos y condiciones</a
                          >
                          .
                        </label>
                      </div>
                    </li>
                  </ul>

                  <div class="d-grid">
                    <button
                      id="btn-pay-with-paypal"
                      type="button"
                      class="btn btn-secondary btn-lg mb-3 mt-3"
                    >
                      <i class="bi bi-paypal"></i>
                      Pagar con Paypal
                    </button>
                    <button
                      class="btn btn-dark btn-lg"
                      type="button"
                      id="pagar-con-tarjeta-btn"
                    >
                      <i class="bi bi-credit-card"></i>Pagar con Tarjeta
                    </button>
                  </div>
                  <p class="small text-muted mt-3 mb-0">
                    Recibirás el **voucher** y la confirmación por correo
                    electrónico.
                  </p>
                </div>
              </div>
            </aside>

            <aside
              class="col-12 col-lg-4 skeleton-wrapper"
              id="checkout-summary-skeleton"
            >
              <div class="card shadow-sm sticky-lg-top" style="top: 1rem">
                <div class="card-body">
                  <div
                    class="skeleton-line skeleton mb-3"
                    style="width: 40%; height: 18px"
                  ></div>

                  <div class="mb-3">
                    <div
                      class="skeleton-line skeleton mb-2"
                      style="width: 55%"
                    ></div>
                    <div class="input-group">
                      <div
                        class="skeleton-input skeleton"
                        style="border-radius: 0.375rem 0 0 0.375rem"
                      ></div>
                      <div
                        class="skeleton-btn-lg skeleton"
                        style="
                          width: 90px;
                          height: 40px;
                          border-radius: 0 0.375rem 0.375rem 0;
                        "
                      ></div>
                    </div>
                  </div>

                  <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 30%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 25%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 40%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 20%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 45%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 20%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 30%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 15%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 25%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 15%"
                      ></span>
                    </li>

                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 30%; height: 16px"
                      ></span>
                    </li>

                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 45%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 10%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 45%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 10%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 45%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 10%"
                      ></span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                      <span
                        class="skeleton-line skeleton"
                        style="width: 45%"
                      ></span>
                      <span
                        class="skeleton-line skeleton"
                        style="width: 10%"
                      ></span>
                    </li>
                  </ul>

                  <div class="d-flex justify-content-between mb-2">
                    <div
                      class="skeleton-line skeleton"
                      style="width: 35%; height: 16px"
                    ></div>
                    <div
                      class="skeleton-line skeleton"
                      style="width: 25%; height: 16px"
                    ></div>
                  </div>

                  <div
                    class="d-flex justify-content-between border-top pt-2 mb-3"
                  >
                    <div
                      class="skeleton-line skeleton"
                      style="width: 30%; height: 20px"
                    ></div>
                    <div
                      class="skeleton-line skeleton"
                      style="width: 30%; height: 20px"
                    ></div>
                  </div>

                  <div class="d-flex align-items-center mb-3">
                    <div
                      class="skeleton skeleton"
                      style="width: 18px; height: 18px; border-radius: 0.25rem"
                    ></div>
                    <div
                      class="skeleton-line skeleton ms-2"
                      style="width: 70%"
                    ></div>
                  </div>

                 <div class="d-grid">
                    <div class="skeleton-btn-lg skeleton mb-3"></div>
                    <div class="skeleton-btn-lg skeleton"></div>
                  </div>

                  <div
                    class="skeleton-line skeleton mt-3"
                    style="width: 90%"
                  ></div>
                  <div
                    class="skeleton-line skeleton mt-1"
                    style="width: 60%"
                  ></div>
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
      include '../php/scripts/checkout-scripts.php';
    ?>
  </body>
</html>

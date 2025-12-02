import {setTourDetailsForm} from './checkout-module.js';

/**
 * @function
 * Calcula y actualiza el total del carrito en el elemento con id="cartTotal"
 * - Subtotal: suma de (qty * price)
 * - Descuento por tour: suma por ítem (más preciso que sumar %)
 * - Cupones: solo aplica los cupones cuyo tour está en el carrito
 * @returns {Promise<void>} - Actualiza el total tanto en el Modal como en el Carrito asi como tambien en el Formulario de Checkout
 */
export const updateCartTotal = async () => {
  const cartTotalModalElement = document.getElementById("cartTotal"); //El total del modal
  const subTotalSidebarElement = document.getElementById("subtotal-cart"); // El SubTotal del sidebar de resumen
  const discountSidebarElement = document.getElementById("discount-cart"); // El Descuento del sidebar de resumen
  const totalSidebarElement = document.getElementById("total-cart"); // El Total del sidebar de resumen
  const cuponInputElement = document.getElementById("cupon-cart"); // El Input del Coupon del sidebar de resumen
  const cuponDiscountEl = document.getElementById("cupon-discounts-cart"); //El coupon del sidebar de resumen
  const totalElement = document.getElementById("total"); // El Input Total de tipo number
  const subTotalInputElement = document.getElementById("subtotal"); //El Input del subtotal

  const existCartData = localStorage.getItem("cart");
  if (!existCartData) {
    const zero = "$0.00";
    if (cartTotalModalElement) {
      cartTotalModalElement.textContent = zero;
    }
    if (subTotalSidebarElement) {
      subTotalSidebarElement.textContent = zero;
    }
    if (totalSidebarElement) {
      totalSidebarElement.textContent = zero;
    }
    if (discountSidebarElement) {
      discountSidebarElement.textContent = "-0%";
    }
    if (cuponInputElement) {
      cuponInputElement.innerHTML = `<span class="badge text-bg-danger">No</span>`;
    }
    if (totalElement) {
      totalElement.value = 0;
    }
    if (subTotalInputElement) {
      subTotalInputElement.value = 0;
    }
    if (cuponDiscountEl) cuponDiscountEl.textContent = "-0%";
    return;
  }

  try {
    const res = await fetch("/Buke-Tours/api/tours/");
    if (!res.ok) {
      throw new Error("Error al cargar tours.json");
    }
    const { data } = await res.json();

    // Subtotal y descuento por tour (en dólares, por ítem)
    const { subtotal,totalOfDiscounts } =
      getSubTotalAndDiscounts(data);

    const subtotalFmt = `$${subtotal}`;
    if (subTotalSidebarElement) {
      subTotalSidebarElement.textContent = subtotalFmt;
    }
    
    const itemDiscountPctEffective = totalOfDiscounts.reduce(
        (prevValue, currentValue) => prevValue + currentValue,
        0
      )
    if (discountSidebarElement) {
      discountSidebarElement.textContent = `-${itemDiscountPctEffective}%`;
    }
    // Aplicar cupones válidos (solo si su tour está en el carrito)
    const { totalCouponsPct, codesApplied } = getCoupons(data);

    const { finalTotal, finalFmt } = getTotal({
      subtotal,
      itemDiscountDollars: Number((subtotal * itemDiscountPctEffective) / 100),
      totalCouponsPct,
    });
    if (totalSidebarElement) {
      totalSidebarElement.textContent = finalFmt;
    }
    if (cartTotalModalElement) {
      cartTotalModalElement.textContent = finalFmt;
    }
    if (totalElement) {
      totalElement.value = finalTotal;
    }
    if (subTotalInputElement) {
      subTotalInputElement.value = subtotal;
    }
    if (cuponInputElement) {
      cuponInputElement.innerHTML = codesApplied.length
        ? codesApplied.join(", ")
        : `<span class="badge text-bg-danger">No</span>`;
    }
    if (cuponDiscountEl) {
      cuponDiscountEl.textContent = `-${totalCouponsPct || 0}%`;
    }
  } catch (error) {
    console.error("Error al calcular el total:", error);
  }
};

/**
 * @function
 * Lee los cupones del localStorage como objeto { [cupon_code]: cupon_discount }
 * @returns {Object} - El JSON con los cupones o un objeto vacio
 */
export const readCupons = () => {
  try {
    return JSON.parse(localStorage.getItem("cupons") || "{}");
  } catch {
    return {};
  }
};

/**
 * @function
 * Guarda los Cupones en el localStorage
 * @returns {void}
 */
export const saveCupon = (cuponObj) => {
  localStorage.setItem("cupons", JSON.stringify(cuponObj));
};

/**
 * @function
 * Lee el carrito del localStorage como objeto { [sku]: qty }
 * @returns {Object}
 */
export const readCart = () => {
  try {
    return JSON.parse(localStorage.getItem("cart") || "{}");
  } catch {
    return {};
  }
};

/**
 * @function
 * Guarda el carrito
 * @returns {void}
 */
export const saveCart = (cartObj) => {
  localStorage.setItem("cart", JSON.stringify(cartObj));
};

/**
 * Actualiza los items adentro del Modal del carrito
 * @param {Object} cartObj - Objeto { [sku]: qty }
 * @returns {Promise<void>} - Se basa en los tours que hay guardados en el localStorage para luego obbtener sus datos y renderezarlos
 */
export const updateCartModal = async (cartObj) => {
  const cartList = document.getElementById("cartList");
  if (!cartList) return;

  const ids = Object.keys(cartObj || {});
  if (!ids.length) {
    document.querySelector(".modal-footer")?.classList?.add("d-none");
    document.querySelector(".resumen-del-pedido")?.classList?.add("d-none");
    document.querySelector("#empty-checkout-cart")?.classList?.remove("d-none");
    document
      .querySelector("#checkout-article-container")
      ?.classList.add("d-none");
    document
      .querySelector("#checkout-summary-skeleton")
      ?.classList?.add("d-none");
    document.querySelector("#checkout-form-skeleton")?.classList.add("d-none");
    cartList.innerHTML = `
          <div class="text-center text-muted p-4">Tu carrito está vacío.</div>
          <a href="/Buke-Tours/tours/" class="btn btn-danger m-auto">Comprar Tours</a>
        `;
    return;
  }
  document.querySelector(".modal-footer")?.classList?.remove("d-none");
  document.querySelector(".resumen-del-pedido")?.classList?.remove("d-none");
  document.querySelector("#empty-checkout-cart")?.classList?.add("d-none");
  document
    .querySelector("#checkout-article-container")
    ?.classList.remove("d-none");
  document.querySelector("#checkout-form-skeleton")?.classList.add("d-none");
  await fetch("/Buke-Tours/api/tours/")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then(({ data }) => {
      // Para cada sku del carrito busca el tour en el JSON
      const output = ids
        .map((sku) => {
          const tour = data.find((t) => String(t.sku) === String(sku));
          if (!tour) return ""; // si no existe en el JSON, sáltalo

          const qty = Number(cartObj[sku]) || 0;
          const price = Number(tour.price_usd) || 0;
          const subtotal = (qty * price).toFixed(2);

          return `
                <aside class="list-group-item d-flex flex-column" data-tour-id="${
                  tour.sku
                }">
                  <div class="row g-3 align-items-start d-flex flex-column flex-lg-row justify-content-md-start align-items-md-start">
                    <div class="col-4 col-sm-3">
                      <img
                        src="${tour.img}" alt="${tour.title}"
                        class="img-fluid rounded"
                        loading="lazy"
                      />
                    </div>
                    <div class="col-8 col-sm-9">
                      <div class="d-flex justify-content-between">
                        <div>
                        <h1 class="h4 mb-1">${tour.title}</h1>
                          <h3 class="h6 mb-1">${tour.location}</h3>
                          <p class="text-muted small mb-2">${
                            tour.description
                          }</p>
                        </div>
                        <div class="text-end d-none d-sm-block">
                          <div class="small text-muted">Precio</div>
                          <div class="fw-semibold">
                            $<span class="price">${price.toFixed(2)}</span>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-2 g-2">
                        <div class="col-8 col-md-6">
                          <div class="input-group">
                            <button
                              class="btn btn-danger btn-qty btn-substract-quantity"
                              type="button"
                              data-action="minus"
                              data-tour-id="${tour.sku}"
                              aria-label="Disminuir cantidad"
                            >
                              −
                            </button>
                            <input
                              type="number"
                              class="form-control text-center input-qty"
                              value="${qty}"
                              min="1"
                              inputmode="numeric"
                              aria-label="Cantidad"
                              data-tour-id="${tour.sku}"
                              name="quantity-${tour.sku}"
                              id="quantity-${tour.sku}"
                            />
                            <button
                              class="btn btn-primary btn-qty btn-add-quantity"
                              type="button"
                              data-action="plus"
                              data-tour-id="${tour.sku}"
                              aria-label="Aumentar cantidad"
                            >
                              +
                            </button>
                          </div>
                        </div>
                        <div class="col-4 col-md-6 text-end">
                          <div class="small text-muted">Subtotal</div>
                          <div class="fw-semibold">
                            $<span class="item-subtotal">${subtotal}</span>
                          </div>
                          <button
                            class="btn btn-link p-0 small text-danger btn-remove"
                            type="button"
                            data-tour-id="${tour.sku}"
                          >
                            <i class="bi bi-trash3-fill display-6"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </aside>`;
        })
        .join("");
      document
        .querySelector("#checkout-summary-skeleton")
        ?.classList?.add("d-none");
      document
        .querySelector("#checkout-form-skeleton")
        ?.classList.add("d-none");
      cartList.innerHTML = output;

      // Re-vincular eventos de + / − / input / eliminar
      attachCartItemEvents();
    })
    .catch((err) => console.error("Error:", err));
};

/**
 * @function
 * Re-vincula los eventos para los elementos del modal
 * @returns {void} - Setea los eventos de los inputs del modal
 */
export const attachCartItemEvents = () => {
  // +
  document.querySelectorAll(".btn-add-quantity").forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      const sku = e.currentTarget.getAttribute("data-tour-id");
      await changeQty(sku, +1);
    });
  });

  // −
  document.querySelectorAll(".btn-substract-quantity").forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      const sku = e.currentTarget.getAttribute("data-tour-id");
      await changeQty(sku, -1);
    });
  });

  // input directo
  document.querySelectorAll(".input-qty").forEach((input) => {
    input.addEventListener("change", (e) => {
      const sku = e.currentTarget.getAttribute("data-tour-id");
      const val = Math.max(1, Number(e.currentTarget.value) || 1);
      setQty(sku, val);
    });
  });

  // eliminar
  document.querySelectorAll(".btn-remove").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const sku = e.currentTarget.getAttribute("data-tour-id");
      removeFromCart(sku);
    });
  });
};

/**
 * @function
 * Permite actualizar la cantidad total (badge del carrito)
 * @returns {void} - Setea la cantidad total de productos
 */
export const updateCartQuantity = () => {
  const quantityEl = document.getElementById("quantity");
  const toursAdded = document.querySelector(".tours-added");
  if (!quantityEl) return;

  const cartObj = readCart();
  const total = Object.values(cartObj).reduce(
    (acc, val) => acc + Number(val || 0),
    0
  );

  if (toursAdded) {
    toursAdded.textContent = total + " tours";
    toursAdded.classList.toggle("d-none", total <= 0);
  }

  quantityEl.textContent = total;
  quantityEl.classList.toggle("d-none", total <= 0);
};

/**
 * @function
 * Cambia la cantidad en ±1, con mínimo 1
 * @returns {Promise<void>}
 */
export const changeQty = async (sku, delta) => {
  const cart = readCart();
  const curr = Number(cart[sku] || 0) + delta;
  if (curr <= 0) {
    delete cart[sku];
  } else {
    cart[sku] = curr;
  }
  saveCart(cart);
  await updateCartModal(cart);
  updateCartQuantity();
  await updateCartTotal();
  await updateBasket();
  await setTourDetailsForm()
};

/**
 * @function
 * Fija la cantidad a un valor específico (mínimo 1)
 * @returns {Promise<void>}
 */
export const setQty = async (sku, qty) => {
  const cart = readCart();
  if (qty <= 0) {
    delete cart[sku];
  } else {
    cart[sku] = qty;
  }
  saveCart(cart);
  await updateCartModal(cart);
  updateCartQuantity();
  await updateCartTotal();
  await updateBasket();
  await setTourDetailsForm()
};

/**
 * @function
 * Elimina un tour del carrito
 * @returns {Promise<void>}
 */
export const removeFromCart = async (sku) => {
  const cart = readCart();
  delete cart[sku];
  saveCart(cart);
  await updateCartModal(cart);
  updateCartQuantity();
  await updateCartTotal();
  await updateBasket();
  await setTourDetailsForm()
};

/**
 * Agrega un Tour al Carrito
 * @param {string} sku - sku del Tour
 * @returns {Promise<void>} - Consulta los datos respectivos al ID del Tour recibido
 */
export const onAddTourToCart = async (sku) => {
  const cart = readCart();
  cart[sku] = (Number(cart[sku]) || 0) + 1;
  saveCart(cart);

  await updateCartModal(cart);
  updateCartQuantity();
  await updateCartTotal();
  await setTourDetailsForm()
};

/**
 * Actualiza todos los tours incluidos en el carrito de compras para la pagina del carrito (no es el modal)
 * @returns {Promise<void>} - Usado para renderizar los tours incluidos en el carrito
 **/
export const updateBasket = async () => {
  const cart = readCart();
  const cartList = document.getElementById("cart-list-tours");
  if (!cartList) return;

  const ids = Object.keys(cart || {});
  if (ids.length === 0) {
    cartList.innerHTML = `
          <div class="text-center text-muted p-4">Tu carrito está vacío.</div>
          <a href="/Buke-Tours/tours/" class="btn btn-danger m-auto">Comprar Tours</a>
        `;
    return;
  }

  await fetch("/Buke-Tours/api/tours/")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then(({ data }) => {
      // Para cada sku del carrito busca el tour en el JSON
      const output = ids
        .map((sku) => {
          const tour = data.find((t) => String(t.sku) === String(sku));
          if (!tour) return ""; // si no existe en el JSON, sáltalo

          const qty = Number(cart[sku]) || 0;
          const price = Number(tour.price_usd) || 0;
          const subtotal = (qty * price).toFixed(2);

          return `
            <article class="list-group-item p-3" data-tour-id="${tour.sku}">
                  <div class="row g-3 align-items-center">
                    <div class="col-4 col-sm-3">
                      <img
                        src="${tour.img}" alt="${tour.title}"
                        class="img-fluid rounded" 
                      />
                    </div>
                    <div class="col-8 col-sm-9">
                      <div class="d-flex justify-content-between">
                        <div>
                        <h1 class="h4 mb-1">${tour.title}</h1>
                          <h2 class="h6 mb-1">${tour.location}</h2>
                          <p class="text-muted small mb-2">
                            ${tour.description}
                          </p>
                          <div class="d-flex gap-2">
                            <button class="btn btn-link p-0 text-danger">
                              <i class="bi bi-trash3-fill display-6 btn-remove" data-tour-id="${
                                tour.sku
                              }"></i>
                            </button>
                            <button class="btn btn-link p-0">
                              <i class="bi bi-bookmark-star-fill display-6"></i>
                            </button>
                          </div>
                        </div>
                        <div class="text-end d-none d-sm-block">
                          <div class="fw-semibold">$${String(
                            tour.price_usd
                          ).toLocaleString("es-CR")}</div>
                          <div class="text-muted small">SKU: ${tour.sku}</div>
                        </div>
                      </div>

                      <div class="row mt-3 g-2">
                        <div class="col-8 col-sm-6 col-md-5">
                          <label class="form-label small mb-1" for="qty-tour-${
                            tour.sku
                          }"
                            >Cantidad</label
                          >
                          <div class="input-group">
                            <button
                              class="btn btn-danger btn-qty btn-substract-quantity"
                              type="button"
                              data-action="minus"
                              data-tour-id="${tour.sku}"
                              aria-label="Disminuir cantidad"
                            >
                              −
                            </button>
                            <input
                              id="qty-tour-${tour.sku}"
                              name="qty-tour-${tour.sku}"
                              type="number"
                              class="form-control text-center input-qty"
                              value="${qty}"
                              min="1"
                              inputmode="numeric"
                               data-tour-id="${tour.sku}"
                            />
                            <button
                              class="btn btn-primary btn-qty btn-add-quantity"
                              type="button"
                              aria-label="Aumentar"
                              data-action="plus"
                              data-tour-id="${tour.sku}"
                            >
                              +
                            </button>
                          </div>
                        </div>
                        <div class="col-4 col-sm-6 col-md-7 text-end">
                          <div class="small text-muted">Subtotal</div>
                          <div class="fw-semibold">$${String(
                            subtotal
                          ).toLocaleString("es-CR")}</div>
                        </div>
                      </div>
                    </div>
                  </div>
            </article>`;
        })
        .join("");

      cartList.innerHTML = output;

      attachCartItemEvents(); // Re-vincula los eventos de los inputs agregados al DOM
    })
    .catch((err) => console.error("Error:", err));
};
/**
 * Valida si el Coupon corresponde a uno de los Tours existentes
 * @param {string} coupon - El Coupon a validar
 * @returns {Promise<void>} - Consulta el id del coupon recibido por parametro para luego compararlo con los tours
 */
export const validateCoupon = async (cupon_code) => {
  const code = String(cupon_code || "")
    .trim()
    .toUpperCase();
  if (!code) {
    Swal.fire({
      icon: "error",
      title: "Cupón vacío",
      text: "Por favor ingresa un código de cupón.",
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
    });
    return;
  }

  const cart = readCart();
  const cartIds = new Set(Object.keys(cart || {})); // Crea una coleccion de Ids unicos basado en las keys que hay almacenadas en el carrito

  try {
    const res = await fetch("/Buke-Tours/api/tours/");
    if (!res.ok) throw new Error("Error al cargar el JSON");
    const { data } = await res.json();

    // Busca el tour por código de cupón (case-insensitive)
    const tourByCoupon = data.find(
      (t) =>
        String(t.cupon_code || "")
          .trim()
          .toUpperCase() === code
    );

    // 1) No existe un tour con ese cupón
    if (!tourByCoupon) {
      Swal.fire({
        icon: "error",
        title: "Cupón Incorrecto",
        text: "El código ingresado no corresponde a ningún tour.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
      });
      return;
    }

    // 2) El tour del cupón NO está en el carrito
    if (!cartIds.has(String(tourByCoupon.sku))) {
      Swal.fire({
        icon: "error",
        title: "Cupón no aplicable",
        text: `Este cupón no pertenece a ningun tour de tu carrito.`,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
      });
      return;
    }

    // 3) Evitar canjear dos veces el mismo cupón
    const existCupons = readCupons(); // objeto { [code]: discountPct }
    if (existCupons[code]) {
      Swal.fire({
        icon: "info",
        title: "Cupón ya canjeado",
        text: "Este cupón ya fue aplicado.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
      });
      return;
    }

    // Guardar cupón (solo porcentaje; validaremos contra carrito en el total)
    saveCupon({
      ...existCupons,
      [code]: tourByCoupon.cupon_discount,
    });

    Swal.fire({
      icon: "success",
      title: "Cupón canjeado",
      text: `Cupón aplicado: -${tourByCoupon.cupon_discount}%`,
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
    });

    await updateCartTotal(); // Actualiza el total si todo salio bien
  } catch (err) {
    console.error("Error validando cupón:", err);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo validar el cupón en este momento.",
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
    });
  }
};

export const onAddToCart = async (sku) => {
  if (sku) {
    await onAddTourToCart(sku);
    await updateCartModal(readCart());
    await updateCartTotal();
    
  }
};

/**
 * @function
 * Calcula los descuentos de los cupones que un usuario tiene
 * @param data {Object} - Los datos de todos los tours disponibles
 */
export const getCoupons = (data) => {
  if (!data) {
    return {
      totalCouponsPct: 0,
      codesApplied: [],
    };
  }
  const cartObj = JSON.parse(localStorage.getItem("cart") || "{}");
  const savedCupons = readCupons(); // { [code]: pct }
  const cartIds = new Set(Object.keys(cartObj || {}));

  const codesApplied = [];
  let totalCouponsPct = 0; // total del descuento del coupon
  if (savedCupons && Object.keys(savedCupons).length) {
    for (const [code, pct] of Object.entries(savedCupons)) {
      // Buscar el tour dueño del cupón
      const tourForCode = data.find(
        (t) =>
          String(t.cupon_code || "")
            .trim()
            .toUpperCase() === String(code).trim().toUpperCase()
      );

      // Aplica si y solo si el tour del cupón está en el carrito
      if (tourForCode && cartIds.has(String(tourForCode.sku))) {
        totalCouponsPct += Number(pct) || 0;
        codesApplied.push(code);
      }
    }
  }
  return {
    totalCouponsPct,
    codesApplied,
  };
};
/**
 * @function
 * Usada para retornar el subtotal del carrito de compras y los descuentos aplicados
 * @param {Object} data - Los datos de todos los tours disponibles
 * @returns {Object} - El Subtotal y el total de descuentos
 */
export const getSubTotalAndDiscounts = (data) => {
  const cartObj = JSON.parse(localStorage.getItem("cart") || "{}");
  let subtotal = 0;

  const totalOfDiscounts = [];

  for (const [sku, qtyRaw] of Object.entries(cartObj)) {
    const tour = data.find((t) => String(t.sku) === String(sku));

    if (!tour) continue;

    const qty = Math.max(0, Number(qtyRaw) || 0);
    const price = Number(tour.price_usd) || 0;
    const discountPct = Number(tour.discount) || 0;
    totalOfDiscounts.push(discountPct * qty);

    const line = qty * price;
    subtotal += line;
  }

  return {
    subtotal,
    totalOfDiscounts,
  };
};
/**
 *
 * @param {Object} params - Requiere del Subtotal, de la cantidad de dinero de los descuentos asi como el total de descuentos en porcentage
 * @returns
 */
export const getTotal = ({
  subtotal,
  itemDiscountDollars,
  totalCouponsPct,
}) => {
  //  Total parcial tras descuentos por tour
  let totalAfterItemDiscount = parseFloat(subtotal - itemDiscountDollars);
  // Descuento por cupones sobre el total parcial
  let couponsDiscountDollars = 0;
  if (totalCouponsPct > 0 && totalAfterItemDiscount > 0) {
    couponsDiscountDollars = totalAfterItemDiscount * (totalCouponsPct / 100);
  }
  // Total final numerico
  let finalTotal = totalAfterItemDiscount - couponsDiscountDollars;

  // Total final con signo de dolar
  const finalFmt = `$${finalTotal}`;

  return {
    finalTotal,
    finalFmt,
  };
};

/**
 * Calcula y actualiza el total del carrito en el elemento con id="cartTotal"
 */
export const updateCartTotal = async () => {
  const cartTotalEl = document.getElementById("cartTotal");
  const basketSubTotal = document.getElementById("subtotal-cart");
  const discount = document.getElementById("discount-cart");
  const totalEl = document.getElementById("total-cart");
  if (!cartTotalEl && !basketSubTotal && !discount) return;

  const existCartData = localStorage.getItem("cart");
  if (!existCartData) {
    const noProductsTotal = "$0.00";
    cartTotalEl.textContent = noProductsTotal;
    basketSubTotal.textContent = noProductsTotal;
    return;
  }

  const cartObj = JSON.parse(existCartData);

  try {
    const res = await fetch("/assets/data/tours.json");
    if (!res.ok) throw new Error("Error al cargar tours.json");

    const data = await res.json();
    let total = 0;
    let descuento = 0;

    // Suma cantidad × precio
    for (const [id, qty] of Object.entries(cartObj)) {
      const tour = data.find((t) => String(t.id) === String(id));
      if (tour && tour.priceUSD) {
        total += Number(qty) * Number(tour.priceUSD);
        descuento += Number(tour.discount);
      }
    }
    const finalTotal = `$${total.toFixed(2)}`;
    // Imprimir total formateado
    cartTotalEl.textContent = finalTotal;
    basketSubTotal.textContent = finalTotal;
    discount.textContent = `${descuento}%`;

    const valorPorcentaje = total * (descuento / 100);

    const totalFinal = total - valorPorcentaje;
    totalEl.textContent = `$${totalFinal.toFixed(2)}`;
  } catch (error) {
    console.error("Error al calcular el total:", error);
  }
};
/**
 * Lee el carrito del localStorage como objeto { [id]: qty }
 */
export const readCart = () => {
  try {
    return JSON.parse(localStorage.getItem("cart") || "{}");
  } catch {
    return {};
  }
};

/**
 * Guarda el carrito
 */
export const saveCart = (cartObj) => {
  localStorage.setItem("cart", JSON.stringify(cartObj));
};

/**
 * Actualiza los items adentro del Modal del carrito
 * @param {Object} cartObj - Objeto { [id]: qty }
 */
export const updateCartModal = (cartObj) => {
  const cartList = document.getElementById("cartList");
  if (!cartList) return;

  const ids = Object.keys(cartObj || {});
  if (ids.length === 0) {
    cartList.innerHTML = `
          <div class="text-center text-muted p-4">Tu carrito está vacío.</div>
          <a href="/tours.html" class="btn btn-danger m-auto">Comprar Tours</a>
        `;
    return;
  }

  fetch("/assets/data/tours.json")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then((data) => {
      // Para cada id del carrito busca el tour en el JSON
      const output = ids
        .map((id) => {
          const tour = data.find((t) => String(t.id) === String(id));
          if (!tour) return ""; // si no existe en el JSON, sáltalo

          const qty = Number(cartObj[id]) || 0;
          const price = Number(tour.priceUSD) || 0;
          const subtotal = (qty * price).toFixed(2);

          return `
                <aside class="list-group-item d-flex flex-column" data-tour-id="${
                  tour.id
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
                              data-tour-id="${tour.id}"
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
                              data-tour-id="${tour.id}"
                            />
                            <button
                              class="btn btn-primary btn-qty btn-add-quantity"
                              type="button"
                              data-action="plus"
                              data-tour-id="${tour.id}"
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
                            data-tour-id="${tour.id}"
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

      cartList.innerHTML = output;

      // Re-vincular eventos de + / − / input / eliminar
      attachCartItemEvents();
    })
    .catch((err) => console.error("Error:", err));
};

/**
 * Re-vincula los eventos para los elementos del modal
 */
export const attachCartItemEvents = () => {
  // +
  document.querySelectorAll(".btn-add-quantity").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.getAttribute("data-tour-id");
      changeQty(id, +1);
    });
  });

  // −
  document.querySelectorAll(".btn-substract-quantity").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.getAttribute("data-tour-id");
      changeQty(id, -1);
    });
  });

  // input directo
  document.querySelectorAll(".input-qty").forEach((input) => {
    input.addEventListener("change", (e) => {
      const id = e.currentTarget.getAttribute("data-tour-id");
      const val = Math.max(1, Number(e.currentTarget.value) || 1);
      setQty(id, val);
    });
  });

  // eliminar
  document.querySelectorAll(".btn-remove").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.getAttribute("data-tour-id");
      removeFromCart(id);
    });
  });
};

/**
 * Permite actualizar la cantidad total (badge del carrito)
 */
export const updateCartQuantity = () => {
  const quantityEl = document.getElementById("quantity");
  if (!quantityEl) return;

  const cartObj = readCart();
  const total = Object.values(cartObj).reduce(
    (acc, val) => acc + Number(val || 0),
    0
  );

  quantityEl.textContent = total;
  quantityEl.classList.toggle("d-none", total <= 0);
};

/**
 * Cambia la cantidad en ±1, con mínimo 1
 */
export const changeQty = (id, delta) => {
  const cart = readCart();
  const curr = Number(cart[id] || 0) + delta;
  if (curr <= 0) {
    delete cart[id];
  } else {
    cart[id] = curr;
  }
  saveCart(cart);
  updateCartModal(cart);
  updateCartQuantity();
  updateCartTotal();
  updateBasket();
};

/**
 * Fija la cantidad a un valor específico (mínimo 1)
 */
export const setQty = (id, qty) => {
  const cart = readCart();
  if (qty <= 0) {
    delete cart[id];
  } else {
    cart[id] = qty;
  }
  saveCart(cart);
  updateCartModal(cart);
  updateCartQuantity();
  updateCartTotal();
  updateBasket();
};

/**
 * Elimina un tour del carrito
 */
export const removeFromCart = (id) => {
  const cart = readCart();
  delete cart[id];
  saveCart(cart);
  updateCartModal(cart);
  updateCartQuantity();
  updateCartTotal();
  updateBasket();
};

/**
 * Agrega un Tour al Carrito
 * @param {string} id - Id del Tour
 */
export const onAddTourToCart = (id) => {
  const cart = readCart();
  cart[id] = (Number(cart[id]) || 0) + 1;
  saveCart(cart);

  updateCartModal(cart);
  updateCartQuantity();
  updateCartTotal();
};

/**
 * Actualiza todos los tours incluidos en el carrito de compras para la pagina del carrito (no es el modal)
 */
export const updateBasket = () => {
  const cart = readCart();
  const cartList = document.getElementById("cart-list-tours");
  if (!cartList) return;

  const ids = Object.keys(cart || {});
  if (ids.length === 0) {
    cartList.innerHTML = `
          <div class="text-center text-muted p-4">Tu carrito está vacío.</div>
          <a href="/tours.html" class="btn btn-danger m-auto">Comprar Tours</a>
        `;
    return;
  }

  fetch("/assets/data/tours.json")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then((data) => {
      // Para cada id del carrito busca el tour en el JSON
      const output = ids
        .map((id) => {
          const tour = data.find((t) => String(t.id) === String(id));
          if (!tour) return ""; // si no existe en el JSON, sáltalo

          const qty = Number(cart[id]) || 0;
          const price = Number(tour.priceUSD) || 0;
          const subtotal = (qty * price).toFixed(2);

          return `

            <article class="list-group-item p-3" data-tour-id="${tour.id}">
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
                          <h2 class="h6 mb-1">${tour.location}</h2>
                          <p class="text-muted small mb-2">
                            ${tour.description}
                          </p>
                          <div class="d-flex gap-2">
                            <button class="btn btn-link p-0 text-danger">
                              <i class="bi bi-trash3-fill display-6 btn-remove" data-tour-id="${
                                tour.id
                              }"></i>
                            </button>
                            <button class="btn btn-link p-0">
                              <i class="bi bi-bookmark-star-fill display-6"></i>
                            </button>
                          </div>
                        </div>
                        <div class="text-end d-none d-sm-block">
                          <div class="fw-semibold">$${String(
                            tour.priceUSD
                          ).toLocaleString("es-CR")}</div>
                          <div class="text-muted small">SKU: ${tour.id}</div>
                        </div>
                      </div>

                      <div class="row mt-3 g-2">
                        <div class="col-8 col-sm-6 col-md-5">
                          <label class="form-label small mb-1" for="qty-1"
                            >Cantidad</label
                          >
                          <div class="input-group">
                            <button
                              class="btn btn-danger btn-qty btn-substract-quantity"
                              type="button"
                              data-action="minus"
                              data-tour-id="${tour.id}"
                              aria-label="Disminuir cantidad"
                            >
                              −
                            </button>
                            <input
                              id="qty-1"
                              type="number"
                              class="form-control text-center input-qty"
                              value="${qty}"
                              min="1"
                              inputmode="numeric"
                               data-tour-id="${tour.id}"
                            />
                            <button
                              class="btn btn-primary btn-qty btn-add-quantity"
                              type="button"
                              aria-label="Aumentar"
                              data-action="plus"
                              data-tour-id="${tour.id}"
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

      // Re-vincular eventos de + / − / input / eliminar
      attachCartItemEvents();
    })
    .catch((err) => console.error("Error:", err));
};

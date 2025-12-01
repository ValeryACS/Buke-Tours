import {
  readCart,
  getTotal,
  getCoupons,
  getSubTotalAndDiscounts,
} from "./cart.module.js";
import {
  onlyDigits,
  containNumbers,
  setState,
  removeNumbers,
  removeLetters,
  isOnOrAfterToday,
} from "./utils.module.js";
/**
 * Setea los Tours en el formulario de checkout dependiendo de los tours guardados en el carrito
 * @returns {Promise<void>} - Actualiza el HTML5 adentro del accordion
 */
export const setTourDetailsForm = async () => {
  const cartData = readCart();
  const ids = Object.keys(cartData || {});
  const accordionContainer = document.getElementById(
    "accordionCheckoutTourList"
  );

  if (!ids.length || !accordionContainer) {
    return;
  }

  await fetch("/Buke-Tours/api/tours/")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then(({ data }) => {
      // Para cada id del carrito busca el tour en el JSON
      const output = ids
        .map((sku, index) => {
          const tour = data.find((t) => String(t.sku) === String(sku));
          if (!tour) return ""; // si no existe en el JSON lo ignora
          const checkoutResult = [];

          const quantityOfTours = Number(cartData[sku]); // la cantidad de tours que el usuario tiene adentro del carrito

          for (let tourIndex = 0; tourIndex < quantityOfTours; tourIndex++) {
            const accordionHtml = `
            <div class="accordion-item" data-tour-id="${sku}" data-accordion-id="${sku}-${tourIndex}" data-tour-discount="${tour.discount}">
                <h2 class="accordion-header" id="headingTour-${sku}-${tourIndex}">
                    <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTour-${sku}-${tourIndex}"
                        aria-expanded="true"
                        aria-controls="collapseTour-${sku}-${tourIndex}"
                    >
                    ${tour.title}
                    </button>
                </h2>
                <div
                id="collapseTour-${sku}-${tourIndex}"
                class="accordion-collapse collapse ${index === 0 ? "show" : ""}"
                aria-labelledby="headingTour-${sku}-${tourIndex}"
                data-bs-parent="#accordionCheckoutTourList"
                >
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-12 text-start">
                                <label
                                for="tour-name-${sku}"
                                class="form-label"
                                >Tour</label
                                >
                                <input
                                id="tour-name-${sku}"
                                name="tour-name-${sku}"
                                type="text"
                                class="form-control"
                                value="${tour.title}"
                                readonly
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaIngresoTour-${sku}"
                                class="form-label"
                                >Fecha de ingreso</label
                                >
                                <input
                                id="fechaIngresoTour-${sku}-${tourIndex}"
                                name="fechaIngresoTour-${sku}-${tourIndex}"
                                type="date"
                                class="form-control start-date-input"
                                data-tour-id="${tour.id}"
                                required
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaSalidaTour-${sku}"
                                class="form-label"
                                >Fecha de salida</label
                                >
                                <input
                                id="fechaSalidaTour-${sku}-${tourIndex}"
                                name="fechaSalidaTour-${sku}-${tourIndex}"
                                type="date"
                                class="form-control end-date-input"
                                data-tour-id="${tour.id}"
                                required
                                />
                            </div>

                            <div class="col-6 col-md-4 form-group">
                              <label for="adultos-${sku}" class="form-label"
                                >Adultos <span class="badge text-bg-success m-2">$${
                                  tour.price_usd
                                }</span></label
                              >
                              <input
                                id="adultos-${sku}"
                                name="adultos-${sku}"
                                type="number"
                                data-tour-id="${sku}"
                                data-tour-price="${tour.price_usd}"
                                min="1"
                                value="1"
                                class="form-control text-center adults-quantity tour-quantity"
                              />
                            </div>
                            <div class="col-6 col-md-4 form-group">
                              <label for="ninos-${sku}" class="form-label"
                                >Niños <span class="badge text-bg-success m-2">$${
                                  tour.price_usd
                                }</span></label
                              >
                              <input
                                id="ninos-${sku}"
                                name="ninos-${sku}"
                                type="number"
                                data-tour-id="${sku}"
                                data-tour-price="${tour.price_usd}"
                                min="0"
                                value="0"
                                class="form-control text-center children-quantity tour-quantity"
                              />
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            checkoutResult.push(accordionHtml);
          }
          return checkoutResult.join("");
        })
        .join("");

      accordionContainer.innerHTML = output;
    })
    .catch((err) => console.error("Error:", err));
};
/**
 * @funcion - Funcion que valida los inputs del formulario de checkout
 *
 * @param {Object} params - Objeto con los inputs a validar
 * @param {HTMLInputElement[]} params.stringsRequeridos - Inputs de tipo Texto
 * @param {HTMLInputElement[]} params.numerosRequeridos - Inputs de tipo Number
 * @param {[NodeListOf<HTMLInputElement>, NodeListOf<HTMLInputElement>]} params.fechasRequeridas - Arreglo de Inputs de tipo Fecha en formato ISO string
 * @returns {boolean} true si todo es válido; false si hay errores
 */
export const validateCheckoutForm = ({
  stringsRequeridos = [],
  numerosRequeridos = [],
  fechasRequeridas = [],
}) => {
  const errors = [];

  /**
   * Usada para ir agregando errores por cada input que incumpla su validacion
   * @param {HTMLInputElement} inputElement - El Input que presenta error
   * @param {string} errorMmsg - El Mensaje de error asociado al input
   */
  const pushErrorMessage = (inputElement, errorMmsg) => {
    errors.push({ inputElement, errorMmsg });
    setState(inputElement, false);
  };

  // ---- 1) Validando los inputs de tipo string o texto
  stringsRequeridos.forEach((inputElement) => {
    if (!inputElement) return;
    const inputValue = (inputElement.value ?? "").trim();
    const id = (inputElement.id || "").toLowerCase();

    // Input sin valor
    if (!inputValue) {
      return pushErrorMessage(
        inputElement,
        `El campo "${inputElement.labels?.[0]?.innerText || id}" es requerido.`
      );
    }

    if (id === "nombre") {
      // Valida el nombre
      if (containNumbers(inputValue)) {
        return pushErrorMessage(
          inputElement,
          "El nombre no puede contener números."
        );
      }
    }

    if (id === "email") {
      // Valida el email
      const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(inputValue); // Valida si es un email valido
      if (!isValidEmail)
        return pushErrorMessage(
          inputElement,
          "El correo electrónico no tiene un formato válido."
        );
    }

    if (id === "telefono") {
      // Valida el Telefono
      const digitosDelTelefono = onlyDigits(inputValue);
      const isValidTelephone = digitosDelTelefono.length >= 8; // mínimo razonable
      if (!isValidTelephone)
        return pushErrorMessage(
          inputElement,
          "El teléfono debe contener mínimo 8 dígitos."
        );
    }

    if (id === "pais") {
      // Valida el pais
      if (!inputElement.value) {
        return pushErrorMessage(
          inputElement,
          "Selecciona un país de residencia."
        );
      }
    }

    setState(inputElement, true);
  });

  // ---- 2) Valida los inputs de typo number
  const now = new Date();
  const curYY = now.getFullYear() % 100; // dos dígitos
  const curMM = now.getMonth() + 1;

  numerosRequeridos.forEach((inputElement) => {
    if (!inputElement) return;
    if (Array.isArray(inputElement)) {
      inputElement.forEach((numb) => {
        const id = (numb.id || "").toLowerCase();
        const numberValue = (numb.value ?? "").trim();

        const minAttr = numb.getAttribute("min");
        const number = Number(onlyDigits(numberValue)) || Number(numberValue);

        if (Number.isNaN(number)) {
          pushErrorMessage(
            numb,
            `El campo "${numb.labels?.[0]?.innerText || id}" debe ser numérico.`
          );
          return;
        }

        if (minAttr !== null && !Number.isNaN(Number(minAttr))) {
          const min = Number(minAttr);
          if (number < min) {
            pushErrorMessage(
              numb,
              `El campo "${
                numb.labels?.[0]?.innerText || id
              }" debe ser mayor o igual a ${min}.`
            );
            return;
          }
        }
      });
    } else {
      const id = (inputElement.id || "").toLowerCase();
      const numberValue = (inputElement.value ?? "").trim();

      // Input del numero vacio
      if (!numberValue) {
        pushErrorMessage(
          inputElement,
          `El campo "${
            inputElement.labels?.[0]?.innerText || id
          }" es requerido.`
        );
        return;
      }

      // por defecto: comprobar número y min (si existe)
      const minAttr = inputElement.getAttribute("min");
      const number = Number(onlyDigits(numberValue)) || Number(numberValue);

      if (Number.isNaN(number)) {
        pushErrorMessage(
          inputElement,
          `El campo "${
            inputElement.labels?.[0]?.innerText || id
          }" debe ser numérico.`
        );
        return;
      }

      if (minAttr !== null && !Number.isNaN(Number(minAttr))) {
        const min = Number(minAttr);
        if (number < min) {
          pushErrorMessage(
            inputElement,
            `El campo "${
              inputElement.labels?.[0]?.innerText || id
            }" debe ser mayor o igual a ${min}.`
          );
          return;
        }
      }

      // Reglas específicas de tarjeta
      if (id === "cardnumber") {
        const digits = onlyDigits(numberValue);
        if (digits.length < 13 || digits.length > 19) {
          return pushErrorMessage(
            inputElement,
            "El número de tarjeta debe tener entre 13 y 19 dígitos."
          );
        }
      }

      if (id === "cardmonth") {
        const mm = Number(onlyDigits(numberValue));
        if (mm < 1 || mm > 12) {
          return pushErrorMessage(
            inputElement,
            "El mes de expiración debe estar entre 01 y 12."
          );
        }
      }

      if (id === "cardyear") {
        // Representa el año
        let year = onlyDigits(numberValue);
        if (!(year.length === 2)) {
          return pushErrorMessage(
            inputElement,
            "El año de expiración debe tener 2 dígitos."
          );
        }
        const year2 = Number(year);

        // si tenemos el mes, comprobamos no expirada
        const monthEl = document.getElementById("cardMonth");
        if (monthEl && monthEl.value) {
          const mm = Number(onlyDigits(monthEl.value));
          // tarjeta expirada: año menor, o año igual y mes menor
          if (year2 < curYY || (year2 === curYY && mm < curMM)) {
            return pushErrorMessage(inputElement, "La tarjeta está expirada.");
          }
        } else if (year2 < curYY) {
          // si no hay mes, al menos año no menor al actual (mejor que nada)
          return pushErrorMessage(
            inputElement,
            "La tarjeta podría estar expirada (verifica mes y año)."
          );
        }
      }

      if (id === "cardcvv") {
        const digits = onlyDigits(numberValue);
        if (digits.length < 3 || digits.length > 4) {
          return pushErrorMessage(
            inputElement,
            "El CVV debe ser de 3 o 4 dígitos."
          );
        }
      }

      setState(inputElement, true);
    }
  });

  // ---- 3) Valida las fechas de los Tours
  const [fechasDeIngreso, fechasDeSalida] = fechasRequeridas; // Se espera un array con 2 NodeList: [ingresos, salidas]

  if (fechasDeIngreso && fechasDeSalida) {
    const ingresos = Array.from(fechasDeIngreso);
    const salidas = Array.from(fechasDeSalida);

    const cantidadTotalDeFechas = Math.max(ingresos.length, salidas.length);
    for (let i = 0; i < cantidadTotalDeFechas; i++) {
      const inputFechasDeIngreso = ingresos[i];
      const inputFechasDeSalida = salidas[i];

      // Validando la fecha de ingreso como requerida
      if (!inputFechasDeIngreso || !inputFechasDeIngreso.value) {
        pushErrorMessage(
          inputFechasDeIngreso || salidas[i],
          "La fecha de ingreso es requerida."
        );
        continue;
      }
      // Validando la fecha de salida como requerida
      if (!inputFechasDeSalida || !inputFechasDeSalida.value) {
        pushErrorMessage(
          inputFechasDeSalida || ingresos[i],
          "La fecha de salida es requerida."
        );
        continue;
      }

      // Pareseando las fechas para poder validar si la fecha de llegada es mayor a la fecha de salida
      const initialDate = new Date(inputFechasDeIngreso.value);
      const outDate = new Date(inputFechasDeSalida.value);
      if (Number.isNaN(initialDate.getTime())) {
        pushErrorMessage(
          inputFechasDeIngreso,
          "La fecha de ingreso no es válida."
        );
        continue;
      }
      if (Number.isNaN(outDate.getTime())) {
        pushErrorMessage(
          inputFechasDeSalida,
          "La fecha de salida no es válida."
        );
        continue;
      }
      if (outDate < initialDate) {
        pushErrorMessage(
          inputFechasDeSalida,
          "La fecha de salida no puede ser anterior a la de ingreso."
        );
        setState(inputFechasDeIngreso, false); // marca también la de ingreso
        continue;
      }
      if (!isOnOrAfterToday(inputFechasDeIngreso.value)) {
        pushErrorMessage(
          inputFechasDeIngreso,
          "La fecha de ingreso no puede ser anterior a hoy."
        );
        setState(inputFechasDeSalida, false);
        continue;
      }
      if (!isOnOrAfterToday(inputFechasDeSalida.value)) {
        pushErrorMessage(
          inputFechasDeSalida,
          "La fecha de salida no puede ser anterior a hoy."
        );
        setState(inputFechasDeIngreso, false);
        continue;
      }

      setState(inputFechasDeIngreso, true);
      setState(inputFechasDeSalida, true);
    }
  }

  // ---- 4) Imprimiendo los errores en el DOM
  if (errors.length) {
    const firstInputError = errors[0].inputElement;
    if (firstInputError && typeof firstInputError.focus === "function") {
      firstInputError.focus({ preventScroll: true });
      firstInputError.scrollIntoView({ behavior: "smooth", block: "center" });
    }
    // Lista de errores en HTML5
    const htmlList = `<ul style="margin:0;padding-left:1.1rem;text-align:left;">
      ${errors
        .slice(0, 9) // limitar para no saturar el toast
        .map((e) => `<li>${e.errorMmsg}</li>`)
        .join("")}
      ${
        errors.length > 6
          ? `<li>…y ${errors.length - 6} error(es) más.</li>`
          : ""
      }
    </ul>`;

    Swal.fire({
      icon: "error",
      title: "Datos Incompletos",
      html: htmlList,
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 6000,
      timerProgressBar: true,
    });

    return false;
  }
  return true; // Todo está ok
};
/**
 * @function - Usada para renderizar las banderas de los paises una vez que el usuario selecciona un pais
 * @returns {void}
 */
export const renderFlags = () => {
  const select = document.getElementById("pais");
  const flag = document.getElementById("flagCountry");
  const setFlag = (code) => {
    // Resetear clases
    flag.className = "fi";

    // Normalizar código
    const normalized = (code || "").toLowerCase();

    if (!normalized) {
      flag.classList.add("d-none");
      flag.removeAttribute("title");
      return;
    }

    // Mostrar bandera
    flag.classList.remove("d-none");
    flag.classList.add(`fi-${normalized}`);

    // Hint accesible (tooltip nativo del title)
    const label = select.options[select.selectedIndex]?.text || "";
    flag.setAttribute("title", label);
  };

  // Inicial (por si el select ya viene con un valor)
  setFlag(select.value);

  // Cambio dinámico
  select.addEventListener("change", (e) => setFlag(e.target.value));
};

/**
 * @function - Usada para evitar que el usuario ingrese valores incorrectos
 * @param {Object} params - Objeto que contiene los inputs que no aceptan numeros y los inputs que solo aceptan letras
 * @param {HTMLInputElement[]} params.inputTextStrings - Inputs de tipo Texto
 * @param {HTMLInputElement[]} params.inputNumbers - Inputs de tipo Number
 * @returns {void}
 */
export const setOnChangeCheckoutEvents = ({
  inputTextStrings,
  inputNumbers,
}) => {
  // --- Inputs tipo texto: no se permiten números
  inputTextStrings.forEach((element) => {
    if (!element) return;
    element.addEventListener("input", (event) => {
      const original = event.target.value;
      const cleaned = removeNumbers(original);
      if (original !== cleaned) {
        event.target.value = cleaned;
      }
    });
  });

  // --- Inputs tipo número: no se permiten letras
  inputNumbers.forEach((element) => {
    if (!element) return;
    if (Array.isArray(element)) {
      element.forEach((elm) => {
        elm.addEventListener("input", (event) => {
          const original = event.target.value;
          const cleaned = removeLetters(original);
          if (original !== cleaned) {
            event.target.value = cleaned;
          }
        });
      });
    } else {
      element.addEventListener("input", (event) => {
        const original = event.target.value;
        const cleaned = removeLetters(original);
        if (original !== cleaned) {
          event.target.value = cleaned;
        }
      });
    }
  });
};

/**
 * @function - Usada para calcular el monto extra dependiendo de si el usuario selecciona o no alguno de los checkobox opcionales
 * @param {Object} params - Un Objeto el cual contiene todos los extras
 * @param {Number} params.children - La cantidad de niños
 * @param {Number} params.adultos - La cantidad de adultos
 * @param {Boolean} params.hasBreakfast - Determina si la reservacion incluye Desayuno o no
 * @param {Boolean} params.hasLunch - Determina si la reservacion incluye Almuerzo o no
 * @param {Boolean} params.hasDinner - Determina si la reservacion incluye Cena o no
 * @param {Boolean} params.hasSecurity - Determina si la reservacion incluye Seguro Viajero
 * @param {Boolean} params.hasPhotos - Determina si la reservacion incluye Fotos o no
 * @param {Boolean} params.hasTransport - Determina si la reservacion incluye Transporte o no
 * @param {Number} params.subtotal - El Subtotal sin haber aplicado los costos extra
 * @param {Number} params.total - El Total final sin haber aplicado los costos extra
 * @return {void} - Actualiza el Total y el SubTotal
 */
export const calculateExtras = ({
  children,
  adultos,
  hasBreakfast,
  hasLunch,
  hasDinner,
  hasSecurity,
  hasPhotos,
  hasTransport,
  subtotal,
  total,
}) => {
  const breakfastCart = document.getElementById("breakfast-cart"); // El texto del Desayuno en el sidebar del Resumen de Pedido
  const launchCart = document.getElementById("launch-cart"); // El texto del Almuerzo en el sidebar del Resumen de Pedido
  const dinerCart = document.getElementById("diner-cart"); // El texto de la Cena en el sidebar del Resumen de Pedido
  const transportCart = document.getElementById("transport-cart"); // El texto del Transport en el sidebar del Resumen de Pedido
  const securityCart = document.getElementById("security-cart"); // El texto del Seguro Viajero en el sidebar del Resumen de Pedido
  const photoCart = document.getElementById("photos-cart"); // El texto del Paquete de Fotos en el sidebar del Resumen de Pedido
  const totalCartSidebar = document.getElementById("total-cart"); // El texto del Total en el sidebar del Resumen de Pedido
  const subTotalCartSidebar = document.getElementById("subtotal-cart"); // El texto del Subtotal en el sidebar del Resumen de Pedido
  const childrenSidebar = document.getElementById("children-sidebar-cart"); // El texto de los Niños en el sidebar del Resumen de Pedido
  const adultosSidebar = document.getElementById("adultos-sidebar-cart"); // El texto de los Adultos en el sidebar del Resumen de Pedido
  let newTotal = Number(total) || 0;
  let newSubTotal = Number(subtotal) || 0;

  const quantityOfPersons = Number(children) + Number(adultos); // Cantidad Total de Personas

  const breakFastCost = quantityOfPersons * 11; // Costo del Desayuno
  const lunchCost = quantityOfPersons * 12; // Costo del Almuerzo
  const dinerCost = quantityOfPersons * 17; // Costo de la Cena
  const securityCost = quantityOfPersons * 9; // Costo del Seguro Viajero
  const photosCost = quantityOfPersons * 15; // Costo del Paquete Fotografico
  const transportCost = quantityOfPersons * 30; // Costo del Transporte

  if (Number(children) > 0) {
    childrenSidebar.textContent = `x${Number(children)}`;
  } else {
    childrenSidebar.textContent = "x0";
  }

  if (quantityOfPersons > 1) {
    if (adultosSidebar) {
      adultosSidebar.textContent = `x${adultos}`;
    }
  } else {
    if (adultosSidebar) {
      adultosSidebar.textContent = `x1`;
    }
  }
  if (hasBreakfast) {
    newTotal = newTotal + breakFastCost;
    newSubTotal = newSubTotal + breakFastCost;
    breakfastCart.textContent = `$${breakFastCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    breakfastCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }
  if (hasLunch) {
    newTotal = newTotal + lunchCost;
    newSubTotal = newSubTotal + lunchCost;
    launchCart.textContent = `$${lunchCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    launchCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }

  if (hasDinner) {
    newTotal = newTotal + dinerCost;
    newSubTotal = newSubTotal + dinerCost;
    dinerCart.textContent = `$${dinerCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    dinerCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }
  if (hasSecurity) {
    newTotal = newTotal + securityCost;
    newSubTotal = newSubTotal + securityCost;
    securityCart.textContent = `$${securityCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    securityCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }
  if (hasPhotos) {
    newTotal = newTotal + photosCost;
    newSubTotal = newSubTotal + photosCost;
    photoCart.textContent = `$${photosCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    photoCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }
  if (hasTransport) {
    newTotal = newTotal + transportCost;
    newSubTotal = newSubTotal + transportCost;
    transportCart.textContent = `$${transportCost.toLocaleString("es-CR")}`;
  } else {
    newTotal = newTotal;
    newSubTotal = newSubTotal;
    transportCart.innerHTML = `<span class="badge text-bg-danger">No</span>`;
  }
  if (totalCartSidebar) {
    totalCartSidebar.textContent = `$${String(
      Number(newTotal)
    ).toLocaleString("es-CR")}`;
  }
  if (subTotalCartSidebar) {
    subTotalCartSidebar.textContent = `$${String(
      Number(newSubTotal)
    ).toLocaleString("es-CR")}`;
  }
};
/**
 * @function
 *
 * Calcula el total en base a la cantidad de personas y en base a la cantidad de dias ya que el precio del tour es por persona
 */
export const calculateAccordionTotal = (data) => {
  let subtotal = 0;
  let itemDiscountDollars = 0;
  let totalDescuento = 0;
  let diffDays = 1;

  const accordionItems = document.querySelectorAll(
    "#accordionCheckoutTourList .accordion-item"
  );

  accordionItems.forEach((item, index) => {
    const sku = item.getAttribute("data-tour-id");
    const tourDiscount = Number(item.getAttribute("data-tour-discount"));
    const startDate = item.querySelector(`#fechaIngresoTour-${sku}-${index}`);
    const endDate = item.querySelector(`#fechaSalidaTour-${sku}-${index}`);

    if (sku) {
      diffDays = getDaysDifference({
        startDate: String(startDate.value),
        endDate: String(endDate.value),
      });
    }
    const { totalOfDiscounts } = getSubTotalAndDiscounts(data);

    // Obtener el input dentro del accordion item
    const inputs = Array.from(item.querySelectorAll(".tour-quantity"));
    if (!inputs.length) return;

    // % efectivo de descuento por tour (para mostrar en el UI)
    const itemDiscountPctEffective = totalOfDiscounts.reduce(
      (prevValue, currentValue) => prevValue + currentValue,
      0
    );

    inputs.forEach((input) => {
      let price = Number(input.getAttribute("data-tour-price"));
      const qty = Number(input.value);
      if (diffDays >= 1) {
        price = price * diffDays;
      }

      if (!isNaN(price) && !isNaN(qty)) {
        subtotal += price * qty;
      }
      
    });
    itemDiscountDollars += parseFloat((subtotal * tourDiscount * diffDays) / 100);
    totalDescuento += diffDays * itemDiscountPctEffective;
    
  });

  // Aplicar cupones válidos (solo si su tour está en el carrito)
  const { totalCouponsPct } = getCoupons(data);
  const { finalTotal, finalFmt } = getTotal({
    subtotal,
    itemDiscountDollars,
    totalCouponsPct,
  });

  document.getElementById("subtotal-cart").textContent = `$${subtotal.toFixed(
    2
  )}`;
  document.getElementById("subtotal").value = subtotal.toFixed(2);
  document.getElementById("total-cart").textContent = finalFmt;
  document.getElementById("total").value = finalTotal.toFixed(2);
  document.getElementById("discount-cart").textContent = `-${totalDescuento}%`;

  document.getElementById("dias-cart").textContent = `x${diffDays}`;
};

/**
 * @function
 * Usada para calcular la cantidad de dias que hay al restar 2 fechas,comunmente usada para multiplicar la cantidad por las veces que fue reservado un Tour
 * @param {Object} params - Las fechas de reservacion
 * @param {string} params.startDate - La fecha del check-in
 * @param {string} params.endDate - La fecha del check-out
 * @returns {number} - La diferencia en dias con respecto a la fecha final - fecha inicial
 */
export const getDaysDifference = ({ startDate, endDate }) => {
  let daysDifference = 1;
  const start = new Date(startDate);
  const end = new Date(endDate);
  if (!isNaN(start) && !isNaN(end)) {
    const msPerDay = 24 * 60 * 60 * 1000;
    daysDifference = Math.floor((end - start) / msPerDay) + 1;
  }

  return daysDifference;
};

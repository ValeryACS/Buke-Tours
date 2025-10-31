import { readCart } from "./cart.module.js";
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

  await fetch("/assets/data/tours.json")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then((data) => {
      // Para cada id del carrito busca el tour en el JSON
      const output = ids
        .map((id, index) => {
          const tour = data.find((t) => String(t.id) === String(id));
          if (!tour) return ""; // si no existe en el JSON lo ignora
          return `
            <div class="accordion-item" data-tour-id="${id}">
                <h2 class="accordion-header" id="headingTour-${id}">
                    <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTour-${id}"
                        aria-expanded="true"
                        aria-controls="collapseTour-${id}"
                    >
                    ${tour.title}
                    </button>
                </h2>
                <div
                id="collapseTour-${id}"
                class="accordion-collapse collapse ${index === 0 ? "show" : ""}"
                aria-labelledby="headingTour-${id}"
                data-bs-parent="#accordionCheckoutTourList"
                >
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-12 text-start">
                                <label
                                for="tour-name-${id}"
                                class="form-label"
                                >Tour</label
                                >
                                <input
                                id="tour-name-${id}"
                                name="tour-name-${id}"
                                type="text"
                                class="form-control"
                                value="${tour.title}"
                                readonly
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaIngresoTour-${id}"
                                class="form-label"
                                >Fecha de ingreso</label
                                >
                                <input
                                id="fechaIngresoTour-${id}"
                                name="fechaIngresoTour-${id}"
                                type="date"
                                class="form-control start-date-input"
                                data-tour-id="${id}"
                                required
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaSalidaTour-${id}"
                                class="form-label"
                                >Fecha de salida</label
                                >
                                <input
                                id="fechaSalidaTour-${id}"
                                name="fechaSalidaTour-${id}"
                                type="date"
                                class="form-control end-date-input"
                                data-tour-id="${id}"
                                required
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
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
      pushErrorMessage(
        inputElement,
        `El campo "${inputElement.labels?.[0]?.innerText || id}" es requerido.`
      );
      return;
    }

    if (id === "nombre") {
      // Valida el nombre
      if (containNumbers(inputValue)) {
        pushErrorMessage(inputElement, "El nombre no puede contener números.");
        return;
      }
    }

    if (id === "viajeroprincipal") {
      // Valida el nombre del Viajero Principal
      if (containNumbers(inputValue)) {
        pushErrorMessage(
          inputElement,
          "El nombre del Viajero Principal no puede contener números."
        );
        return;
      }
    }

    if (id === "email") {
      // Valida el email
      const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(inputValue); // Valida si es un email valido
      if (!isValidEmail)
        pushErrorMessage(
          inputElement,
          "El correo electrónico no tiene un formato válido."
        );
      return;
    }

    if (id === "telefono") {
      // Valida el Telefono
      const digitosDelTelefono = onlyDigits(inputValue);
      const isValidTelephone = digitosDelTelefono.length >= 8; // mínimo razonable
      if (!isValidTelephone)
        pushErrorMessage(
          inputElement,
          "El teléfono debe contener mínimo 8 dígitos."
        );
      return;
    }

    if (id === "pais") {
      // Valida el pais
      if (!inputElement.value) {
        pushErrorMessage(inputElement, "Selecciona un país de residencia.");
        return;
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
    const id = (inputElement.id || "").toLowerCase();
    const numberValue = (inputElement.value ?? "").trim();

    // Input del numero vacio
    if (!numberValue) {
      pushErrorMessage(
        inputElement,
        `El campo "${inputElement.labels?.[0]?.innerText || id}" es requerido.`
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
    element.addEventListener("input", (event) => {
      const original = event.target.value;
      const cleaned = removeLetters(original);
      if (original !== cleaned) {
        event.target.value = cleaned;
      }
    });
  });
};

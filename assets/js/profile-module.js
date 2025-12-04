
/**
 * @funcion - Funcion que valida los inputs del formulario de Profile
 *
 * @param {Object} params - Objeto con los inputs a validar
 * @param {HTMLInputElement[]} params.stringsRequeridos - Inputs de tipo Texto
 * @param {HTMLInputElement[]} params.numerosRequeridos - Inputs de tipo Number
 * @param {[NodeListOf<HTMLInputElement>, NodeListOf<HTMLInputElement>]} params.fechasRequeridas - Arreglo de Inputs de tipo Fecha en formato ISO string
 * @returns {boolean} true si todo es válido; false si hay errores
 */
export const validateProfileForm =({
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
      if (!inputElement.value || inputElement.value ) {
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
import {
  setState,
  onlyDigits,
  isOnOrAfterToday,
  todayLocalISO,
} from "./utils.module.js";

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
    if(id === 'password'){
      const pattern = /^(?=(?:.*[A-Za-z]){4,})(?=(?:.*[AEIOUaeiou]){3,})(?=(?:.*\d){4,})(?=(?:.*[^A-Za-z0-9]){3,}).+$/;
      if (!pattern.test(inputValue)) {
        return pushErrorMessage(
          inputElement,
          "La contraseña debe contener al menos 4 letras, 3 vocales, 3 caracteres especiales y 4 números."
        );
      }
      const confirmPassword = stringsRequeridos.find((elemnt)=> elemnt.id ==='confirm-password');
      if(inputValue && confirmPassword.value && inputValue !== confirmPassword.value){
        return pushErrorMessage(
          inputElement,
          "La contraseña no coincide con la contraseña de confirmacion."
        );
      }
    }
    if(id === 'fullName'){
      if(inputValue.length <= 5){
        return pushErrorMessage(
          inputElement,
          "El Nombre completo debe contener al menos 5 caracteres."
        );
      }
    }
    if(id === 'idioma'){
      if(inputValue === 'no-seleccionado'){
        return pushErrorMessage(
          inputElement,
          "Debes Seleccionar un Idioma."
        );
      }
    }
    if(id === 'documento'){
      if(inputValue.length <7){
        return pushErrorMessage(
          inputElement,
          "El Documento debe contener mas de 7 caracteres."
        );
      }
    }
    if(id=== 'direccion'){
      if(inputValue.length <10){
        return pushErrorMessage(
          inputElement,
          "La Direccion de recinto debe contener mas de 10 caracteres."
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

    if (id === "country") {
      // Valida el pais
      if (!inputElement.value || inputElement.value === 'no-seleccionado' ) {
        return pushErrorMessage(
          inputElement,
          "Debes seleccionar un país de residencia."
        );
      }
    }

    if(id === 'ciudad'){
      if(inputValue.length < 4){
        return pushErrorMessage(
          inputElement,
          "La Ciudad debe contener mas de 4 caracteres."
        );
      }
    }

    if(id === 'provincia'){
      if(inputValue.length < 4){
        return pushErrorMessage(
          inputElement,
          "La Provincia debe contener mas de 4 caracteres."
        );
      }
    }

    setState(inputElement, true);
  });

  numerosRequeridos.forEach((inputElement) => {
    if (!inputElement) return;
    const id = (inputElement.id || "").toLowerCase();
    if (id === "zip") {
      const numberValue = Number(inputElement.value ?? 0);
        const digits = onlyDigits(numberValue);
        if (digits.length <  4) {
          return pushErrorMessage(
            inputElement,
            "El Codigo Postal debe tener minimo 4 caracteres."
          );
        }
    }

    setState(inputElement, true);
  }); 

  Array.from(fechasRequeridas).forEach((inputDate) => {
    if (!inputDate) return;

    let hasDateErrors = false;
    const labelText =
      inputDate.labels?.[0]?.innerText?.trim() ||
      inputDate.placeholder ||
      inputDate.id ||
      "La fecha";

    if (!inputDate.value) {
      pushErrorMessage(inputDate, `El campo "${labelText}" es requerido.`);
      hasDateErrors = true;
    }

    const parsedDate = inputDate.value ? new Date(inputDate.value) : null;
    if (!parsedDate || Number.isNaN(parsedDate.getTime())) {
      pushErrorMessage(inputDate, `${labelText} no es válida.`);
      hasDateErrors = true;
    }

    const inputId = (inputDate.id || "").toLowerCase();
    const isBirthdateField =inputId.includes("birthdate");

    if (!hasDateErrors && isBirthdateField) {
      if (inputDate.value > todayLocalISO()) {
        pushErrorMessage(
          inputDate,
          "La fecha de nacimiento no puede ser posterior a hoy."
        );
        hasDateErrors = true;
      }

      const hoy = new Date();
      let edad = hoy.getFullYear() - parsedDate.getFullYear();
      const mesDiff = hoy.getMonth() - parsedDate.getMonth();
      if (mesDiff < 0 || (mesDiff === 0 && hoy.getDate() < parsedDate.getDate())) {
        edad--;
      }
      if (edad < 18) {
        pushErrorMessage(
          inputDate,
          "Debes ser mayor de 18 años para reservar Tours."
        );
        hasDateErrors = true;
      }
    }

    if (!hasDateErrors && !isBirthdateField && !isOnOrAfterToday(inputDate.value)) {
      pushErrorMessage(
        inputDate,
        `${labelText} no puede ser anterior a hoy.`
      );
      hasDateErrors = true;
    }

    setState(inputDate, !hasDateErrors);
  });

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

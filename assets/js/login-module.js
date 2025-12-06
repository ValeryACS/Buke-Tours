import {
  setState
} from "./utils.module.js";

/**
 * @funcion - Funcion que valida los inputs del formulario de Login
 *
 * @param {Object} params - Objeto con los inputs a validar
 * @param {HTMLInputElement[]} params.stringsRequeridos - Inputs de tipo Texto
 * @returns {boolean} true si todo es válido; false si hay errores
 */
export const validateLoginForm =({
  stringsRequeridos = [],
}) => {
  const errors = [];
  /**
   * Usada para ir agregando errores por cada input que incumpla su validacion
   * @param {HTMLInputElement} inputElement - El Input que presenta error
   * @param {string} errorMessage - El Mensaje de error asociado al input
   */
  const pushErrorMessage = (inputElement, errorMessage) => {
    errors.push({ inputElement, errorMessage });
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

    setState(inputElement, true);
  });

  // Imprimiendo los errores en el DOM
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
        .map((e) => `<li>${e.errorMessage}</li>`)
        .join("")}
      ${
        errors.length > 6
          ? `<li>…y ${errors.length - 6} error(es) más.</li>`
          : ""
      }
    </ul>`;

    Swal.fire({
      icon: "error",
      title: errors?.[0].errorMessage ?? "Datos Incompletos o Incorrectos",
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

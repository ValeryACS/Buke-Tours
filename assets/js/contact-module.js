import { setState, onlyDigits } from "./utils.module.js";

/**
 * 
 * @param {Object} stringsRequeridos - Un Objeto con los string requeridos para determinar si el formulario esta validado o no 
 * @returns {Boolean} - Determina si fue validado el formulario satisfactoriamente - Si presenta errores retorna `false` caso contrario `true`
 */
export const validateContactForm = ({ stringsRequeridos = [] }) => {
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

  // Validando los inputs de tipo string o texto
  stringsRequeridos.forEach((inputElement) => {
    if (!inputElement) return;
    const inputValue = (inputElement.value ?? "").trim();
    const id = (inputElement.id || "").toLowerCase();

    if (!inputValue) {
      return pushErrorMessage(
        inputElement,
        `El campo "${inputElement.labels?.[0]?.innerText || id}" es requerido.`
      );
    }

    if (id === "nombre") {
      if (inputValue.length <= 5) {
        return pushErrorMessage(
          inputElement,
          "El Nombre completo debe contener al menos 5 caracteres."
        );
      }
    }
    if (id === "asunto") {
      if (inputValue.length < 6) {
        return pushErrorMessage(
          inputElement,
          "El asunto debe contener mas de 6 caracteres."
        );
      }
    }
    if (id === "mensaje") {
      if (inputValue.length < 10) {
        return pushErrorMessage(
          inputElement,
          "El Mensaje debe contener mas de 10 caracteres."
        );
      }
    }
    if (id === "email") {
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

    setState(inputElement, true);
  });

  // Imprimiendo los errores en el DOM
  if (errors.length) {
    const firstInputError = errors[0].inputElement;
    if (firstInputError && typeof firstInputError.focus === "function") {
      firstInputError.focus({ preventScroll: true });
      firstInputError.scrollIntoView({ behavior: "smooth", block: "center" });
    }
    const htmlList = `<ul style="margin:0;padding-left:1.1rem;text-align:left;">
      ${errors
        .slice(0, 9)
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
  return true; // El Formulario de Contacto ha sido validado exitosamente
};

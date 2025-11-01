/**
 * @function - Usada para  agregar o quitar la clase que determina si un input presenta errores o no
 * @param {HTMLInputElement} inputElement - El elemento de HTML a validar
 * @param {boolean} valid - Determina si fue validado o no
 */
export const setState = (inputElement, valid) => {
  if (!inputElement) return;
  inputElement.classList.remove("is-valid", "is-invalid");
  inputElement.classList.add(valid ? "is-valid" : "is-invalid");
};

/**
 * @function - Determina si un string contiene números o no
 * @param {string} texto - Texto a validar
 * @returns {boolean}
 */
export const containNumbers = (texto) => {
  for (const char of texto) {
    if (!isNaN(char) && char.trim() !== "") return true;
  }
  return false;
};
/**
 * @function - Usada para para eliminar números de un texto
 * @param {string} value - El valor del input a editar
 */
export const removeNumbers = (value = "") => value.replace(/[0-9]/g, "");
/**
 * @función - Usada para eliminar letras en inputs donde se esperan números
 * @param {string} value - El valor del input a editar
 **/
export const removeLetters = (value = "") => value.replace(/[^\d]/g, ""); // deja solo dígitos (0–9)
/**
 * Reemplaza las letras y deja solo numeros
 * @param {string} str - string a validar
 * @returns
 */
export const onlyDigits = (str = "") => String(str).replace(/\D+/g, ""); // Elimina letras y solo deja numeros
/**
 * @function - Usada para retornar la fecha en un formato year-month-day
 * @returns {string} - La fecha en formato yyyy-mm-dd
 */
export const todayLocalISO = () => {
  const d = new Date();
  d.setHours(0, 0, 0, 0);
  const yyyy = d.getFullYear();
  const mm = String(d.getMonth() + 1).padStart(2, "0");
  const dd = String(d.getDate()).padStart(2, "0");
  return `${yyyy}-${mm}-${dd}`;
};
/**
 * @function - Funcion usada para saber si una fecha es anterior a la fecha de hoy
 * @param {string} iso - La fecha en formato ISO
 * @returns {boolean}
 */
export const isOnOrAfterToday = (iso) => {
  if (!iso) return false;
  return iso >= todayLocalISO();
};

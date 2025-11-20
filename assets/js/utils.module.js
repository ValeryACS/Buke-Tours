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

/**
 * @function - Usada para mostrar un elemento usando las clases bootstrap
 * @param {HTMLElement} element - El elemento a mostrar
 * @param {string} className - La clase para mostrar el elemento
 */
export const showElement = (element, className = 'd-block') => {
  element.classList.add(className);
  element.classList.remove("d-none");
};

/**
 * @function - Usada para esconder un elemento usando las clases bootstrap
 * @param {HTMLElement} element - El elemento a ocultar
 * @param {string} className - La clase para ocultar el elemento
 */
export const hideElement = (element, className= 'd-block') => {
  element.classList.add("d-none");
  element.classList.remove(className);
};

/**
 * @function - Usada para normalizar un string y convertirlo a letras minusculas comunmnete usada para comparar strings
 * @param {string | undefined} value 
 * @returns {string} - El valor del string ya normalizado
 */
export const normalizeString = (value) =>
  String(value || "")
    .normalize("NFD")// convierte los caracteres acentuados (como á, é, ñ, etc.) en una forma descompuesta.
    .replace(/\p{Diacritic}/gu, "")// eliminar los signos diacríticos como (acentos, tildes, diéresis, etc.)
    .toLowerCase()
    .trim();
/**
 * @funtion Redirecciona a un Tour en Especifico
 * @param {string} id - ID del tour
 */
export const onClickViewTour = (id) => {
    console.log("Tour Clicked:", id);// TODO redirigir a la pagina del tour
    window.location.href = "/Buke-Tours/tour/"
};
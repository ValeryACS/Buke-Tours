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
    window.location.href = `/Buke-Tours/tour/index.php?tourID=${id}`;
};

/**
 * @function - Usada para evitar que el usuario ingrese valores incorrectos
 * @param {Object} params - Objeto que contiene los inputs que no aceptan numeros y los inputs que solo aceptan letras
 * @param {HTMLInputElement[]} params.inputTextStrings - Inputs de tipo Texto
 * @param {HTMLInputElement[]} params.inputNumbers - Inputs de tipo Number
 * @returns {void}
 */
export const setOnChangeEvents = ({
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
          const minValue = Number(elm.getAttribute("min"));

          if(Number(cleaned)<= minValue){
            event.target.value = minValue;
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
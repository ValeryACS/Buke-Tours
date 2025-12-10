//                 FUNCIONES DE UTILIDAD

/**
 * @function - Usada para agregar o quitar la clase que determina si un input presenta errores o no
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
export const removeLetters = (value = "") => value.replace(/[^\d.]/g, ""); // deja solo dígitos y puntos decimales

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

    // --- Inputs tipo número: no se permiten letras, solo números y decimales
    inputNumbers.forEach((element) => {
        if (!element) return;
        element.addEventListener("input", (event) => {
            const original = event.target.value;
            let cleaned = removeLetters(original);
            
            // Permite solo un punto decimal
            const parts = cleaned.split('.');
            if (parts.length > 2) {
                cleaned = parts[0] + '.' + parts.slice(1).join('');
            }

            if (original !== cleaned) {
                event.target.value = cleaned;
            }
        });
    });
};


//                 LÓGICA PRINCIPAL DEL FORMULARIO

document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Obtener elementos del DOM
    const form = document.getElementById('createTourForm');
    
    // Inputs de Texto
    const nombreInput = document.getElementById('nombre');
    const descripcionInput = document.getElementById('descripcion');
    const ubicacionInput = document.getElementById('ubicacion');
    const imgInput = document.getElementById('img'); // URL

    // Inputs Numéricos (NOT NULL)
    const priceInput = document.getElementById('price_usd');
    const ratingInput = document.getElementById('rating');
    const durationInput = document.getElementById('duration_hours');
    const discountInput = document.getElementById('discount');
    const adultsLimitInput = document.getElementById('adults_limit');
    const childrenLimitInput = document.getElementById('children_limit');
    
    // Inputs Opcionales
    const cuponCodeInput = document.getElementById('cupon_code');
    const iframeInput = document.getElementById('iframe');

    // 2. Aplicar eventos de limpieza en tiempo real
    setOnChangeEvents({
        inputTextStrings: [nombreInput, ubicacionInput], 
        inputNumbers: [
            priceInput, ratingInput, durationInput, 
            discountInput, adultsLimitInput, childrenLimitInput
        ],
    });

    // 3. Funciones de Validación Específicas
    
    /**
     * @function - Usada para determinar si un campo numérico es válido según sus límites
     * @param {HTMLElement} inputElement - El elemento a validar
     * @param {number} min - Valor mínimo permitido
     * @param {number} [max=Infinity] - Valor máximo permitido
     * @returns {boolean}
     */
    const validateNumberField = (inputElement, min, max = Infinity) => {
        const value = Number(inputElement.value);
        // Validar: no vacío, es un número, y cumple con los límites
        const valid = inputElement.value.length > 0 && !isNaN(value) && value >= min && value <= max;
        setState(inputElement, valid);
        return valid;
    };

    const validateNombre = () => {
        const value = nombreInput.value.trim();
        const valid = value.length > 0 && !containNumbers(value);
        setState(nombreInput, valid);
        return valid;
    };

    const validateDescripcion = () => {
        const value = descripcionInput.value.trim();
        const valid = value.length >= 20; 
        setState(descripcionInput, valid);
        return valid;
    };

    const validateUbicacion = () => {
        const value = ubicacionInput.value.trim();
        const valid = value.length > 0 && !containNumbers(value); 
        setState(ubicacionInput, valid);
        return valid;
    };
    
    const validateImg = () => {
        const value = imgInput.value.trim();
        // Validación de URL simple: no vacía y empieza con http(s)://
        const valid = value.length > 0 && /^https?:\/\/.*/i.test(value);
        setState(imgInput, valid);
        return valid;
    }


    // 4. Adjuntar eventos de validación en tiempo real

    nombreInput.addEventListener('input', validateNombre);
    descripcionInput.addEventListener('input', validateDescripcion);
    ubicacionInput.addEventListener('input', validateUbicacion);
    imgInput.addEventListener('input', validateImg);

    // Adjuntar validaciones específicas para números
    // price_usd: min 1
    priceInput.addEventListener('input', () => validateNumberField(priceInput, 1)); 
    // rating: min 1, max 5
    ratingInput.addEventListener('input', () => validateNumberField(ratingInput, 1, 5)); 
    // duration_hours: min 0.5
    durationInput.addEventListener('input', () => validateNumberField(durationInput, 0.5)); 
    // discount: min 0, max 100
    discountInput.addEventListener('input', () => validateNumberField(discountInput, 0, 100)); 
    // adults_limit: min 1
    adultsLimitInput.addEventListener('input', () => validateNumberField(adultsLimitInput, 1)); 
    // children_limit: min 0
    childrenLimitInput.addEventListener('input', () => validateNumberField(childrenLimitInput, 0)); 


    // 5. Manejar el envío del formulario (Validación Final)
    form.addEventListener('submit', function(event) {
        
        // Ejecutar todas las validaciones
        const isNombreValid = validateNombre();
        const isDescripcionValid = validateDescripcion();
        const isUbicacionValid = validateUbicacion();
        const isImgValid = validateImg();
        
        const isPriceValid = validateNumberField(priceInput, 1);
        const isRatingValid = validateNumberField(ratingInput, 1, 5);
        const isDurationValid = validateNumberField(durationInput, 0.5);
        const isDiscountValid = validateNumberField(discountInput, 0, 100);
        const isAdultsValid = validateNumberField(adultsLimitInput, 1);
        const isChildrenValid = validateNumberField(childrenLimitInput, 0);

        // Si alguna validación falla, previene el envío.
        if (
            !isNombreValid || !isDescripcionValid || !isUbicacionValid || !isImgValid || 
            !isPriceValid || !isRatingValid || !isDurationValid || !isDiscountValid || 
            !isAdultsValid || !isChildrenValid
        ) {
            event.preventDefault(); 
            // Esto asegura que los mensajes de error de Bootstrap se mantengan visibles
            // y que el formulario no se envíe al servidor con datos inválidos.
        }
    });
});
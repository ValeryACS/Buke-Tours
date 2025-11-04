# Buke Tours
E-commerce de venta de Tours Vacacionales


## 🔍 Funcionalidades principales
### 🧭 Búsqueda de tours

Permite filtrar tours según título, ubicación o descripción.

Utiliza la función normalizeString() para buscar sin acentos ni mayúsculas.

Los resultados se renderizan dinámicamente en el contenedor #search-result o #search-tours-results.

### 🛒 Carrito de compras

Administrado desde cart.module.js.

El usuario puede agregar tours desde cualquier vista (index o tours).

Se almacena en localStorage para mantener persistencia entre páginas.

### 💳 Checkout

Validación de formulario con alertas visuales usando Bootstrap.

Cálculo del subtotal y total dinámicamente según los ítems seleccionados.


### 💡 Tecnologías utilizadas

HTML5 / Bootstrap 5.3 / JavaScript (ES6+) /SweetAlert2 / Fetch API 

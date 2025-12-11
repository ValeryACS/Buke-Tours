import { setOnChangeEvents } from "../utils.module.js";

function validateTourForm({
  stringsRequeridos = [],
  numerosRequeridos = [],
  ratingInput,
  discountInput,
}) {
  let isValid = true;
  const errores = [];

  const limpiarEstado = (inputs) => {
    inputs.forEach((input) => {
      if (!input) return;
      input.classList.remove("is-invalid");
    });
  };

  limpiarEstado([...stringsRequeridos, ...numerosRequeridos, ratingInput, discountInput]);

  const marcarError = (input, mensaje) => {
    if (!input) return;
    isValid = false;
    input.classList.add("is-invalid");
    errores.push(mensaje);
  };

  
  stringsRequeridos.forEach((input) => {
    if (!input) return;
    if (!input.value || input.value.trim() === "") {
      marcarError(input, `El campo "${input.id}" es obligatorio.`);
    }
  });

  
  numerosRequeridos.forEach((input) => {
    if (!input) return;
    const valor = parseFloat(input.value);
    if (isNaN(valor)) {
      marcarError(input, `El campo "${input.id}" debe ser un número válido.`);
      return;
    }
    if (valor < 0) {
      marcarError(input, `El campo "${input.id}" no puede ser negativo.`);
    }
  });

  
  if (ratingInput) {
    const ratingVal = parseFloat(ratingInput.value);
    if (isNaN(ratingVal) || ratingVal < 1 || ratingVal > 5) {
      marcarError(
        ratingInput,
        'El campo "rating" debe estar entre 1.0 y 5.0.'
      );
    }
  }

  
  if (discountInput) {
    const discountVal = parseFloat(discountInput.value);
    if (isNaN(discountVal) || discountVal < 0 || discountVal > 100) {
      marcarError(
        discountInput,
        'El campo "discount" debe estar entre 0 y 100.'
      );
    }
  }

  if (!isValid && errores.length) {
    Swal.fire({
      icon: "error",
      title: "Revisa los datos del Tour",
      text: errores.join(" "),
      toast: false,
      position: "top",
      showConfirmButton: true,
    });
  }

  return isValid;
}

document.addEventListener("DOMContentLoaded", () => {
  const tourForm = document.getElementById("tour-form");
  if (!tourForm) return;

  tourForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const sku           = document.getElementById("sku");
    const title         = document.getElementById("title");
    const location      = document.getElementById("location");
    const description   = document.getElementById("description");
    const priceUSD      = document.getElementById("price_usd");
    const rating        = document.getElementById("rating");
    const durationHours = document.getElementById("duration_hours");
    const adultsLimit   = document.getElementById("adults_limit");
    const childrenLimit = document.getElementById("children_limit");
    const discount      = document.getElementById("discount");
    const img           = document.getElementById("img");
    const cuponCode     = document.getElementById("cupon_code");
    const iframe        = document.getElementById("iframe");

    
    setOnChangeEvents({
      inputTextStrings: [sku, title, location, description, img, cuponCode, iframe],
      inputNumbers: [priceUSD, rating, durationHours, adultsLimit, childrenLimit, discount],
    });

    const esValido = validateTourForm({
      stringsRequeridos: [sku, title, location, description, img],
      numerosRequeridos: [priceUSD, durationHours, adultsLimit, childrenLimit],
      ratingInput: rating,
      discountInput: discount,
    });

    if (!esValido) return;

    const formData = new FormData(tourForm);

    try {
      const response = await fetch("/Buke-Tours/api/tours/create.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        const text = await response.text();
        Swal.fire({
          icon: "error",
          title: "Error al crear Tour",
          text: text || "Ocurrió un error en el servidor.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }

      const result = await response.json();

      if (!result?.success) {
        const msg =
          Array.isArray(result?.errors) && result.errors.length
            ? result.errors.join(", ")
            : result?.message || "No se pudo crear el tour.";
        Swal.fire({
          icon: "error",
          title: "El Tour no pudo ser creado",
          text: msg,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }

      Swal.fire({
        icon: "success",
        title: "Tour creado exitosamente",
        text: result?.message || "El tour ha sido creado exitosamente.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });

      setTimeout(() => {
        window.location.href = "/Buke-Tours/admin/tours/index.php";
      }, 3000);
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Error de red",
        text: "No se pudo conectar con el servidor. Intenta nuevamente.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 6000,
        timerProgressBar: true,
      });
    }
  });
});

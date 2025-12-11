import { setOnChangeEvents } from "../utils.module.js";

document.addEventListener("DOMContentLoaded", () => {
  const tourForm = document.getElementById("tour-form");
  if (!tourForm) return;

  tourForm.addEventListener("submit", async (event) => {
    event.preventDefault();


    const sku            = document.getElementById("sku");
    const title          = document.getElementById("title");
    const location       = document.getElementById("location");
    const description    = document.getElementById("description");
    const priceUSD       = document.getElementById("price_usd");
    const rating         = document.getElementById("rating");
    const durationHours  = document.getElementById("duration_hours");
    const adultsLimit    = document.getElementById("adults_limit");
    const childrenLimit  = document.getElementById("children_limit");
    const discount       = document.getElementById("discount");
    const img            = document.getElementById("img");
    const cuponCode      = document.getElementById("cupon_code");
    const iframe         = document.getElementById("iframe");

    
    setOnChangeEvents({
      inputTextStrings: [sku, title, location, description, img, cuponCode, iframe],
      inputNumbers: [priceUSD, rating, durationHours, adultsLimit, childrenLimit, discount],
    });

    
    if (
      !sku.value.trim() ||
      !title.value.trim() ||
      !location.value.trim() ||
      !description.value.trim() ||
      !priceUSD.value.trim()
    ) {
      alert("Todos los campos obligatorios deben estar llenos.");
      return;
    }

    const formData = new FormData(tourForm);

    try {
      console.log(" Enviando datos a create.php...");
      const response = await fetch("/Buke-Tours/api/tours/create.php", {
        method: "POST",
        body: formData,
      });

      console.log(" Respuesta recibida:", response);

      if (!response.ok) {
        alert("Error del servidor al crear tour. Código: " + response.status);
        return;
      }

      const result = await response.json();
      console.log("Resultado JSON:", result);

      if (!result?.success) {
        alert("No se pudo crear el tour: " + (result.message || "Error desconocido"));
        return;
      }

      alert("Tour creado exitosamente ");

      
      setTimeout(() => {
        window.location.href = "/Buke-Tours/admin/tours/index.php";
      }, 1500);

    } catch (err) {
      console.error(" Error en fetch de crear tour:", err);
      alert("Error de red al crear tour: " + (err?.message || err));
    }
  });
});


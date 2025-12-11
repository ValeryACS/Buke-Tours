// assets/js/tours/edit-tour-page.js
import { setOnChangeEvents } from "../utils.module.js";
import { validateProfileForm } from "../profile-module.js";

document.addEventListener("DOMContentLoaded", () => {
  const tourEditForm = document.getElementById("tour-edit-form");
  if (!tourEditForm) return;

  tourEditForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const id             = document.getElementById("tour_id");
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

    const isValid = validateProfileForm({
      stringsRequeridos: [sku, title, location, description],
      numerosRequeridos: [priceUSD, rating, durationHours, adultsLimit, childrenLimit, discount],
      fechasRequeridas: [],
    });

    if (!isValid) return;

    const formData = new FormData(tourEditForm);
    formData.set("id", id.value); // nos aseguramos que vaya el id correcto

    try {
      const response = await fetch("/Buke-Tours/api/tours/update.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        const text = await response.text();
        Swal.fire({
          icon: "error",
          title: "Error al actualizar Tour",
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
            : result?.message || "No se pudo actualizar el tour.";
        Swal.fire({
          icon: "error",
          title: "El Tour no pudo ser actualizado",
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
        title: "Tour actualizado exitosamente",
        text: result?.message || "El tour ha sido actualizado exitosamente.",
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


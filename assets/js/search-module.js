import { onAddToCart } from "./cart.module.js";

(() => {
  /**
   * @function
   * Usada para filtrar las busquedas en el Home Page
   */
  document.addEventListener("DOMContentLoaded", async () => {
    const searchResult = document.getElementById("search-result");
    const slider = document.getElementById("swiper-slider-tours");
    const inputSearch = document.getElementById("search-input");

    const tours = await fetch("/assets/data/tours.json")
      .then((res) => {
        if (!res.ok) throw new Error("Error al cargar el JSON");
        return res.json();
      })
      .catch((err) => {
        console.error("Error Fetching Tours", err);
        return [];
      });

    const showEl = (el) => {
      el.classList.add("d-block");
      el.classList.remove("d-none");
    };
    const hideEl = (el) => {
      el.classList.add("d-none");
      el.classList.remove("d-block");
    };

    const normalizeString = (s) =>
      String(s || "")
        .normalize("NFD")
        .replace(/\p{Diacritic}/gu, "")
        .toLowerCase()
        .trim();

    inputSearch.addEventListener("input", (event) => {
      const inputValue = normalizeString(event.currentTarget.value);

      // Si está vacío: mostrar slider, ocultar resultados y limpiar HTML.
      if (!inputValue) {
        showEl(slider);
        hideEl(searchResult);
        searchResult.innerHTML = "";
        return;
      }

      const toursFiltered = tours.filter((tour) => {
        const t = normalizeString(tour.title);
        const l = normalizeString(tour.location);
        const d = normalizeString(tour.description);
        return (
          t.includes(inputValue) ||
          l.includes(inputValue) ||
          d.includes(inputValue)
        );
      });

      if (!toursFiltered.length) {
        showEl(slider);
        hideEl(searchResult);
        searchResult.innerHTML = "";
        // Solo mostrar alerta si hay texto (ya lo hay) y no hay resultados
        Swal.fire({
          icon: "error",
          title: "Tour no encontrado",
          text: `No se encontró ningún tour relacionado a "${event.currentTarget.value.trim()}".`,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });
        return;
      }

      // Render de resultados
      hideEl(slider);
      showEl(searchResult);

      const html = toursFiltered
        .map(
          (tour) => `
          <div class="result-content d-flex flex-column flex-md-row align-items-center search-item w-100" data-tour-id="${tour.id}">
            <img
              src="${tour.img}"
              alt="${tour.title}"
              title="${tour.title}"
              loading="lazy"
              class="tour-imagen"
            />
            <div class="overlay-effect px-5">
              <h1>
                ${tour.title}
                <i
                  class="bi bi-cart-plus-fill display-4 add-to-cart"
                  data-bs-toggle="modal"
                  data-bs-target="#cartModal"
                  data-tour-id="${tour.id}"
                ></i>
                <i class="bi bi-cursor-fill view-tour-page" data-tour-id="${tour.id}"></i>
              </h1>
              <p>${tour.description}</p>
            </div>
          </div>`
        )
        .join("");

      searchResult.innerHTML = html;
      document.querySelectorAll(".add-to-cart").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const id = e.currentTarget.getAttribute("data-tour-id");
          onAddToCart(id);
        });
      });
    });
  });
})();

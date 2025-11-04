import { onAddToCart } from "./cart.module.js";
import { hideElement, normalizeString, onClickViewTour, showElement } from "./utils.module.js";

(() => {
  /**
   * @function
   * Usada para filtrar las busquedas en el Home Page
   */
  document.addEventListener("DOMContentLoaded", async () => {
    const searchResult = document.getElementById("search-result");
    const slider = document.getElementById("swiper-slider-tours");
    const inputSearch = document.getElementById("search-input");
    const searchBtn = document.getElementById("btn-search");
    const searchInputTour = document.getElementById("search-input-tour");
    const toursContainer = document.getElementById("tours");
    const searchResultsContainer = document.getElementById(
      "search-tours-results"
    );
    const btnSearchTours = document.getElementById("btn-search-tours");

    const tours = await fetch("/assets/data/tours.json")
      .then((res) => {
        if (!res.ok) throw new Error("Error al cargar el JSON");
        return res.json();
      })
      .catch((err) => {
        console.error("Error Fetching Tours", err);
        return [];
      });
    /**
     * @function - Busca tours y los renderiza en el Home Page
     * @param {string} inputValue - El Input Search
     * @returns {void}
     */
    const renderSearch = (inputValue) => {
      try {
        // Si está vacío: mostrar slider, ocultar resultados y limpiar HTML.
        if (!inputValue) {
          showElement(slider);
          hideElement(searchResult);
          searchResult && (searchResult.innerHTML = "");
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
          showElement(slider);
          hideElement(searchResult);
          searchResult && (searchResult.innerHTML = "");
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
        hideElement(slider);
        showElement(searchResult);

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
        if (searchResult) {
          searchResult.innerHTML = html;
        }
        document.querySelectorAll(".add-to-cart").forEach((btn) => {
          btn.addEventListener("click", (e) => {
            const id = e.currentTarget.getAttribute("data-tour-id");
            onAddToCart(id);
          });
        });
        document.querySelectorAll('.view-tour-page')?.forEach((btn)=> {
              btn.addEventListener('click', (event)=> {
                  console.log('Tour Clciked')
                  event.preventDefault();
                    const id = event.currentTarget.getAttribute("data-tour-id");
                  onClickViewTour(id);    
              })
          })
      } catch (error) {
        console.error(error);
      }
    };

    /**
     * @function - Busca tours y los renderiza en la pagina de Tours
     * @param {string} inputValue - El Input Search
     * @returns {void}
     */
    const renderSearchTour = (inputValue) => {
      try {
        // Si está vacío: mostrar slider, ocultar resultados y limpiar HTML.
        if (!inputValue) {
          showElement(toursContainer, "d-flex");
          hideElement(searchResultsContainer, "d-flex");
          searchResultsContainer && (searchResultsContainer.innerHTML = "");
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
          showElement(toursContainer, "d-flex");
          hideElement(searchResultsContainer, "d-flex");
          searchResultsContainer && (searchResultsContainer.innerHTML = "");
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
        hideElement(toursContainer, "d-flex");
        showElement(searchResultsContainer, "d-flex");

        const html = toursFiltered
          .map(
            (tour) => `
         <div class="col d-flex">
            <div class="card card-travel w-100 h-100 d-flex flex-column">
              <img class="card-img-top" src="${tour.img}" alt="${tour.title}" />
              <div class="card-body d-flex flex-column">
                <div class="tag text-center fs-3 fw-medium">${
                  tour.location
                }</div>
                <h5 class="card-title mt-2">${tour.title}</h5>
                <p class="card-text mb-0">${tour.description ?? ""}</p>
              </div>
              <div class="d-flex">
                <button type="button" data-id="${
                  tour.id
                }" class="btn btn-dark w-50 py-3 add-to-cart-btn rounded-2">
                  <i class="bi bi-cart-plus display-6" data-bs-toggle="modal" data-bs-target="#cartModal"></i>
                </button>
                <button 
                  type="button" 
                  class="btn btn-dark w-50 py-3 read-more rounded-2" 
                  data-id="${tour.id}">
                  <i class="bi bi-cursor-fill display-6"></i>
                </button>
              </div>
            </div>
          </div>`
          )
          .join("");

        if (searchResultsContainer) {
          searchResultsContainer.innerHTML = html;
        }
        document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
          btn.addEventListener("click", (e) => {
            const id = e.currentTarget.getAttribute("data-tour-id");
            onAddToCart(id);
          });
        });
        document.querySelectorAll('.view-tour-page')?.forEach((btn)=> {
              btn.addEventListener('click', (event)=> {
                  console.log('Tour Clciked')
                  event.preventDefault();
                    const id = event.currentTarget.getAttribute("data-tour-id");
                  onClickViewTour(id);    
              })
          })
      } catch (error) {
        console.error("error", error);
      }
    };

    searchBtn?.addEventListener("click", (event) => {
      event.preventDefault();
      const inputValue = normalizeString(inputSearch.value);
      renderSearch(inputValue);
    });

    inputSearch?.addEventListener("blur", (event) => {
      const inputValue = normalizeString(event.currentTarget.value);
      renderSearch(inputValue);
    });

    searchInputTour?.addEventListener("blur", (event) => {
      const inputValue = normalizeString(event.currentTarget.value);
      renderSearchTour(inputValue);
    });

    btnSearchTours?.addEventListener("click", (event) => {
      event.preventDefault();
      const inputValue = normalizeString(searchInputTour.value);
      renderSearchTour(inputValue);
    });
  });
})();

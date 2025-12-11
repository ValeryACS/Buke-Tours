import { onAddToCart } from "./cart.module.js";
import {
  hideElement,
  normalizeString,
  onClickViewTour,
  showElement,
} from "./utils.module.js";

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
    const checkInInput = document.getElementById("check-in-date");
    const checkOutInput = document.getElementById("check-out-date");
    const clearHomeBtn = document.getElementById("btn-clear-home-search");
    const clearToursBtn = document.getElementById("btn-clear-tours-search");
    const homePageSearchInput = document.getElementById("search-result");
    const tourPageSearchInput = document.getElementById("search-tours-results");

    /**
     * @function - Usada para hacer el request a la Base de Datos para buscar tours
     * @param {Object} params - Los parametros de Busqueda utilizados para filtrar la consulta
     * @returns {Array<Object>} - La Respuesta con todos los tours filtrados
     */
    const fetchToursData = async (params = {}) => {
      const query = new URLSearchParams();
      Object.entries(params).forEach(([key, value]) => {
        if (value) query.append(key, value);
      });
      const endpoint = query.toString()
        ? `/Buke-Tours/api/tours/?${query.toString()}`
        : "/Buke-Tours/api/tours/";

      try {
        const response = await fetch(endpoint);
        if (!response.ok) throw new Error("Error al cargar el JSON");
        const payload = await response.json();
        return Array.isArray(payload) ? payload : payload.data ?? [];
      } catch (err) {
        console.error("Error Fetching Tours", err);
        return [];
      }
    };

    let tours = await fetchToursData();
    /**
     * @function - Busca tours y los renderiza en el Home Page
     * @param {string} inputValue - El Input Search que el usuario escribio en el buscador
     * @param {string} checkInDate - El valor del input para el Check In, normalmente una fecha en formato ISO
     * @param {string} checkOutDate - El valor del input para el Check Out, normalmente una fecha en formato ISO
     * @returns {void}
     */
    const renderSearch = async (
      inputValue,
      checkInDate = "",
      checkOutDate = ""
    ) => {
      try {
        const hasSearchTerm = Boolean(inputValue);
        const hasDateFilters = Boolean(checkInDate || checkOutDate);

        if (
          checkInDate &&
          checkOutDate &&
          new Date(checkInDate) > new Date(checkOutDate)
        ) {
          Swal.fire({
            icon: "warning",
            title: "Rango inválido",
            text: "La fecha de salida debe ser mayor o igual a la fecha de entrada.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        // Si no hay filtros ni texto, mostrar slider y limpiar resultados
        if (!hasSearchTerm && !hasDateFilters) {
          showElement(slider);
          hideElement(searchResult);
          searchResult && (searchResult.innerHTML = "");
          return;
        }

        let toursData = tours;
        if (hasDateFilters) {
          toursData = await fetchToursData({
            check_in_date: checkInDate,
            check_out_date: checkOutDate,
          });
        }

        const toursFiltered = toursData.filter((tour) => {
          const t = normalizeString(tour.title);
          const l = normalizeString(tour.location);
          const d = normalizeString(tour.description);
          const matchesText = hasSearchTerm
            ? t.includes(inputValue) ||
              l.includes(inputValue) ||
              d.includes(inputValue)
            : true;
          return matchesText;
        });

        if (!toursFiltered.length) {
          showElement(slider);
          hideElement(searchResult);
          searchResult && (searchResult.innerHTML = "");
          // Solo mostrar alerta si hay texto y no hay resultados
          Swal.fire({
            icon: "error",
            title: "Tour no encontrado",
            text: "No se encontró ningún tour relacionado con tu búsqueda.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
          });
          return;
        }

        // Renderizando los resultados solo en caso de que `toursFiltered` tenga datos
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
                  data-tour-id="${tour.sku}"
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
        document.querySelectorAll(".view-tour-page")?.forEach((btn) => {
          btn.addEventListener("click", (event) => {
            console.log("Tour Clciked");
            event.preventDefault();
            const id = event.currentTarget.getAttribute("data-tour-id");
            onClickViewTour(id);
          });
        });
      } catch (error) {
        console.error(error);
      }
    };
    /**
     * @function - Usada para definir los parametros de busqueda de los tours en base al valor de los inputs en la pagina del Home Page.
     * @returns {object}
     */
    const getHomeSearchFilters = () => ({
      term: normalizeString((inputSearch?.value ?? "").trim()),
      checkIn: checkInInput?.value ?? "",
      checkOut: checkOutInput?.value ?? "",
    });
    /**
     * @function - Usada para definir los parametros de busqueda de los tours en base al valor de los inputs en la pagina de tours.
     * @returns {object}
     */
    const getTourSearchFilters = () => ({
      term: normalizeString((searchInputTour?.value ?? "").trim()),
      checkIn: checkInInput?.value ?? "",
      checkOut: checkOutInput?.value ?? "",
    });
    /**
     * @function Usada para limpiar el buscador del Home Page
     * @returns {Promise<object>}
     */
    const resetHomeSearch = async () => {
      if (inputSearch) inputSearch.value = "";
      if (checkInInput) checkInInput.value = "";
      if (checkOutInput) checkOutInput.value = "";
      await renderSearch("", "", "");
    };
    /**
     * @function Usada para limpiar el buscador de la seccion de tours
     * @returns {Promise<object>}
     */
    const resetTourSearch = async () => {
      if (searchInputTour) searchInputTour.value = "";
      if (checkInInput) checkInInput.value = "";
      if (checkOutInput) checkOutInput.value = "";
      await renderSearchTour("", "", "");
    };

    /**
     * @function - Busca tours y los renderiza en la pagina de Tours
     * @param {string} inputValue - El Input Search
     * @param {string} checkInDate - Fecha de check in
     * @param {string} checkOutDate - Fecha de check out
     * @returns {void}
     */
    const renderSearchTour = async (
      inputValue,
      checkInDate = "",
      checkOutDate = ""
    ) => {
      try {
        const hasSearchTerm = Boolean(inputValue);
        const hasDateFilters = Boolean(checkInDate || checkOutDate);

        if (
          checkInDate &&
          checkOutDate &&
          new Date(checkInDate) > new Date(checkOutDate)
        ) {
          Swal.fire({
            icon: "warning",
            title: "Rango inválido",
            text: "La fecha de salida debe ser mayor o igual a la fecha de entrada.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
          });
          return;
        }

        if (!hasSearchTerm && !hasDateFilters) {
          showElement(toursContainer, "d-flex");
          hideElement(searchResultsContainer, "d-flex");
          searchResultsContainer && (searchResultsContainer.innerHTML = "");
          return;
        }

        const params = new URLSearchParams();
        if (checkInDate) params.append("check_in_date", checkInDate);
        if (checkOutDate) params.append("check_out_date", checkOutDate);
        const endpoint = params.toString()
          ? `/Buke-Tours/api/tours/?${params.toString()}`
          : "/Buke-Tours/api/tours/";

        let toursData = [];
        try {
          const response = await fetch(endpoint);
          if (!response.ok) throw new Error("Error al cargar el JSON");
          const payload = await response.json();
          toursData = Array.isArray(payload) ? payload : payload.data ?? [];
        } catch (err) {
          console.error("Error Fetching Tours", err);
          toursData = [];
        }

        const toursFiltered = toursData.filter((tour) => {
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
          Swal.fire({
            icon: "error",
            title: "Tour no encontrado",
            text: "No se encontró ningún tour con los criterios seleccionados.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
          });
          return;
        }

        // Renderizando los resultados solo en caso de que `toursFiltered` tenga datos
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
                  tour.sku
                }" class="btn btn-dark w-50 py-3 add-to-cart-btn rounded-2">
                  <i class="bi bi-cart-plus display-6" data-bs-toggle="modal" data-bs-target="#cartModal"></i>
                </button>
                <button 
                  type="button" 
                  class="btn btn-dark w-50 py-3 read-more rounded-2" 
                  data-id="${tour.sku}">
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
        document.querySelectorAll(".view-tour-page")?.forEach((btn) => {
          btn.addEventListener("click", (event) => {
            console.log("Tour Clciked");
            event.preventDefault();
            const id = event.currentTarget.getAttribute("data-tour-id");
            onClickViewTour(id);
          });
        });
      } catch (error) {
        console.error("error", error);
      }
    };
    /**
     * @function - Usada para iniciar la busqueda de Tours en la seccion de Tours
     */
    const handleTourSearch = async () => {
      const { term, checkIn, checkOut } = getTourSearchFilters();
      await renderSearchTour(term, checkIn, checkOut);
    };
    /**
     * @function - Usada para iniciar la busqueda de Tours en el Home Page
     */
    const handleHomeSearch = async (event) => {
      event?.preventDefault?.();
      const { term, checkIn, checkOut } = getHomeSearchFilters();
      await renderSearch(term, checkIn, checkOut);
    };

    searchBtn?.addEventListener("click", handleHomeSearch);

    inputSearch?.addEventListener("blur", async () => {
      await handleHomeSearch();
    });

    clearHomeBtn?.addEventListener("click", async (event) => {
      event.preventDefault();
      await resetHomeSearch();
    });

    searchInputTour?.addEventListener("blur", async () => {
      await handleTourSearch();
    });

    btnSearchTours?.addEventListener("click", async (event) => {
      event.preventDefault();
      await handleTourSearch();
    });

    clearToursBtn?.addEventListener("click", async (event) => {
      event.preventDefault();
      await resetTourSearch();
    });

    if (homePageSearchInput) {
      checkInInput?.addEventListener("change", handleHomeSearch);
      checkOutInput?.addEventListener("change", handleHomeSearch);
    }

    if (tourPageSearchInput) {
      checkInInput?.addEventListener("change", handleTourSearch);
      checkOutInput?.addEventListener("change", handleTourSearch);
    }
  });
})();

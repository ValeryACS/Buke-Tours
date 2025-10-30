import { readCart } from "./cart.module.js";
/**
 * Setea los Tours en el formulario de checkout dependiendo de los tours guardados en el carrito
 */
export const setTourDetailsForm = () => {
  const cartData = readCart();
  const ids = Object.keys(cartData || {});
  const accordionContainer = document.getElementById(
    "accordionCheckoutTourList"
  );

  if (!ids.length || !accordionContainer) {
    return;
  }
  console.log("Seteando Tours", cartData);
  console.log("ids", ids);

  fetch("/assets/data/tours.json")
    .then((res) => {
      if (!res.ok) throw new Error("Error al cargar el JSON");
      return res.json();
    })
    .then((data) => {
      // Para cada id del carrito busca el tour en el JSON
      const output = ids
        .map((id) => {
          const tour = data.find((t) => String(t.id) === String(id));
          if (!tour) return ""; // si no existe en el JSON, sáltalo
          return `
            <div class="accordion-item" data-tour-id="${id}">
                <h2 class="accordion-header" id="headingTour-${id}">
                    <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTour-${id}"
                        aria-expanded="true"
                        aria-controls="collapseTour-${id}"
                    >
                    ${tour.title}
                    </button>
                </h2>
                <div
                id="collapseTour-${id}"
                class="accordion-collapse collapse show"
                aria-labelledby="headingTour-${id}"
                data-bs-parent="#accordionCheckoutTourList"
                >
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label
                                for="tour-name-${id}"
                                class="form-label"
                                >Tour</label
                                >
                                <input
                                id="tour-name-${id}"
                                name="tour-name-${id}"
                                type="text"
                                class="form-control"
                                value="${tour.title}"
                                readonly
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaIngresoTour-${id}"
                                class="form-label"
                                >Fecha de ingreso</label
                                >
                                <input
                                id="fechaIngresoTour-${id}"
                                name="fechaIngresoTour-${id}"
                                type="date"
                                class="form-control start-date-input"
                                data-tour-id="${id}"
                                required
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label
                                for="fechaSalidaTour-${id}"
                                class="form-label"
                                >Fecha de salida</label
                                >
                                <input
                                id="fechaSalidaTour-${id}"
                                name="fechaSalidaTour-${id}"
                                type="date"
                                class="form-control end-date-input"
                                data-tour-id="${id}"
                                required
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        })
        .join("");

      accordionContainer.innerHTML = output;
      attachCheckoutEvents();
    })
    .catch((err) => console.error("Error:", err));
};
/**
 * Funcion usada para guardar la fecha de salida del tour
 * @param {string} id  - ID del tour
 * @param {string} endDate - La fecha de salida del tour
 */
const setEndDate = (id, endDate) => {
    console.log('id', id)
    console.log('endDate', endDate)
}
/**
 * Funcion usada para guardar la fecha de ingreso
 * @param {string} id  - ID del tour
 * @param {string} startDate - La fecha de llegada al tour
 */
const setStartDate = (id, startDate) => {
    console.log('id', id)
    console.log('startDate', startDate)
}
/**
 * Funcion usada para setear los eventos de los inputs del formulario del checkout
 */
const attachCheckoutEvents = () => {
    document.querySelectorAll(".end-date-input").forEach((input) => {
        input.addEventListener("change", (e) => {
            const id = e.currentTarget.getAttribute("data-tour-id");
            const endDate = new Date(e.currentTarget.value).toISOString();
            setEndDate(id, endDate);
        });
    });

    document.querySelectorAll(".start-date-input").forEach((input) => {
        input.addEventListener("change", (e) => {
            const id = e.currentTarget.getAttribute("data-tour-id");
            const startDate = new Date(e.currentTarget.value).toISOString();
            setStartDate(id, startDate);
        });
    });
}

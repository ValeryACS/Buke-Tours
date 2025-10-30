import {
  onAddTourToCart,
  updateCartModal,
  readCart,
  updateCartTotal
} from "./cart.module.js";

(() => {
 document.addEventListener("DOMContentLoaded", () => {
    const tours = document.getElementById("tours");

    const onClickViewTour = (id) => {
      console.log("Tour Clicked:", id);
    }

    const onAddToCart = (id) => {
        if (id) {
          onAddTourToCart(id);
          updateCartModal(readCart());
          updateCartTotal();
        }
    }

    const skeleton = document.getElementById("skeleton-tours")
    fetch("/assets/data/tours.json")
      .then((res) => {
        if (!res.ok) throw new Error("Error al cargar el JSON");
        return res.json();
      })
      .then((data) => {
        const htmlOutput = data.map(
          (tour) => `
          <div class="col d-flex">
            <div class="card card-travel w-100 h-100 d-flex flex-column">
              <img class="card-img-top" src="${tour.img}" alt="${tour.title}" />
              <div class="card-body d-flex flex-column">
                <div class="tag text-center fs-3 fw-medium">${tour.location}</div>
                <h5 class="card-title mt-2">${tour.title}</h5>
                <p class="card-text mb-0">${tour.description ?? ""}</p>
              </div>
              <div class="d-flex">
                <button type="button" data-id="${tour.id}" class="btn btn-dark w-50 py-3 add-to-cart-btn rounded-2">
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
        );

        tours.innerHTML = htmlOutput.join("");
        skeleton.classList.add("d-none")
        document.querySelectorAll(".read-more").forEach((btn) => {
          btn.addEventListener("click", (e) => {
            const id = e.currentTarget.getAttribute("data-id");
            onClickViewTour(id);
          });
        });

        document.querySelectorAll(".add-to-cart-btn").forEach((btn) => {
          btn.addEventListener("click", (e) => {
            const id = e.currentTarget.getAttribute("data-id");
            onAddToCart(id);
          });
        });
      })
      .catch((err) => console.error("Error:", err));
  });
})();

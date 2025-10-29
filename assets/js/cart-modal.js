import {
  updateCartQuantity,
  updateCartModal,
  readCart,
  updateCartTotal,
  onAddTourToCart,
} from "./cart.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", () => {
    // Botones "Agregar al carrito"
    const cartTours = document.querySelectorAll(".add-to-cart");
    cartTours.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const id = e.currentTarget.getAttribute("data-tour-id");
        if (id) onAddTourToCart(id);
      });
    });

    // Inicializa badge + modal (si corresponde)
    updateCartQuantity();
    updateCartModal(readCart());
    updateCartTotal();
  });
})();

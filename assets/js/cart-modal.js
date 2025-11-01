import {
  updateCartQuantity,
  updateCartModal,
  readCart,
  updateCartTotal,
  onAddTourToCart,
} from "./cart.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    // Botones de "Agregar al carrito"
    const cartTours = document.querySelectorAll(".add-to-cart");
    cartTours.forEach((btn) => {
      // Por cada elemento que contiene la clase add-to-cart le agrega un evento
      btn.addEventListener("click", async (e) => {
        const id = e.currentTarget.getAttribute("data-tour-id");
        if (id) {
          await onAddTourToCart(id);
        }
      });
    });

    // Inicializa badge + modal (si corresponde)
    updateCartQuantity();
    await updateCartModal(readCart());
    await updateCartTotal();
  });
})();

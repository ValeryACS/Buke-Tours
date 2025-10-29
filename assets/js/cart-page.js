import {
  updateBasket,
  updateCartTotal,
  validateCoupon,
} from "./cart.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", () => {
    updateBasket();
    updateCartTotal();

    const btnCoupon = document.getElementById("btn-coupon");
    const inputCoupon = document.getElementById("coupon");

    btnCoupon.addEventListener("click", () => {
      if (!inputCoupon.value.length) {
        Swal.fire({
          icon: "error",
          title: "Cupon Inválido",
          text: "El Código del Cupon es inválido",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }
      validateCoupon(inputCoupon.value);
    });
  });
})();

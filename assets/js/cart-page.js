import {
  updateBasket,
  updateCartTotal,
  validateCoupon,
  readCart
} from "./cart.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    await updateBasket();
    await updateCartTotal();
    const cartProducts = readCart();

    console.log('cartProducts', cartProducts)

    if(Object.keys(cartProducts).length){
      const summary = document.getElementById("summary-content");
      const cartSummarySkeleton = document.getElementById("cart-summary-payment")

      if(summary){
        summary.classList.remove('d-none');
      }

      if(cartSummarySkeleton){
         cartSummarySkeleton.classList.add('d-none');
      }
    }

    const btnCoupon = document.getElementById("btn-coupon");
    const inputCoupon = document.getElementById("coupon");

    btnCoupon.addEventListener("click", async () => {
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
      await validateCoupon(inputCoupon.value);
    });
  });
})();

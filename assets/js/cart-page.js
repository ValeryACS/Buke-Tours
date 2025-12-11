import {
  updateBasket,
  updateCartTotal,
  validateCoupon,
  readCart,
} from "./cart.module.js";
import { getLanguageData } from "./language-module.js";

(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    const language = await getLanguageData();
    const t = (key, fallback) => language?.[key] ?? fallback;
    await updateBasket();
    await updateCartTotal();
    const cartProducts = readCart();

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
          title: t("coupon_invalid_title", "Cupón inválido"),
          text: t("coupon_invalid_text", "El código del cupón es inválido."),
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
